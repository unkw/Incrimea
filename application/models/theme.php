<?php

class Theme extends CI_Model {

    /** Переменные используемые в шаблоне tpl файла */
    var $vars = array();
    /** Html шаблон страницы */
    var $tpl;
    /** Шаблон блока */
    var $block_tpl;
    /** Хлебные крошки */
    var $breadcrumb = array();
    /** Глобальные css классы для тега body */
    var $body_classes = array();
    /** По-умолчанию метатеги включены */
    var $metatags_disable = FALSE;
    /** Строка GET */
    var $query_string = null;
    /** Регионы */
    var $regions = array(
        'left' => array(),
    );
    /** Блоки */
    var $blocks = array(
        'filters' => array(
            'region' => 'left',
            'title'  => 'Фильтры',
            'enable' => true,
        ),
        'login' => array(
            'region' => 'left',
            'title'  => 'Вход на сайт',
            'enable' => true,
        ),
    );

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
        // Шаблон блока
        $this->block_tpl = 'theme/block.php';
        // Название сайта
        $this->vars['site_name'] = 'Incrimea';
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
        // Включены ли метатеги
        if ($this->metatags_disable)
            return FALSE;

        // Если метатеги не назначены, получить метатеги по текущему адресу url
        if (is_null($this->vars['metatags']))
        {
            $path = $this->uri->uri_string();
            // Метатеги главной страницы
            if ($path == '')
                $path = 'index';

            $this->setVar('metatags', $this->metatags->get_by_path($path));
        }

        $meta = $this->vars['metatags'];

        // Отображение мета заголовка
        if (isset($meta['title']) && trim($meta['title']))
            $this->setVar('head_title', trim($meta['title']).' | '.$this->vars['site_name']);
        else
            $this->setVar('head_title', $this->vars['title'].' | '.$this->vars['site_name']);

        return $this->metatags->html($meta);
    }

    /** Задать метатеги */
    function set_metatags($meta_id)
    {
        $this->setVar('metatags', $this->metatags->get($meta_id));
    }

    /**
     * Включить/выключить отображение блока
     * @param string $name - Имя блока
     * @param boolean $switcher - Переключатель (TRUE/FALSE)
     */
    function block($name, $switcher, $region = FALSE)
    {
        if ( ! isset($this->blocks[$name]) )
            return FALSE;

        $this->blocks[$name]['enable'] = (bool) $switcher;
    }

    /** Добавить блок в регион */
    function add_block($name, $data, $html)
    {
        $this->regions[$data['region']][] = array(
            'name' => $name,
            'title'=> $data['title'],
            'html' => $html,
        );
    }

    /** Генерация регионов для блоков ($left, $content_top, etc.) */
    function generate_regions()
    {
        // Вставляем блоки в регионы
        foreach ($this->blocks as $name => $data)
        {
            if ( $data['enable'] === TRUE && isset($this->regions[$data['region']]) )
            {
                switch ($name)
                {
                    // Блок "Фильтров"
                    case 'filters':
                        $this->common->load_module('filter');
                        $this->add_block($name, $data, $this->filter->form());
                        break;
                    case 'login': $this->add_block($name, $data, 'Тестовый блок'); break;
                }
            }
        }

        // Генерируем html регионов и блоков
        foreach ($this->regions as $region => $blocks)
        {
            $html = array();
            foreach ($blocks as $b)
                $html[] = $this->load->view($this->block_tpl, $b, TRUE);

            $this->setVar($region, implode('', $html));
        }
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

        // Регионы
        $this->generate_regions();

        // Отображение
        $this->load->view($this->tpl, $this->vars);

        // Debug information
        $this->output->enable_profiler(TRUE);
    }
}
