<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\ValueObjects;

use DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException;
class Address
{
    protected string $addressLine1;
    protected string $countryCode;
    protected string $postalCode;
    protected string $cityName;
    protected string $addressLine2 = '';
    protected string $addressLine3 = '';
    protected string $countyName = '';
    protected string $provinceCode = '';
    /**
     * @throws InvalidAddressException
     */
    public function __construct(string $addressLine1, string $countryCode, string $postalCode, string $cityName, string $addressLine2 = '', string $addressLine3 = '', string $countyName = '', string $provinceCode = '')
    {
        $this->provinceCode = $provinceCode;
        $this->countyName = $countyName;
        $this->addressLine3 = $addressLine3;
        $this->addressLine2 = $addressLine2;
        $this->cityName = $cityName;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
        $this->addressLine1 = $addressLine1;
        $this->countryCode = \strtoupper($this->countryCode);
        $this->validateData();
    }
    /**
     * @return string
     */
    public function getAddressLine1() : string
    {
        return $this->addressLine1;
    }
    /**
     * @return string
     */
    public function getAddressLine2() : string
    {
        return $this->addressLine2;
    }
    /**
     * @return string
     */
    public function getAddressLine3() : string
    {
        return $this->addressLine3;
    }
    /**
     * @return string
     */
    public function getCountyName() : string
    {
        return $this->countyName;
    }
    /**
     * @return string
     */
    public function getProvinceCode() : string
    {
        return $this->provinceCode;
    }
    public function getCountryCode() : string
    {
        return $this->countryCode;
    }
    public function getPostalCode() : string
    {
        return $this->postalCode;
    }
    public function getCityName() : string
    {
        return $this->cityName;
    }
    public function getAsArray() : array
    {
        $result = ['addressLine1' => $this->addressLine1, 'countryCode' => $this->countryCode, 'postalCode' => $this->postalCode, 'cityName' => $this->cityName];
        if ($this->addressLine2 !== '') {
            $result['addressLine2'] = $this->addressLine2;
        }
        if ($this->addressLine3 !== '') {
            $result['addressLine3'] = $this->addressLine3;
        }
        if ($this->countyName !== '') {
            $result['countyName'] = $this->countyName;
        }
        if ($this->provinceCode !== '') {
            $result['provinceCode'] = $this->provinceCode;
        }
        return $result;
    }
    /**
     * @throws InvalidAddressException
     */
    protected function validateData() : void
    {
        if (\strlen($this->countryCode) !== 2) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException("Country Code must be 2 characters long. Entered: {$this->countryCode}");
        }
        if (\strlen($this->addressLine1) === 0) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException("Address Line1 must not be empty.");
        }
        if (\strlen($this->cityName) === 0) {
            throw new \DhlVendor\Octolize\DhlExpress\RestApi\Exceptions\InvalidAddressException("City name must not be empty.");
        }
    }
}
