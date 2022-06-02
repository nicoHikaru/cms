<?php
namespace App\StaticData;


class DateAndTime
{
    public static function now():string
    {
        date_default_timezone_set("Europe/Paris");
        $date = date("Y-m-d H:i:s");
        return $date;

    }
}