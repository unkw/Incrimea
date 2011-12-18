<?php

class Theme extends CI_Model {

    var $vars = array();
    var $tpl;
    var $breadcrumb = array();
    var $body_classes = array();

    function __construct()
    {
        $this->vars = array();
        $this->init_variables();
    }

    /** Инициализация переменных шаблона */
    function init_variables()
    {
        // Путь к шаблону по умолчанию
        $this->tpl = 'theme/frontend.php';
        // Флаг для главной страницы
        $this->vars['is_front'] = FALSE;
        // Глобальные классы для html тега body
        $this->vars['body_classes'] = array();
        // "Хлебные крошки"
        $this->vars['breadcrumb'] = array();
    }

    /** Назначить переменную в шаблоне */
    function setVar($label, $val = FALSE)
    {
        if (is_array($label))
        {
            foreach ($label as $l => $v)
            {
                $this->vars[$l] = $v;
            }
        }
        else
            $this->vars[$label] = $val;
    }

    /** Назначить "хлебную крошку" */
    function set_breadcrumb($title, $link)
    {
        $this->breadcrumb[] = array($title, $link);
    }

    /** Html представление "хлебных крошек" */
    function get_breadcrumb_html()
    {
        $breadcrumb = array();

        $end_element = array_pop($this->breadcrumb);

        foreach ($this->breadcrumb as $b) {
            
            $breadcrumb[] = '<a href="'.  base_url() . $b[1] . '">' . $b[0] . '</a>';
        }

        $breadcrumb[] = $end_element[0];

        return implode(' > ', $breadcrumb);
    }

    /** Отображение шаблона */
    function render()
    {
        if ($this->vars['is_front'])
            $this->body_classes[] = 'frontpage';

        $this->vars['body_classes'] = implode(' ', $this->body_classes);

        $this->vars['breadcrumb'] = $this->get_breadcrumb_html();

        $this->load->view($this->tpl, $this->vars);

        // Debug information
        $this->output->enable_profiler(TRUE);
    }
}
