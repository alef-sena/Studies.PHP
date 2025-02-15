<?php

namespace App\Utils;

class Validator
{
    public static function validateString($value, $fieldName)
    {
        if (!is_string($value)) {
            throw new \Exception("The field '$fieldName' must be a string.", 400);
        }
    }

    public static function validateDecimal($value, $fieldName)
    {
        if (is_string($value)) {
            if (!is_numeric($value)) {
                throw new \Exception("The field '$fieldName' must be a number.", 400);
            }
        } elseif (!is_numeric($value)) {
            throw new \Exception("The field '$fieldName' must be a number.", 400);
        }
    
        // Verifica se o número tem no máximo 10 dígitos no total e 2 casas decimais
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/', $value)) {
            throw new \Exception("The field '$fieldName' must be a valid decimal with up to 10 digits and 2 decimal places.", 400);
        }
    }

    public static function validateInteger($value, $fieldName)
    {
        if (!is_int($value)) {
            throw new \Exception("The field '$fieldName' must be an integer.", 400);
        }
    }
}