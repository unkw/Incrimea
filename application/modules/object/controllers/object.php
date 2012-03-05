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

    public function action_view($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404();

        // Контент
        $data = array();

        // Основные данные
        $data = $this->model->get_obj($id);

        if (!$data)
            show_404();

        // Назначаем метатеги
        $this->theme->set_metatags($data['meta_id']);

        // Заголовок
        $this->theme->setVar('title', $data['title']);

        /** Дополнительные поля */
        $data['room'] = $this->model->value_from_additional_field('room', $data['room']);
        $data['infrastructure'] = $this->model->value_from_additional_field('infrastructure', $data['infrastructure']);
        $data['service'] = $this->model->value_from_additional_field('service', $data['service']);
        $data['entertainment'] = $this->model->value_from_additional_field('entertainment', $data['entertainment']);
        $data['for_children'] = $this->model->value_from_additional_field('for_children', $data['for_children']);

        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/template.php', $data, TRUE));
    }

    public function get_additional_fields()
    {
        return $this->model->get_addition_fields();
    }
}
