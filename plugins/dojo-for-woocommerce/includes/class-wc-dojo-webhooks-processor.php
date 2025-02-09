<?php

/**
 * Dojo Webhooks Processor
 *
 * @package    Dojo_For_WooCommerce
 * @subpackage Dojo_For_WooCommerce/includes
 * @author     Dojo
 * @link       http://dojo.tech/
 */


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('WC_Dojo_Webhooks_Processor')) {

	require_once __DIR__ . '/class-wc-dojo-logger.php';
	require_once __DIR__ . '/class-wc-dojo-apiclient.php';
	require_once __DIR__ . '/class-wc-dojo-telemetry-apiclient.php';
	require_once __DIR__ . '/class-wc-dojo-utils.php';
	require_once __DIR__ . '/models/class-wc-dojo-payment-intent-event.php';
	require_once __DIR__ . '/models/class-wc-dojo-payment-intent.php';

	/**
	 * Dojo Webhooks Processor
	 *
	 */
	class WC_Dojo_Webhooks_Processor
	{

		/**
		 * API Client
		 *
		 * @var WC_Dojo_ApiClient
		 */
		private $api_client;

		/**
		 * Dojo Logger
		 *
		 * @var WC_Dojo_Logger
		 */
		private $logger;

		public function __construct($api_client)
		{
			$this->api_client = $api_client;
			$this->logger = new WC_Dojo_Logger();
		}

		/**
		 *  Process webhook call
		 */
		public function process_webhook_call($secret, $api_secret)
		{
			try {
				$wh_secret_missing = false;
				$request_body = WP_REST_Server::get_raw_data();

				if (!empty($secret)) {
					$this->validate_signature($request_body, $secret);
				} else {
					$this->logger->log(
						"Warning",
						"process_webhook_call", 
						"Signature validation skipped since webhook secret not provided!",
						$api_secret
					);
					$wh_secret_missing = true;
				}

				$this->logger->log(
					"Info",
					"process_webhook_call", 
					sprintf("Received notification request: %s", json_encode($request_body, true)),
					$api_secret
				);

				$event = new WC_Dojo_Payment_Intent_Event(json_decode($request_body, true));

				if (!$event->is_payment_status_update()) {
					$this->logger->log(
						"Info",
						"process_webhook_call", 
						'Event is ignored, since not PI update.',
						$api_secret
					);
					wp_send_json(["status" => "200", "title" => "event ignored"], 200);
				}

				$order = WC_Dojo_Utils::get_order_by_payment_intent_id($event->payment_intent_id);

				if ($order === null) {
					throw new Exception("Order not found by payment intent ID inside webhook handler. Id: " . $event->payment_intent_id);
				}

				$order = new WC_Order($order_id);

				$current_order_status = $order->get_status();
				if ('pending' === $current_order_status || 'failed' === $current_order_status) {
					$payment_intent = $this->api_client->get_payment_intent($event->payment_intent_id, $api_secret);
					$order_note = '';

					switch ($payment_intent->payment_status) {
						case WC_Dojo_Payment_Intent::PAYMENT_STATUS_CODE_SUCCESS:

							$order->payment_complete();
							$order_note = sprintf(
								// Translators: %s - payment message.
								__('Payment processed successfully with message: %s.', 'woocommerce-dojo'),
								$payment_intent->message
							);
							WC()->cart->empty_cart();
							break;
						case WC_Dojo_Payment_Intent::PAYMENT_STATUS_CODE_UNKNOWN:
						default:
							if ($payment_intent->has_last_payment_attempt_declined()) {
								$order_note = __(
									'Payment processing failed. Please retry. Visit https://account.dojo.tech for more details.',
									'woocommerce-dojo'
								);
								$order->update_status('failed');
							}
							break;
					}
					if (!empty($order_note)) {
						$order->add_order_note($order_note);
						if ($wh_secret_missing) {
							$wh_secret_missing_order_note = __(
								'Webhook secret is not specified! 
									Please provide a valid webhook secret (in Dojo plugin settings), 
									otherwise it compromises your security. Next versions of the plugin 
									will ignore un-authenticated webhook calls.',
								'woocommerce-dojo'
							);
							$order->add_order_note($wh_secret_missing_order_note);
						}
					}
				} else {
					$this->logger->log(
						"Info",
						"process_webhook_call", 
						'Order ignored since not in "pending" status. Order Id: ' . $order_id,
						$api_secret
					);
				}

				// Success
				wp_send_json(["status" => "processed"], 200);
			} catch (Exception $ex) {
				$this->logger->log(
					"Error",
					"process_webhook_call", 
					$ex->getMessage(),
					$api_secret
				);
				wp_send_json(["status" => "401", "title" => "unauthorized"], 401);
			}
		}

		/**
		 * Validates dojo-signature (https://docs.dojo.tech/payments/plugins/woocommerce/configure#step-3-add-a-webhook-endpoint)
		 *
		 * @param string $request_body Raw JSON request body from the webhook request.
		 * @param string $secret Raw   Secret generated by for this webhook registration.
		 * @return void Throws, if validation fails.
		 */
		private function validate_signature($request_body, $secret)
		{
			$dojo_signature_header_value = $_SERVER['HTTP_DOJO_SIGNATURE'];

			if (empty($dojo_signature_header_value)) {
				throw new Exception("dojo-signature header is not provided");
			}

			// "sha256=EE-A5-9D-30-4D-BB-..." ->
			// eea59d4dbb...
			$dojo_signature = str_replace("sha256=", '', $dojo_signature_header_value);
			$dojo_signature = str_replace("-", '', $dojo_signature);
			$dojo_signature = strtolower($dojo_signature);

			$res = hash_hmac('sha256', $request_body, $secret);

			if ($res !== $dojo_signature) {
				throw new Exception("Signature provided in dojo-signature header is incorrect!");
			}
		}
	}
}
