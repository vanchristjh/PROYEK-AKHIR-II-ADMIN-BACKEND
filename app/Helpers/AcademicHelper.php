<?php

if (!function_exists('getCurrentSemester')) {
    /**
     * Get the current semester based on the date
     * Semester Ganjil (Odd): July - December
     * Semester Genap (Even): January - June
     * 
     * @return string
     */
    function getCurrentSemester()
    {
        $month = (int) date('n'); // Current month (1-12)
        return ($month >= 7 && $month <= 12) ? 'Ganjil' : 'Genap';
    }
}

if (!function_exists('getCurrentAcademicYear')) {
    /**
     * Get the current academic year in format YYYY/YYYY
     * Academic year starts in July and ends in June of the following year
     * 
     * @return string
     */
    function getCurrentAcademicYear()
    {
        $year = (int) date('Y'); // Current year
        $month = (int) date('n'); // Current month (1-12)
        
        // If current month is January-June, academic year is (previous year)/(current year)
        if ($month >= 1 && $month <= 6) {
            return ($year - 1) . '/' . $year;
        }
        
        // If current month is July-December, academic year is (current year)/(next year)
        return $year . '/' . ($year + 1);
    }
}
