<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MX_Controller {
     
    private $module_name;
    
    function __construct()
    {
        $this->module_name = strtolower(get_class());
    }
    
    function action_login()
    {
        if ($this->auth->loggin_in()) show_404();

        $data = array('error_login' => FALSE);

        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $config = array(
            array(
                'field' => 'login',
                'label' => 'Логин',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'Пароль',
                'rules' => 'required'
            ),
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run())
        {
            if ($this->auth->login($this->input->post('login'), $this->input->post('password')))
            {
                if ($this->auth->is_admin())
                    redirect('admin');
                else
                    redirect('');
            }
            else
            {
                $data['error_login'] = 'Логин или пароль введены неверно';
            }
        }

        $this->theme->setVar('title', 'Вход');
        $this->theme->setVar('content', $this->load->view($this->module_name.'/login.php', $data, TRUE));
    }

    function action_logout()
    {
        if ($this->auth->loggin_in())
        {
            $this->session->sess_destroy();

            redirect('');
        }
        else
            show_404 ();
    }
}
