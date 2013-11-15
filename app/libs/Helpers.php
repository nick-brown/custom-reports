<?php


class Helpers {

    /**
     * Gets an array of dates of every sunday between two dates
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $weekdayNumber
     *
     * @return array
     */
    public static function get_sundays_between(DateTime $startDate, DateTime $endDate, $weekdayNumber = 0)
    {
        $startDate = $startDate->getTimestamp(); 
        $endDate = $endDate->getTimestamp();

        $dateArr = array();

        do
        {
            if(date("w", $startDate) != $weekdayNumber)
            {
                $startDate += (24 * 3600); // add 1 day
            }
        } while(date("w", $startDate) != $weekdayNumber);


        while($startDate <= $endDate)
        {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return($dateArr);
    }
}
