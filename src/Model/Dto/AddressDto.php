<?php

namespace App\Model\Dto;

class AddressDto
{
    private string $country;
    private string $street;
    private string $city;
    private string $zipCode;

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $country
     * @return AddressDto
     */
    public function setCountry(string $country): AddressDto
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $street
     * @return AddressDto
     */
    public function setStreet(string $street): AddressDto
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param string $city
     * @return AddressDto
     */
    public function setCity(string $city): AddressDto
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $zipCode
     * @return AddressDto
     */
    public function setZipCode(string $zipCode): AddressDto
    {
        $this->zipCode = $zipCode;
        return $this;
    }

}
