<?php

namespace Intervention\Validation\Rules;

use Intervention\Validation\AbstractStringRule;

class Iban extends AbstractStringRule
{
    /**
     * IBAN lengths for countries
     *
     * @var array
     */
    private $lengths = [
        'AL' => 28,
        'AD' => 24,
        'AT' => 20,
        'AZ' => 28,
        'BH' => 22,
        'BE' => 16,
        'BA' => 20,
        'BR' => 29,
        'BG' => 22,
        'CR' => 22,
        'HR' => 21,
        'CY' => 28,
        'CZ' => 24,
        'DK' => 18,
        'DO' => 28,
        'TL' => 23,
        'EE' => 20,
        'FO' => 18,
        'FI' => 18,
        'FR' => 27,
        'GE' => 22,
        'DE' => 22,
        'GI' => 23,
        'GR' => 27,
        'GL' => 18,
        'GT' => 28,
        'HU' => 28,
        'IS' => 26,
        'IE' => 22,
        'IL' => 23,
        'IT' => 27,
        'JO' => 30,
        'KZ' => 20,
        'XK' => 20,
        'KW' => 30,
        'LV' => 21,
        'LB' => 28,
        'LI' => 21,
        'LT' => 20,
        'LU' => 20,
        'MA' => 28,
        'MK' => 19,
        'MT' => 31,
        'MR' => 27,
        'MU' => 30,
        'MC' => 27,
        'MD' => 24,
        'ME' => 22,
        'NL' => 18,
        'NO' => 15,
        'PK' => 24,
        'PS' => 29,
        'PL' => 28,
        'PT' => 25,
        'QA' => 29,
        'RO' => 24,
        'SM' => 27,
        'SA' => 24,
        'RS' => 22,
        'SK' => 24,
        'SI' => 19,
        'ES' => 24,
        'SE' => 24,
        'CH' => 21,
        'TN' => 24,
        'TR' => 26,
        'AE' => 23,
        'GB' => 22,
        'VG' => 24,
        'DZ' => 24,
        'AO' => 25,
        'BJ' => 28,
        'BF' => 27,
        'BI' => 16,
        'CM' => 27,
        'CV' => 25,
        'IR' => 26,
        'CI' => 28,
        'MG' => 27,
        'ML' => 28,
        'MZ' => 25,
        'SN' => 28,
        'UA' => 29,
        'EG' => 29,
    ];

    /**
     * Determine if current value is valid
     *
     * @return boolean
     */
    public function isValid(): bool
    {
        $iban = $this->getValue();

        // check iban length and checksum
        return $this->hasValidLength($iban) && $this->getChecksum($iban) === 1;
    }

    /**
     * Prepare given value
     *
     * @return string
     */
    public function getValue()
    {
        return str_replace(' ', '', strtoupper(parent::getValue()));
    }

    /**
     * Calculate checksum of iban
     *
     * @param  string $iban
     * @return int
     */
    private function getChecksum($iban)
    {
        $iban = substr($iban, 4) . substr($iban, 0, 4);
        $iban = str_replace(
            $this->getReplacementsChars(),
            $this->getReplacementsValues(),
            $iban
        );

        $checksum = intval(substr($iban, 0, 1));

        for ($strcounter = 1; $strcounter < strlen($iban); $strcounter++) {
            $checksum *= 10;
            $checksum += intval(substr($iban, $strcounter, 1));
            $checksum %= 97;
        }

        return $checksum; // only 1 is iban
    }

    /**
     * Returns the designated length of IBAN for given IBAN
     *
     * @param  string $iban
     * @return integer
     */
    private function getDesignatedIbanLength($iban)
    {
        $countrycode = substr($iban, 0, 2);

        return isset($this->lengths[$countrycode]) ? $this->lengths[$countrycode] : false;
    }

    /**
     * Determine if given iban has the proper length
     *
     * @param  string  $iban
     * @return boolean
     */
    private function hasValidLength($iban)
    {
        return $this->getDesignatedIbanLength($iban) == strlen($iban);
    }

    private function getReplacementsChars()
    {
        return range('A', 'Z');
    }

    private function getReplacementsValues()
    {
        $values = [];
        foreach (range(10, 35) as $value) {
            $values[] = strval($value);
        }

        return $values;
    }
}
