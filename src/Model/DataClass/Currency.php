<?php

namespace App\Model\DataClass;

class Currency
{
    private int $numericCode;
    private string $code;
    private string $displayName;
    private string $symbol;
    private string $hundredthSymbol;

    /**
     * @param int $numericCode
     * @param string $code
     * @param string $displayName
     * @param string $symbol
     * @param string $hundredthSymbol
     */
    public function __construct(
        int    $numericCode,
        string $code,
        string $displayName,
        string $symbol,
        string $hundredthSymbol
    )
    {
        $this->numericCode = $numericCode;
        $this->code = $code;
        $this->displayName = $displayName;
        $this->symbol = $symbol;
        $this->hundredthSymbol = $hundredthSymbol;
    }

    /**
     * @return int
     */
    public function getNumericCode(): int
    {
        return $this->numericCode;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getHundredthSymbol(): string
    {
        return $this->hundredthSymbol;
    }

}
