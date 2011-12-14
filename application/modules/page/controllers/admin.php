<?php

class Admin extends MX_Controller {

    function  __construct() {

        // Имя модуля
        $this->module_name = 'page';

        // Хлебная крошка на главную страницу модуля
        $this->theme->set_breadcrumb('Страницы', 'admin/page');

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

    function action_index()
    {
        $this->load->library('pagination');

        // Заголовок
        $this->theme->setVar('title', 'Контент');

        // Список пользователей и пагинация
        $config['base_url'] = base_url() . 'admin/'.$this->module_name;
        $config['total_rows'] = $this->model->get_count_all();
        $config['per_page'] = 20;
        $config['first_url'] = base_url() . 'admin/'.$this->module_name;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $page = (int)$this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $config['per_page'];

        if ($offset > $config['total_rows'])
            show_404();

        $data = array();
        $data['pager'] = $this->pagination->create_links();
        $data['content'] = $this->model->get_pages($config['per_page'], $offset);

        // Контент
        $this->theme->setVar('content', $this->load->view($this->module_name . '/admin_list.php', $data, TRUE));
    }

    function action_new()
    {
        $this->form_validation->set_rules($this->config->config['validation_new']);

        if ($this->form_validation->run($this) === TRUE)
        {
            $cdata = array(
                'title'         => $this->input->post('edit-title'),
                'preview'       => mb_substr($this->input->post('edit-body'), 0, 100),
                'body'          => $this->input->post('edit-body'),
                'created_date'  => time(),
                'last_update'   => time(),
                'status'        => $this->input->post('edit-status') ? 1 : 0,
                'sticky'        => $this->input->post('edit-sticky') ? 1 : 0,
                'uid'           => USER_AUTH_ID,
            );

            $this->model->add($cdata);

            $this->message->set('success', 'Материал "' . $cdata['title'] . '" создан успешно');

            redirect('admin/' . $this->module_name);
        }

        $title = 'Создание страницы';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['content'] = array(
            'title' => '',
            'body' => '',
            'status' => 1,
            'sticky' => 0,
        );
        // CKEditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit-page.php', $data, TRUE));
    }

    function action_edit($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404();

        $this->form_validation->set_rules($this->config->config['validation_new']);

        if ($this->form_validation->run($this))
        {
            $cdata = array(
                'title'         => $this->input->post('edit-title'),
                'preview'       => mb_substr($this->input->post('edit-body'), 0, 100),
                'body'          => $this->input->post('edit-body'),
                'last_update'   => time(),
                'status'        => $this->input->post('edit-status') ? 1 : 0,
                'sticky'        => $this->input->post('edit-sticky') ? 1 : 0,
            );

            $this->model->update($id, $cdata);

            $this->message->set('success', 'Изменения сохранены успешно');

            redirect('admin/'.$this->module_name);
        }

        $title = 'Редактирование страницы';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['content'] = $this->model->get($id);
        if (!$data['content'])
            show_404();
        // CKeditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit-page.php', $data, TRUE));
    }

    function action_delete($id = 0)
    {
        
    }

    /** Инициализация графического редактора */
    private function editor_init()
    {
        $this->load->library('ckeditor');
        $this->ckeditor->basePath = base_url().'asset/ckeditor/';
        $this->ckeditor->config['toolbar'] = 'Full';
        $this->ckeditor->config['language'] = 'ru';
    }
}