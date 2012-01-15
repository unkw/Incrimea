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
        // Заголовок
        $this->vars['head_title'] = NULL;
        // Метатеги
        $this->vars['metatags'] = NULL;
        // Флаг для главной страницы
        $this->vars['is_front'] = FALSE;
        // Глобальные классы для html тега body
        $this->vars['body_classes'] = array();
        // Фильтры
        $this->vars['filters'] = FALSE;
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

    /** Получить метатеги */
    function get_metatags_html()
    {
        if (is_null($this->vars['metatags']))
        {
            $this->setVar('metatags', $this->metatags->get_by_path($this->uri->uri_string()));
        }

        $meta = $this->vars['metatags'];

        if (isset($meta['title']) && trim($meta['title']))
            $this->setVar('head_title', trim($meta['title']));
        else
            $this->setVar('head_title', $this->vars['title']);

        $this->theme->setVar('metatags', $this->metatags->html($meta));

        return $this->vars['metatags'];
    }

    function set_metatags($meta_id)
    {
        $this->setVar('metatags', $this->metatags->get($meta_id));
    }

    /** Отображение шаблона */
    function render()
    {
        if ($this->vars['is_front'])
            $this->body_classes[] = 'frontpage';

        // Метатеги
        $this->vars['metatags'] = $this->get_metatags_html();

        // Глобальные классы добавляемые в тег body
        $this->vars['body_classes'] = implode(' ', $this->body_classes);

        // Хлебные крошки
        $this->vars['breadcrumb'] = $this->get_breadcrumb_html();

        // Отображение
        $this->load->view($this->tpl, $this->vars);

        // Debug information
        $this->output->enable_profiler(TRUE);
    }
}
