<?php


namespace App\Components;


/**
 * Class DateHelpers
 * @package App\Components
 */
class DateHelpers
{


    /**
     * @param $date
     * @return false|string
     */
    public function formatDate($date)
    {

        return date("d.m.Y H:i:s", strtotime($date));

    }

}
