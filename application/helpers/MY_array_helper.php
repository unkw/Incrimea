<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('escape') ) 
{
    /**
     * Эскейпирование элементов массива
     */
    function escape($val)
    {
        $CI = & get_instance();
        
        return $CI->db->escape($val);
    }

}
