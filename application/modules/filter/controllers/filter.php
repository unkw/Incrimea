<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filter extends MX_Controller {
     
    private $module_name;
    
    function __construct()
    {
        // Имя модуля
        $this->module_name = strtolower(get_class());

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;
    }

    function action_index()
    {
        $this->theme->setVar('title', 'Incrimea');

        // Форма фильтров
        $this->theme->setVar('filters', $this->filter->form());

        $this->theme->setVar('content', $this->load->view($this->module_name.'/template.php', array(), TRUE));
    }

    function form()
    {
        $this->load->helper('form');

        $data = array();

        $data['resorts'] = $this->model->get_resorts();

        return $this->load->view($this->module_name . '/form.php', $data, TRUE);
    }

}
