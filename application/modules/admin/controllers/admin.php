<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
     
    private $module_name;
    
    function __construct()
    {
        // Имя модуля
        $this->module_name = 'admin';

        // Хэлпер форм и библиотека валидации форм
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;

//        // Подгружаем конфиги модуля
//        $this->load->config();
    } 
    
    function action_index()
    {
        $module_content = $this->load->view($this->module_name.'/'.$this->module_name.'.php', FALSE, TRUE);
        $this->theme->setVar('title', 'Административная панель');
        $this->theme->setVar('content', $module_content);
        $this->theme->body_classes = array($this->module_name);
    }

    function action_site_settings()
    {
        $title = 'Настройки сайта';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, 'admin/admin/site_settings');
        // Заголовок
        $this->theme->setVar('title', $title);

        if (empty($_POST))
            $data['metatags'] = $this->metatags->html_form_fields_by_path('index');
        else {
            $data['metatags'] = $this->metatags->html_form_fields_by_path();

            if ( ! $this->metatags->update($this->input->post('edit-metaid'), 'index') )
                $this->metatags->create('index');

            $this->message->set('success', 'Изменения сохранены успешно');

            redirect(current_url());
        }

        $this->theme->setVar('content', $this->load->view($this->module_name.'/site_settings.php', $data, TRUE));
    }

}
