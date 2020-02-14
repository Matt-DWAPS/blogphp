<?php

class Validator
{
    static public function isEmpty($value)
    {
        if (empty($value)) {
            return true;
        }
        return false;
    }

    static public function isNotIdentic($value1, $value2)
    {
        if ($value1 !== $value2) {
            return true;
        }
        return false;
    }

    static function isNotAnEmail($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    static function isToUpper($value, $number)
    {
        if (strlen($value) > $number) {
            return true;
        }
        return false;
    }
}