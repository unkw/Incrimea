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

if ( ! function_exists('getVar') ) 
{
    /**
     * Получение параметра из массивов POST или GET
     * @param string $name - имя параметра
     * @param mixed $default - значение, если параметр отсутствует
     * @param boolean $without_xss_clean - флаг, производить ли xss фильтрацию
     * @return mixed 
     */
    function getVar($name, $default = FALSE, $xss_clean = TRUE)
    {
        $CI = & get_instance();
        
        $param = $CI->input->get_post($name, $xss_clean);

        return $param ? $param : $default;
    }

}
