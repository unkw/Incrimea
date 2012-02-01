<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('href'))
{
    function href($href, $text, $data = array())
    {
        $CI =& get_instance();
        $get = $CI->input->get(NULL, TRUE);

        if ( ! $CI->theme->query_string )
        {
            $tmp = array();
            foreach ($get as $k => $v)
                if ($k != 'page')
                    $tmp[] = $k.'='.$v;

            $CI->theme->query_string = implode('&', $tmp);
        }
        
        $href .= '?' . $CI->theme->query_string;

        $id = isset($data['id']) ? ' id="'.$data['id'].'"' : '';
        $class = isset($data['class']) ? ' class="'.$data['class'].'"' : '';
        $title = isset($data['title']) ? ' title="'.$data['title'].'"' : '';

        return '<a href="'.base_url().$href.'"'.$id.$class.$title.'>'.$text.'</a>';
    }
}
