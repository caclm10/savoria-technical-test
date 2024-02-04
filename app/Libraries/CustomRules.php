<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;

class CustomRules
{
    public function after_date(mixed $value, string $params, array $data, ?string &$error = null): bool
    {
        if (!$params) return true;

        $minDate = Time::parse($params);

        $inputDate = Time::parse($value);

        $isAfter = $inputDate->isAfter($minDate);

        if (!$isAfter) {
            $error = lang("Validation.after_date", ["date" => $minDate->toLocalizedString("dd MMMM YYYY")]);

            return false;
        }

        return true;
    }

    public function minimum_age(mixed $value, string $params, array $data, ?string &$error = null): bool
    {
        if (!$params) {
            $params = 0;
        }

        $minAge = abs((int) $params);

        $age = Time::parse($value)->difference(Time::now())->getYears(true);

        if ($minAge === 0 && $age > 0) {
            $error = lang("Validation.before_today");

            return false;
        }

        $age = abs((int) $age);

        if ($age < $minAge) {
            $error = lang("Validation.minimum_age", ["age" => $minAge]);

            return false;
        }

        return true;
    }
}
