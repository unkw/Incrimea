<?php

class Admin extends MX_Controller {

    function  __construct() {

        // Имя модуля
        $this->module_name = 'event';

        // Хлебная крошка на главную страницу модуля
        $this->theme->set_breadcrumb('События', 'admin/event');

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
        $this->theme->setVar('title', 'События');

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
        $data = array();
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
                'resort_id'     => $this->input->post('edit-resorts'),
                'image_src'     => $this->input->post('edit-image'),
                'image_thumb'   => $this->input->post('edit-thumb'),
                'image_desc'    => $this->input->post('edit-image') ? $this->input->post('edit-image-desc') : '',
            );

            $this->model->add($cdata);

            $this->message->set('success', 'Событие "' . $cdata['title'] . '" создана успешно');

            redirect('admin/' . $this->module_name);
        }

        $title = 'Создание события';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data['content'] = array(
            'id' => '',
            'title' => '',
            'body' => '',
            'status' => 1,
            'sticky' => 0,
            'resort_id' => 0,
            'image_desc' => '',
            'image_src' => '',
            'image_thumb' => '',
        );
        $data['resorts'] = $this->model->get_resorts();
        // CKEditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit-event.php', $data, TRUE));
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
                'resort_id'     => $this->input->post('edit-resorts'),
                'image_src'     => $this->input->post('edit-image'),
                'image_thumb'   => $this->input->post('edit-thumb'),
                'image_desc'    => $this->input->post('edit-image') ? $this->input->post('edit-image-desc') : '',
            );
            
            $this->model->update($id, $cdata);

            $this->message->set('success', 'Изменения сохранены успешно');

            redirect('admin/'.$this->module_name);
        }

        $title = 'Редактирование события';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['content'] = $this->model->get($id);
        $data['resorts'] = $this->model->get_resorts();
        if (!$data['content'])
            show_404();
        // CKeditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit-event.php', $data, TRUE));
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

    /** Загрузка картинки */
    public function action_upload()
    {
        $this->load->library('upload', $this->config->config['upload']);

        if (!$this->upload->do_upload('uploadimg')) {
            echo $this->upload->display_errors('', '');
        }
        else
        {
            // Нарезка изображений нужного размера
            $this->model->create_images($this->upload->data());

            echo 'ok';
        }
    }
}