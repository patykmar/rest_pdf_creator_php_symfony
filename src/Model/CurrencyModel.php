<?php

namespace App\Model;

use App\Model\DataClass\Currency;
use InvalidArgumentException;

class CurrencyModel
{
    /**
     * @var Currency[]
     */
    private array $currency = array();

    public function __construct()
    {
        array_push($this->currency, 840, new Currency(840, "USD", "United States dollar", "$", "¢"));
        array_push($this->currency, 978, new Currency(978, "EUR", "Euro", "€", "c"));
        array_push($this->currency, 392, new Currency(392, "JPY", "Japanese yen", "¥", ""));
        array_push($this->currency, 826, new Currency(826, "GBP", "Pound sterling", "£", "p"));
        array_push($this->currency, 752, new Currency(752, "SEK", "Swedish krona", "kr", "öre"));
        array_push($this->currency, 578, new Currency(578, "NOK", "Norwegian krone", "kr", "øre"));
        array_push($this->currency, 985, new Currency(985, "PLN", "Polish złoty", "zł", "gr"));
        array_push($this->currency, 203, new Currency(203, "CZK", "Czech koruna", "Kč", "h"));
    }


    public function getCurrencyByNumericCode(int $numericCode): Currency
    {
        return $this->currency[$numericCode];
    }

    public function getCurrencyByCode(string $code): Currency
    {
        foreach ($this->currency as $currency) {
            if ($currency->getCode() === $code) {
                return $this->currency[$currency->getNumericCode()];
            }
        }
        throw new InvalidArgumentException(printf("Currency with code %s doesn't match", $code));
    }

}
