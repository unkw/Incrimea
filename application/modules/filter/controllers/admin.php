<?php

class Admin extends MX_Controller {

    function  __construct() {

        // Имя модуля
        $this->module_name = 'filter';

        // Хлебная крошка на главную страницу модуля
        $this->theme->set_breadcrumb('Фильтры', 'admin/' . $this->module_name);

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;

        // Подгружаем конфиги модуля
        $this->load->config();
    }

    function action_index()
    {
        
    }
	
}

