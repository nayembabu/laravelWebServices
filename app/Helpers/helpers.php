<?php

    use Illuminate\Support\Facades\DB;

    if (!function_exists('number_format_bd')) {
        function number_format_bd($number) {
            $number = (string) $number;
            if (strpos($number, '.') !== false) {
                list($int, $dec) = explode('.', $number);
            } else {
                $int = $number;
                $dec = '';
            }
            $lastThree = substr($int, -3);
            $restUnits = substr($int, 0, -3);
            if ($restUnits != '') {
                $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
                $formatted = $restUnits . ',' . $lastThree;
            } else {
                $formatted = $lastThree;
            }
            if ($dec != '') {
                $formatted .= '.' . $dec;
            }
            return $formatted;
        }
    }

    if (!function_exists('en_to_bn_number')) {
        function en_to_bn_number($number)
        {
            $en = ['0','1','2','3','4','5','6','7','8','9','.'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯','.'];
            return str_replace($en, $bn, $number);
        }
    }

    if (!function_exists('en_to_bn_month')) {
        function en_to_bn_month($month)
        {
            $months = [
                'January'   => 'জানুয়ারি',
                'February'  => 'ফেব্রুয়ারি',
                'March'     => 'মার্চ',
                'April'     => 'এপ্রিল',
                'May'       => 'মে',
                'June'      => 'জুন',
                'July'      => 'জুলাই',
                'August'    => 'আগস্ট',
                'September' => 'সেপ্টেম্বর',
                'October'   => 'অক্টোবর',
                'November'  => 'নভেম্বর',
                'December'  => 'ডিসেম্বর',
            ];
            return $months[$month] ?? $month;
        }
    }

    function setting($key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = DB::table('settings')
                ->pluck('value','key')
                ->toArray();
        }

        return $settings[$key] ?? $default;
    }


    