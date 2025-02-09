<?php
/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      https://softdiscover.com/zigaform/wordpress-cost-estimator
 */
if ( ! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
?>
<div id="uiform-container" data-uiform-page="gateway_list" class="uiform-wrap">
<div class="space20"></div>
<div class="sfdc-row">
<div class="col-lg-12">
          <div class="widget widget-padding">
            <div class="widget-header">
              <i class="fa fa-list-alt"></i><h5><?php echo __('Payment gateway', 'FRocket_admin'); ?></h5>
            </div>
            <div class="widget-body">
              <div class="widget-forms sfdc-clearfix">
                  <form id="uiform-form-editgateway"
                          name="uiform-form-editgateway"
                          enctype="multipart/form-data"
                          method="post"
                          >
                  <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Name', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <input name="pg_name" 
                             id="pg_name" type="text" 
                             placeholder="<?php echo __('Type name payment gateway', 'FRocket_admin'); ?>" 
                             class="sfdc-form-control sfdc-col-md-7" value="<?php echo ( isset($pg_name) ) ? $pg_name : ''; ?>">
                    </div>
                  </div>
                  <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Paypal mail', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <input name="paypal_email" 
                             id="paypal_email" 
                             type="text" placeholder="<?php echo __('Type Paypal mail', 'FRocket_admin'); ?>" 
                             class="sfdc-form-control sfdc-col-md-7" 
                             value="<?php echo ( ! empty($paypal_email) ) ? $paypal_email : ''; ?>">
                    </div>
                  </div>
                 <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Currency', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                          <select class="sfdc-form-control input-sm" 
                                  name="paypal_currency"  
                                  id="paypal_currency" 
                                  data-placeholder="Select here.." >
                        <?php
                        foreach ( $currency_list as $frow) :
                            ?>
                            <?php $sel = ( $frow == $paypal_currency ) ? ' selected="selected"' : ''; ?>
                            <option value="<?php echo $frow; ?>" <?php echo $sel; ?>>
                              <?php echo $frow; ?>
                            </option>
                            <?php
                        endforeach;
                        ?>
                        <?php unset($frow); ?>
                        </select>
                    </div>
                  </div>
                      <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Method', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <label class="sfdc-radio-inline">
                        <input name="paypal_method" id="optionsRadios5" value="1" type="radio" <?php Uiform_Form_Helper::getChecked($paypal_method, 1); ?>>
                        <?php echo __('Total amount', 'FRocket_admin'); ?>
                      </label> 
                      <label class="sfdc-radio-inline">
                        <input name="paypal_method" id="optionsRadios6" value="0" type="radio" <?php Uiform_Form_Helper::getChecked($paypal_method, 0); ?> >
                        <?php echo __('Individuals items', 'FRocket_admin'); ?>
                      </label>
                    </div>
                  </div>
                  <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Return URL', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <input name="paypal_return_url" id="paypal_return_url" 
                             type="text" 
                             placeholder="<?php echo __('Type return URL', 'FRocket_admin'); ?>" 
                             class="sfdc-form-control sfdc-col-md-7" 
                             value="<?php echo ( isset($paypal_return_url) ) ? $paypal_return_url : ''; ?>">
                    </div>
                  </div>
                       <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Cancel URL', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <input name="paypal_cancel_url" id="paypal_cancel_url" 
                             type="text" 
                             placeholder="<?php echo __('Type cancel URL', 'FRocket_admin'); ?>" 
                             class="sfdc-form-control sfdc-col-md-7" 
                             value="<?php echo ( isset($paypal_cancel_url) ) ? $paypal_cancel_url : ''; ?>">
                    </div>
                  </div>
                 <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Status', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <label class="sfdc-radio-inline">
                        <input name="flag_status" id="optionsRadios1" value="1" type="radio" <?php Uiform_Form_Helper::getChecked($flag_status, 1); ?>>
                        <?php echo __('Enabled', 'FRocket_admin'); ?>
                      </label> 
                      <label class="sfdc-radio-inline">
                        <input name="flag_status" id="optionsRadios2" value="0" type="radio" <?php Uiform_Form_Helper::getChecked($flag_status, 0); ?> >
                        <?php echo __('Disabled', 'FRocket_admin'); ?>
                      </label>
                    </div>
                  </div>
                 <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Test mode', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-10">
                      <label class="sfdc-radio-inline">
                        <input name="pg_modtest" id="optionsRadios3" value="1" type="radio" <?php Uiform_Form_Helper::getChecked($pg_modtest, 1); ?>>
                        <?php echo __('Yes', 'FRocket_admin'); ?>
                      </label> 
                      <label class="sfdc-radio-inline">
                        <input name="pg_modtest" id="optionsRadios4" value="0" type="radio" <?php Uiform_Form_Helper::getChecked($pg_modtest, 0); ?> >
                        <?php echo __('No', 'FRocket_admin'); ?>
                      </label>
                    </div>
                  </div>
                       
                  <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Description', 'FRocket_admin'); ?></label>
                      <div class="sfdc-col-md-10">
                        <textarea name="pg_description" 
                                  id="pg_description" 
                                  style="height:100px;" rows="5"  
                                  placeholder="" 
                                  class="sfdc-form-control sfdc-col-md-7"><?php echo $pg_description; ?></textarea>
                      </div>
                  </div>
                  <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label"><?php echo __('Payment order', 'FRocket_admin'); ?></label>
                    <div class="sfdc-col-md-4">
                        <input name="pg_order" id="pg_order" value="<?php echo ( isset($pg_order) ) ? $pg_order : ''; ?>">
                    </div>
                  </div>
                  <div class="sfdc-form-group sfdc-clearfix">
                    <label class="sfdc-col-md-2 sfdc-control-label" style="padding-top:0px;">IPN URL</label>
                    <div class="sfdc-col-md-10">
                      <label class="label label-default">
                       <?php echo site_url('?uifm_costestimator_api_handler&zgfm_action=uifm_est_api_handler&paygat=2&uifm_mode=ipn'); ?>
                      </label> 
                       
                    </div>
                  </div>
                  <input type="hidden" name="pg_id" id="uiform_current_pgid" value="<?php echo $pg_id; ?>">
                </form>
              </div>
            </div>
            <div class="widget-footer">
               <button type="submit" class="sfdc-btn sfdc-btn-primary" onclick="javascript:rocketform.gateway_savepaypal();"><?php echo __('Save', 'FRocket_admin'); ?></button>
               <button type="button" class="sfdc-btn sfdc-btn-default"  onclick="javascript:rocketform.gateway_gotoList();return false;" ><?php echo __('Cancel', 'FRocket_admin'); ?></button>
            </div>
          </div>
</div>
</div>
</div>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function ($) {
    
    $("#pg_order").TouchSpin({
        verticalbuttons: true,
        min: 0,
        max: 10,
        stepinterval: 1,
        verticalupclass: 'sfdc-glyphicon sfdc-glyphicon-plus',
        verticaldownclass: 'sfdc-glyphicon sfdc-glyphicon-minus'
    }); 
});
//]]>
</script>
