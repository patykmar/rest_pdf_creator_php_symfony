<?php

namespace App\Validate;

use App\Exceptions\ClientException;

class NumberValidate
{
    public function integerValidate(mixed $input): int
    {
        if (filter_var($input, FILTER_VALIDATE_INT)) {
            return $input;
        }
        throw new ClientException("Input parameter is not validate integer");
    }

    public function integerValidatePositive(mixed $input): int
    {
        if (filter_var($input, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            return $input;
        }
        throw new ClientException("Input parameter is not positive integer");
    }

    public function integerValidatePositiveIncludeZero(mixed $input): int
    {
        if (filter_var($input, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]])) {
            return $input;
        }
        throw new ClientException("Input parameter is not positive integer or zero");
    }
}
