<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('href'))
{
    /**
     * Функция создания html ссылки с $_GET параметрами формы фильтров
     * текущей страницы
     * @param string $href - адрес ссылки
     * @param string $text - текст ссылки
     * @param array $data - дополнительные атрибуты ссылки
     * @return string
     */
    function href($href, $text, $data = array())
    {
        $CI =& get_instance();
        $get = $CI->input->get(NULL, TRUE);

        if ( ! $CI->theme->filters_qs && !empty($get) )
        {
            $tmp = array();
            foreach ($get as $k => $v)
                if ($k != 'page')
                    $tmp[] = $k.'='.$v;

            $CI->theme->filters_qs = implode('&', $tmp);
        }
        
        $href .= $CI->theme->filters_qs ? '?' . $CI->theme->filters_qs : '';

        $id = isset($data['id']) ? ' id="'.$data['id'].'"' : '';
        $class = isset($data['class']) ? ' class="'.$data['class'].'"' : '';
        $title = isset($data['title']) ? ' title="'.$data['title'].'"' : '';

        return '<a href="'.base_url().$href.'"'.$id.$class.$title.'>'.$text.'</a>';
    }
}
