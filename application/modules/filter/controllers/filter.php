<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filter extends MX_Controller {
     
    // Имя модуля
    private $module_name;
    // Пейджер
    var $pager = FALSE;
    // Допустимые типы контента
    var $allow_types = array('objects', 'events', 'articles');
    // Параметры GET запроса
    private $params = NULL;
    
    function __construct()
    {
        // Имя модуля
        $this->module_name = strtolower(get_class());

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;

        // Все GET параметры проверенные на XSS
        $this->params = $this->input->get(NULL, TRUE);
    }

    /** Главная страницы фильтров */
    function action_index()
    {
        // Проверка на GET параметров на валидность
        if (!$this->check_params())
            redirect('filter');

        /** Шаблон контента по-умолчанию */
        $tpl = 'main.php';

        /** Заголовок */
        $this->theme->setVar('title', 'Отдых в Крыму');
        /** Содержимое контента */
        $data = array(
            'objects' => false,
            'articles' => false,
            'events' => false,
        );
        // Показывать только один из типов контента
        if (isset($this->params['type']))
        {
            $this->load->library('pagination');
            switch ($this->params['type'])
            {
                case 'objects':
                    $data['objects'] = $this->get_content_by_type('objects', TRUE);
                    $tpl = 'objects.php';
                    break;
                case 'events':
                    $data['events'] = $this->get_content_by_type('events', TRUE);
                    $tpl = 'events.php';
                    break;
                case 'articles': 
                    $data['articles'] = $this->get_content_by_type('articles', TRUE);
                    $tpl = 'articles.php';
                    break;
            }
        }
        // Показать все типы контента вместе
        else
        {
            $data['objects'] = $this->get_content_by_type('objects');
            $data['articles'] = $this->get_content_by_type('articles');
        }
        /** Пейджер */
        $data['pager'] = $this->pager;
        /** Контент */
        $this->theme->setVar('content', $this->load->view($this->module_name.'/'.$tpl, $data, TRUE));
    }


    /** Получить список заданного контента в зависимости от типа (отели, статьи или события) */
    function get_content_by_type($type, $pagination = FALSE, $per_page = 5)
    {
        $config = array();
        // Кол-во отелей на страницу
        $config['per_page'] = $per_page;
        // Номер текущей страницы
        $page = (int)$this->input->get('page', TRUE) ? (int)$this->input->get('page', TRUE) : 1;
        // Позиция для БД
        $offset = ($page - 1) * $config['per_page'];

        /** Прикручиваем пагинацию, если необходимо */
        if ($pagination)
        {
            // Адресная строка
            $config['base_url'] = base_url().$this->module_name.'?'.$this->url_params_to_string();
            // Кол-во отелей всего
            $method = 'count_all_'.$type;
            $config['total_rows'] = $this->model->$method($this->params);
            // Ссылка на первую страницу
            $config['first_url'] = base_url().$this->module_name.'?'.$this->url_params_to_string();
            // Сегмент номера страницы в адресной строке
            $config['uri_segment'] = 2;

            $this->pagination->initialize($config);

            if ($offset > $config['total_rows'])
                redirect('filter');

            // Пейджер
            $this->pager = $this->pagination->create_links();
        }

        $method = 'get_'.$type;

        return $this->model->$method($this->params, $config['per_page'], $offset);
    }
    

    /** Форма фильтров */
    public function form()
    {
        $this->load->helper('form');

        $data = array();

        // Места отдыха
        $data['resorts'] = $this->model->get_resorts();

        // Параметры для всех типов контента
        $data['params'] = array(
            'type' => isset($this->params['type']) ? $this->params['type'] : false,
            'resorts' => isset($this->params['resorts']) ? explode(',', $this->params['resorts']) : array(),
        );

        switch ($data['params']['type'])
        {
            case 'objects':

                $data['room'] = $this->model->get_field('room');
                $data['infrastructure'] = $this->model->get_field('infrastructure');
                $data['service'] = $this->model->get_field('service');
                $data['entment'] = $this->model->get_field('entertainment');
                $data['child'] = $this->model->get_field('for_children');
                $data['beachs'] = $this->model->get_field('beachs');

                $data['params'] = array_merge($data['params'], $this->obj_form_params());
                break;
        }

        return $this->load->view($this->module_name . '/form.php', $data, TRUE);
    }

    /** Параметры GET запроса для отелей */
    private function obj_form_params()
    {
        return array(
            'room' => isset($this->params['room']) ? explode(',', $this->params['room']) : array(),
            'infr' => isset($this->params['infr']) ? explode(',', $this->params['infr']) : array(),
            'service' => isset($this->params['service']) ? explode(',', $this->params['service']) : array(),
            'entment' => isset($this->params['entment']) ? explode(',', $this->params['entment']) : array(),
            'child' => isset($this->params['child']) ? explode(',', $this->params['child']) : array(),
            'beachs' => isset($this->params['beachs']) ? explode(',', $this->params['beachs']) : array(),
            'distance' => isset($this->params['distance']) ? $this->params['distance'] : '',
            'price_min' => isset($this->params['p-min']) ? $this->params['p-min'] : '',
            'price_max' => isset($this->params['p-max']) ? $this->params['p-max'] : '',
        );
    }

    /** Проверка валидности параметров фильтра */
    function check_params()
    {
        if (!isset($this->params['type']) || in_array($this->params['type'], $this->allow_types))
            return TRUE;
        else
            return FALSE;
    }

    /** Преобразование массива в GET строку */
    function url_params_to_string($sep_in = '=', $sep_out = '&')
    {
        $temp = array();

        foreach ($this->params as $key => $value)
        {
            if ($key != 'page')
                $temp[] = $key.$sep_in.$value;
        }

        return implode($sep_out, $temp);
    }

}
