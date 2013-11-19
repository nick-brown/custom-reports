<?php


class Helpers {

    /**
     * Gets an array of dates of every sunday between two dates
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     *
     * @return array
     */
    public static function get_sundays_between(DateTime $startDate, DateTime $endDate)
    {
        $startDate = $startDate->getTimestamp(); 
        $endDate = $endDate->getTimestamp();

        // Sundays
        $weekdayNumber = 0;

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

        return $dateArr;
    }
}
