<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Horoscope\Service;

use Prokerala\Api\Astrology\Traits\Service\TimeZoneAwareTrait;
use Prokerala\Api\Astrology\Transformer as TransformerAlias;
use Prokerala\Api\Horoscope\Result\DailyHoroscope;
use Prokerala\Common\Api\Client;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
final class DailyPrediction
{
    /** @use TimeZoneAwareTrait<DailyHoroscope> */
    use TimeZoneAwareTrait;
    /**
     * @var string
     */
    protected $slug = '/horoscope/daily';
    /** @var TransformerAlias<DailyHoroscope> */
    private $transformer;
    /**
     * @var \Prokerala\Common\Api\Client
     */
    private $apiClient;
    /**
     * @param Client $client Api client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
        $this->transformer = new TransformerAlias(DailyHoroscope::class);
        $this->addDateTimeTransformer($this->transformer);
    }
    /**
     * Fetch result from API.
     *
     * @param \DateTimeInterface $datetime Date and time
     *
     * @throws QuotaExceededException
     * @throws RateLimitExceededException
     */
    public function process(\DateTimeInterface $datetime, string $sign) : DailyHoroscope
    {
        $parameters = ['datetime' => $datetime->format('c'), 'sign' => $sign];
        $apiResponse = $this->apiClient->process($this->slug, $parameters);
        \assert($apiResponse instanceof \stdClass);
        return $this->transformer->transform($apiResponse->data);
    }
}
