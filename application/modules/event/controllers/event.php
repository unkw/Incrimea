<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends MX_Controller {
     
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
        $data = $this->model->get($id);

        if ( $data['status'] == 0 && ! $this->auth->is_admin() ) 
            show_404();

        // Назначаем метатеги
        $this->theme->set_metatags($data['meta_id']);  

        $title = $data['title'];
        // Заголовок
        $this->theme->setVar('title', $title);
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/template.php', $data, TRUE));
    }
}
