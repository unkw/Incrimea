<?php

class Admin extends MX_Controller {

    /** PAGE MODULE */
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

        // Подменю
        $this->theme->add_submenu_item($this->config->config['admin_submenu']);
    }

    function action_index()
    {
        $this->load->library('pagination');

        // Заголовок
        $this->theme->setVar('title', 'Страницы');

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
        $data['module'] = $this->module_name;
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
            $this->model->add($this->processing_request_data());

            $this->message->set('success', 'Страница создана успешно');

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

        // Метатеги
        $data['metatags'] = $this->metatags->html_form_fields();
        // Алиас
        $data['alias'] = $this->path->get_form_field();
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
            $this->model->update($id, $this->processing_request_data($id));

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

        // Метатеги
        $data['metatags'] = $this->metatags->html_form_fields($data['content']['meta_id']);
        // Алиас
        $data['alias'] = $this->path->get_form_field($data['content']['alias_id']);
        // CKeditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit-page.php', $data, TRUE));
    }

    function action_delete($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404();

        if ( $this->input->post('id') )
        {
            $this->model->delete($this->input->post('id'));
            
            $this->message->set('success', 'Удаление прошло успешно');
            redirect('admin/page/');
        }

        $title = 'Удаление страницы';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['id'] = $id;
        $this->theme->setVar('content', $this->load->view('page/delete.php', $data, TRUE));        
    }

    private function processing_request_data($id = 0)
    {
        $data = array(
            'title'         => $this->input->post('edit-title'),
            'preview'       => mb_substr($this->input->post('edit-body'), 0, 100),
            'body'          => $this->input->post('edit-body'),
            'last_update'   => time(),
            'status'        => $this->input->post('edit-status') ? 1 : 0,
            'sticky'        => $this->input->post('edit-sticky') ? 1 : 0,
        );

        // Дополнительные данные при создании отеля
        if (!$id)
        {
            $data['created_date'] = time();
            $data['uid'] = USER_AUTH_ID;
        }
        else
        {
            $data['meta_id'] = $this->input->post('edit-metaid');
            $data['alias_id'] = $this->input->post('pathid');
        }

        return $data;
    }

    /** Инициализация графического редактора */
    private function editor_init()
    {
        $this->load->library('ckeditor');
        $this->ckeditor->basePath = base_url().'asset/ckeditor/';
        $this->ckeditor->config['toolbar'] = 'Full';
        $this->ckeditor->config['language'] = 'ru';
        $this->ckeditor->config['height'] = '350';
    }
}