<?php
if (! function_exists('convertFloatToInteger')) {
    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return integer
     */
    function convertFloatToInteger($value) {
        return $value * 100;
    }
}
if (! function_exists('convertIntegerToFloat')) {
    /**
     * Undocumented function
     *
     * @param [type] $value
     * @return float
     */
    function convertIntegerToFloat($value) {
        return round($value / 100, 2);
    }
}
if (! function_exists('statisticsItem')) {

    /**
     * Set item body for statistic record
     *
     * @param string $name
     * @param string $title
     * @param integer $value
     * @return array
     */
    function setStatisticsItem(string $name, string $title, int $value) {
        return [
            'name' => $name,
            'title' => $title,
            'value' => $value
        ];
    }
}
