<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Object extends MX_Controller {
     
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

    function action_view($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404();

        // Контент
        $data = array();
        $m = $this->module_name;
        $data = $this->model->get_obj($id);

        if (!$data)
            show_404();

        $title = $data['title'];
        // Заголовок
        $this->theme->setVar('title', $title);
        /** Дополнительные поля */
        $data['room'] = $this->model->get_field('room', $data['room']);
        $data['infrastructure'] = $this->model->get_field('infrastructure', $data['infrastructure']);
        $data['service'] = $this->model->get_field('service', $data['service']);
        $data['entertainment'] = $this->model->get_field('entertainment', $data['entertainment']);
        $data['for_children'] = $this->model->get_field('for_children', $data['for_children']);

        // Отображение
        $this->theme->setVar('content', $this->load->view($m.'/template.php', $data, TRUE));
    }
}
