<?php

class Admin extends MX_Controller {

    public $default_url;
    public $model;

    function  __construct() {

        // Имя модуля
        $this->module_name = 'user';

        // Хлебная крошка на главную страницу модуля
        $this->theme->set_breadcrumb('Пользователи', 'admin/user/list');

        // Хэлпер форм и библиотека валидации форм
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;

        // Подгружаем конфиги модуля
        $this->load->config();
    }

    /** Список пользователей */
    function action_list()
    {
        $this->load->library('pagination');

        // Заголовок
        $this->theme->setVar('title', 'Пользователи');

        // Список пользователей и пагинация
        $config['base_url'] = base_url() . 'admin/user/list';
        $config['total_rows'] = $this->model->get_count_all();
        $config['per_page'] = 20;
        $config['first_url'] = base_url() . 'admin/user/list';
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $page = (int)$this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $config['per_page'];

        if ($offset > $config['total_rows'])
            show_404();

        $data = array();
        $data['pager'] = $this->pagination->create_links();
        $data['users'] = $this->model->get_users($config['per_page'], $offset);

        // Контент
        $this->theme->setVar('content', $this->load->view('user/admin_list.php', $data, TRUE));
    }

    /** Добавить нового пользователя */
    function action_add()
    {
        $this->form_validation->set_rules($this->config->config['profile_validation_add']);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        if ($this->form_validation->run($this) === TRUE)
        {
            $userdata = array(
                'username'    => $this->input->post('edit-name'),
                'password'    => sha1($this->input->post('edit-pass')),
                'email'       => $this->input->post('edit-email'),
                'created_date' => time(),
                'active'      => $this->input->post('edit-status'),
                'role_id'     => $this->input->post('edit-role'),
            );
            
            $this->model->add_user($userdata);

            redirect('admin/user/list');
        }

        $title = 'Добавить пользователя';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, 'admin/user/add');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['user'] = array(
            'id'       => '',
            'email'    => '',
            'username' => '',
            'active'   => TRUE,
            'role_id'   => 3 // По-умолчанию "Зарегистрированный"
        );
        $data['roles'] = $this->model->get_roles();
        $this->theme->setVar('content', $this->load->view('user/profile_form.php', $data, TRUE));
    }

    /** Редактировать профиль пользователя */
    function action_edit($uid = 0)
    {
        if (!$uid || !is_numeric($uid))
            show_404 ();
        
        $this->form_validation->set_rules($this->config->config['profile_validation_edit']);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        if ($this->form_validation->run($this))
        {
            $userdata = array(
                'username'    => $this->input->post('edit-name'),
                'email'       => $this->input->post('edit-email'),
                'active'      => $this->input->post('edit-status'),
                'role_id'     => $this->input->post('edit-role'),
            );

            $this->model->update_user($uid, $userdata);

            $this->message->set('success', 'Изменения сохранены успешно');

            redirect('admin/user/list');
        }

        $title = 'Редактирование пользователя';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['roles'] = $this->model->get_roles();
        $data['user'] = $this->model->get_user($uid);
        $this->theme->setVar('content', $this->load->view('user/profile_form.php', $data, TRUE));
    }

    /** Удалить пользователя */
    function action_delete($uid = 0)
    {
        if (!$uid || !is_numeric($uid))
            show_404 ();

        if ($this->input->post('uid') && $this->input->post('uid') != 1)
        {
            $this->model->delete_user($this->input->post('uid'));

            redirect('admin/user/list');
        }

        $title = 'Удаление пользователя';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['uid'] = $uid;
        $this->theme->setVar('content', $this->load->view('user/profile_delete_form.php', $data, TRUE));
    }

    /** Настройки модулья "Пользователи" */
    function action_settings()
    {
        $title = 'Настройки';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $this->theme->setVar('content', 'В разработке...');
    }

    /** Проверка уникальности почтового адреса */
    function check_username($name)
    {
        $uid = $this->input->post('user-id');

        if ($this->model->is_unique_username($name, $uid))
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('check_username', 'Пользователь с таким именем уже имеется на сайте');
            return FALSE;
        }
    }

    /** Проверка уникальности почтового адреса */
    function check_email($email)
    {
        $uid = $this->input->post('user-id');

        if ($this->model->is_unique_email($email, $uid))
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('check_email', 'Пользователь с таким email уже имеется на сайте');
            return FALSE;
        }
    }
}