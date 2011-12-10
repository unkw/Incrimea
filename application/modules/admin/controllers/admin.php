<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
     
    private $module_name;
    
    function __construct()
    {
        $this->module_name = strtolower(get_class());
    } 
    
    function action_index()
    {
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';

        $module_content = $this->load->view($this->module_name.'/'.$this->module_name.'.php', FALSE, TRUE);
        $this->theme->setVar('title', 'Административная панель');
        $this->theme->setVar('content', $module_content);
        $this->theme->body_classes = array($this->module_name);
    }
}
