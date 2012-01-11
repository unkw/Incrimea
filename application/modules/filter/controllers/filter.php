<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filter extends MX_Controller {
     
    private $module_name;
    var $pager = FALSE;
    var $allow_types = array('objects', 'events', 'articles');
    
    function __construct()
    {
        // Имя модуля
        $this->module_name = strtolower(get_class());

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;
    }

    /** Главная страницы фильтров */
    function action_index()
    {
        // Все GET параметры проверенные на XSS
        $params = $this->input->get(NULL, TRUE);

        // Проверка на GET параметров на валидность
        if (!$this->check_params($params))
            redirect('filter');

        $this->theme->setVar('title', 'Incrimea');

        /** Форма фильтров */
        $this->theme->setVar('filters', $this->filter->form($params));

        /** Содержимое контента */
        $data = array(
            'objects' => false,
            'articles' => false,
            'events' => false,
        );

        /** Показывать только один из типов контента */
        if (isset($params['type']))
        {
            $this->load->library('pagination');
            switch ($params['type']) {
                case 'objects': $data['objects'] = $this->get_objects($params, TRUE); break;
                case 'events': $data['events'] = $this->get_events($params, TRUE); break;
                case 'articles': $data['articles'] = $this->get_articles($params, TRUE); break;
            }
        }
        /** Все типы контента */
        else
        {
            $data['objects'] = $this->get_objects($params);
            $data['articles'] = $this->get_articles($params);
            $data['events'] = $this->get_events($params);
        }
        
        /** Пагинация */
        $data['pager'] = $this->pager;

        $this->theme->setVar('content', $this->load->view($this->module_name.'/template.php', $data, TRUE));
    }

    /** Получить список отелей */
    function get_objects($params, $pagination = FALSE)
    {
        $config = array();
        // Кол-во отелей на страницу
        $config['per_page'] = $pagination ? 3 : 3;
        // Номер текущей страницы
        $page = (int)$this->input->get('page', TRUE) ? (int)$this->input->get('page', TRUE) : 1;
        // Позиция для БД
        $offset = ($page - 1) * $config['per_page'];

        /** Прикручиваем пагинацию, если необходимо */
        if ($pagination)
        {
            // Адресная строка
            $config['base_url'] = base_url().$this->module_name.'?'.$this->arr_to_string($params);
            // Кол-во отелей всего
            $config['total_rows'] = $this->model->count_all_objects($params);
            // Ссылка на первую страницу
            $config['first_url'] = base_url().$this->module_name.'?'.$this->arr_to_string($params);
            // Сегмент номера страницы в адресной строке
            $config['uri_segment'] = 2;

            $this->pagination->initialize($config);

            if ($offset > $config['total_rows'])
                redirect('filter');

            // Пейджер
            $this->pager = $this->pagination->create_links();
        }

        return $this->model->get_objects($params, $config['per_page'], $offset);
    }

    /** Получить список статей */
    function get_articles($params, $pagination = FALSE)
    {
        $config = array();
        // Кол-во отелей на страницу
        $config['per_page'] = $pagination ? 3 : 3;
        // Номер текущей страницы
        $page = (int)$this->input->get('page', TRUE) ? (int)$this->input->get('page', TRUE) : 1;
        // Позиция для БД
        $offset = ($page - 1) * $config['per_page'];

        /** Прикручиваем пагинацию, если необходимо */
        if ($pagination)
        {
            // Адресная строка
            $config['base_url'] = base_url().$this->module_name.'?'.$this->arr_to_string($params);
            // Кол-во отелей всего
            $config['total_rows'] = $this->model->count_all_articles($params);
            // Ссылка на первую страницу
            $config['first_url'] = base_url().$this->module_name.'?'.$this->arr_to_string($params);
            // Сегмент номера страницы в адресной строке
            $config['uri_segment'] = 2;

            $this->pagination->initialize($config);

            if ($offset > $config['total_rows'])
                redirect('filter');

            // Пейджер
            $this->pager = $this->pagination->create_links();
        }

        return $this->model->get_articles($params, $config['per_page'], $offset);
    }

    /** Получить список статей */
    function get_events($params, $pagination = FALSE)
    {
        $config = array();
        // Кол-во отелей на страницу
        $config['per_page'] = $pagination ? 3 : 3;
        // Номер текущей страницы
        $page = (int)$this->input->get('page', TRUE) ? (int)$this->input->get('page', TRUE) : 1;
        // Позиция для БД
        $offset = ($page - 1) * $config['per_page'];

        /** Прикручиваем пагинацию, если необходимо */
        if ($pagination)
        {
            // Адресная строка
            $config['base_url'] = base_url().$this->module_name.'?'.$this->arr_to_string($params);
            // Кол-во отелей всего
            $config['total_rows'] = $this->model->count_all_events($params);
            // Ссылка на первую страницу
            $config['first_url'] = base_url().$this->module_name.'?'.$this->arr_to_string($params);
            // Сегмент номера страницы в адресной строке
            $config['uri_segment'] = 2;

            $this->pagination->initialize($config);

            if ($offset > $config['total_rows'])
                redirect('filter');

            // Пейджер
            $this->pager = $this->pagination->create_links();
        }

        return $this->model->get_events($params, $config['per_page'], $offset);
    }

    /** Форма фильтров */
    function form($params = array())
    {
        $this->load->helper('form');

        $data = array();

        $params['resorts'] = isset($params['resorts']) ? explode(',', $params['resorts']) : array();
        $params['room'] = isset($params['room']) ? explode(',', $params['room']) : array();
        $params['infr'] = isset($params['infr']) ? explode(',', $params['infr']) : array();
        $params['service'] = isset($params['service']) ? explode(',', $params['service']) : array();
        $params['entment'] = isset($params['entment']) ? explode(',', $params['entment']) : array();
        $params['child'] = isset($params['child']) ? explode(',', $params['child']) : array();
        $params['beachs'] = isset($params['beachs']) ? explode(',', $params['beachs']) : array();

        $data['resorts'] = $this->model->get_resorts();

        $data['params'] = array_merge(array(
            'type' => false,
            'resorts' => array(),
            'room' => array(),
            'infr' => array(),
            'service' => array(),
            'entment' => array(),
            'child' => array(),
            'beachs' => array(),
        ), $params);

        switch ($data['params']['type'])
        {
            case 'objects':
                $data['room'] = $this->model->get_field('room');
                $data['infrastructure'] = $this->model->get_field('infrastructure');
                $data['service'] = $this->model->get_field('service');
                $data['entment'] = $this->model->get_field('entertainment');
                $data['child'] = $this->model->get_field('for_children');
                $data['beachs'] = $this->model->get_field('beachs');
                break;
        }

        return $this->load->view($this->module_name . '/form.php', $data, TRUE);
    }

    /** Проверка валидности параметров фильтра */
    function check_params($params)
    {
        if (!isset($params['type']) || in_array($params['type'], $this->allow_types))
            return TRUE;
        else
            return FALSE;
    }

    /** Преобразование массива в GET строку */
    function arr_to_string($arr, $sep_in = '=', $sep_out = '&')
    {
        $temp = array();

        foreach ($arr as $key => $value)
        {
            if ($key != 'page')
                $temp[] = $key.$sep_in.$value;
        }

        return implode($sep_out, $temp);
    }

}
