<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filter extends MX_Controller {
     
    // Имя модуля
    public $module_name;
    // Допустимые типы контента
    public $allow_types = array('objects', 'events', 'articles');
    // Параметры GET запроса
    public $qs = NULL;
    
    function __construct()
    {
        // Имя модуля
        $this->module_name = strtolower(get_class());

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;

        // Все GET параметры + проверенные на XSS
        $this->qs = $this->input->get(NULL, TRUE);
        $this->qs = $this->qs ? $this->qs : array();

        // Подключение модуля "Object"
        $this->common->load_module('object');
    }

    /** Главная страница фильтров */
    function action_index()
    {
        // Проверка GET параметров на валидность
        if ( ! $this->check_qs() )
            redirect('filter');

        /** Шаблон контента по-умолчанию */
        $tpl = 'main.php';

        /** Заголовок */
        $this->theme->setVar('title', 'Отдых в Крыму');
        
        /** Содержимое контента */
        $data = array(
            'objects' => NULL,
            'articles' => NULL,
            'events' => NULL,
        );
        // Показывать только один из типов контента
        if (isset($this->qs['type']))
        {
            switch ($this->qs['type'])
            {
                case 'objects':
                    $data['objects'] = $this->model->get_content($this->qs, TRUE);
                    $tpl = 'objects.php';
                    break;
                case 'events':
                    $data['events'] = $this->model->get_content($this->qs, TRUE);
                    $tpl = 'events.php';
                    break;
                case 'articles': 
                    $data['articles'] = $this->model->get_content($this->qs, TRUE);
                    $tpl = 'articles.php';
                    break;
            }
        }
        // Показать все типы контента вместе, если не выбран ни один из них
        else
        {
            $data['objects'] = $this->model->get_content(array_merge($this->qs, array('type'=>'objects')));
            $data['articles'] = $this->model->get_content(array_merge($this->qs, array('type'=>'articles')));
        }
        /** Пейджер */
        $data['pager'] = $this->model->pager;
        /** Контент */
        $this->theme->setVar('content', $this->load->view($this->module_name.'/'.$tpl, $data, TRUE));
    }

    /** Форма фильтров */
    public function form()
    {
        $this->load->helper('form');

        $data = array();

        // Обработка $_GET данных
        $data['params'] = $this->get_form_params();
        
        // Места отдыха
        $data['resorts'] = $this->model->get_resorts($data['params']);        
        
        switch ($data['params']['type'])
        {
            case 'objects':
                $data = array_merge($data, $this->model->get_obj_form_params($data['params']));
                break;
        }
        
        return $this->load->view($this->module_name . '/form.php', $data, TRUE);
    }
    
    /**
     * Получить параметры GET запроса для формы фильтров
     * @return type 
     */
    public function get_form_params()
    {
        $data = array();
        
        $data['type'] = isset($this->qs['type']) ? $this->qs['type'] : FALSE;

        $data['resorts'] = isset($this->qs['resorts']) ? explode(',', $this->qs['resorts']) : array();
        
        switch ($data['type']) {
            case 'objects': 
                $data['room'] = isset($this->qs['room']) ? explode(',', $this->qs['room']) : array();
                $data['infr'] = isset($this->qs['infr']) ? explode(',', $this->qs['infr']) : array();
                $data['service'] = isset($this->qs['service']) ? explode(',', $this->qs['service']) : array();
                $data['entment'] = isset($this->qs['entment']) ? explode(',', $this->qs['entment']) : array();
                $data['child'] = isset($this->qs['child']) ? explode(',', $this->qs['child']) : array();
                $data['beachs'] = isset($this->qs['beachs']) ? explode(',', $this->qs['beachs']) : array();
                $data['distance'] = getVar('distance', '');
                $data['price_min'] = getVar('p-min', '');
                $data['price_max'] = getVar('p-max', '');
                break;
        }
        
        return $data;
    }
    
    /**
     * Проверка валидности $_GET параметров для формы фильтров
     * @return boolean 
     */
    function check_qs()
    {
        if (!isset($this->qs['type']) || in_array($this->qs['type'], $this->allow_types))
            return TRUE;
        else
            return FALSE;
    }

    ///////////////////////////
    ////////// AJAX ///////////
    ///////////////////////////
    
    /**
     * Получить данные параметров для обновления формы,
     * чтобы включить/выключить элементы формы
     */ 
    public function ajax_get_form()
    {   
        $params = $this->get_form_params();
        
        $data = array();
        $data['resorts'] = $this->model->get_resorts($params);
        switch ($params['type']) {
            case 'objects': $data = array_merge($data, $this->model->get_obj_form_params($params, TRUE)); break;
        }

        echo json_encode($data);
    }    
    

}
