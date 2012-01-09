<?php

class Admin extends MX_Controller {

    function  __construct() {

        // Имя модуля
        $this->module_name = 'object';

        // Хлебная крошка на главную страницу модуля
        $this->theme->set_breadcrumb('Объекты', 'admin/' . $this->module_name);

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
        $this->theme->setVar('title', 'Объекты');

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
                'location'      => $this->input->post('edit-location'),
                'resort_id'     => $this->input->post('edit-resorts'),
                'region_id'     => $this->input->post('edit-region'),
                'type_id'       => $this->input->post('edit-types'),
                'images'        => $this->input->post('edit-img') ? $this->input->post('edit-img') : 0,
                'price'         => $this->input->post('edit-price'),
                'food'          => $this->input->post('edit-food'),
                'beach_distance'=> $this->input->post('edit-beach-distance'),
                'beach_id'      => $this->input->post('edit-beach-type'),
                'room'          => $this->input->post('edit-room'),
                'infrastructure'=> $this->input->post('edit-infrastructure'),
                'service'       => $this->input->post('edit-service'),
                'entertainment' => $this->input->post('edit-entertainment'),
                'for_children'  => $this->input->post('edit-for-children'),
                'body'          => $this->input->post('edit-body'),
                'published'     => $this->input->post('edit-published') ? 1 : 0,
                'priority'      => $this->input->post('edit-priority'),
                'created_date'  => time(),
                'last_update'   => time(),
                'uid'           => USER_AUTH_ID,
            );

            $this->model->add($cdata);

            $this->message->set('success', 'Объект "' . $cdata['title'] . '" создан успешно');

            redirect('admin/' . $this->module_name);
        }

        $title = 'Создание объекта';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Поля
        $data = $this->all_fields();
        // По-умолчанию
        $data['obj'] = array(
            'id' => '',
            'title' => '',
            'location' => '',
            'resort_id' => 0,
            'region_id' => 0,
            'type_id' => 0,
            'images' => $this->input->post('edit-img') ? $this->input->post('edit-img') : array(),
            'price' => '',
            'food' => '',
            'beach_distance' => '',
            'beach_id' => 0,
            'room' => array(),
            'infrastructure' => array(),
            'service' => array(),
            'entertainment' => array(),
            'for_children' => array(),
            'body' => '',
            'published' => 1,
            'priority' => 0,
        );

        // CKEditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit.php', $data, TRUE));
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
                'location'      => $this->input->post('edit-location'),
                'resort_id'     => $this->input->post('edit-resorts'),
                'region_id'     => $this->input->post('edit-region'),
                'type_id'       => $this->input->post('edit-types'),
                'images'        => $this->input->post('edit-img') ? $this->input->post('edit-img') : 0,
                'price'         => $this->input->post('edit-price'),
                'food'          => $this->input->post('edit-food'),
                'beach_distance'=> $this->input->post('edit-beach-distance'),
                'beach_id'      => $this->input->post('edit-beach-type'),
                'room'          => $this->input->post('edit-room'),
                'infrastructure'=> $this->input->post('edit-infrastructure'),
                'service'       => $this->input->post('edit-service'),
                'entertainment' => $this->input->post('edit-entertainment'),
                'for_children'  => $this->input->post('edit-for-children'),
                'body'          => $this->input->post('edit-body'),
                'published'     => $this->input->post('edit-published') ? 1 : 0,
                'priority'      => $this->input->post('edit-priority'),
                'last_update'   => time(),
            );
            
            $this->model->update($id, $cdata);

            $this->message->set('success', 'Изменения сохранены успешно');

            redirect('admin/'.$this->module_name);
        }

        $title = 'Редактирование объекта';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Поля
        $data = $this->all_fields();
        // Основные данные
        $data['obj'] = $this->model->get($id);

        if (!$data['obj'])
            show_404();

        // CKeditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit.php', $data, TRUE));
    }

    function action_delete($id = 0)
    {
        
    }

    function action_fields()
    {
        $title = 'Редактирование полей';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);

        $data = $this->all_fields();

        $this->theme->setVar('content', $this->load->view($this->module_name.'/fields.php', $data, TRUE));
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

        if (!$this->upload->do_upload('edit-images')) {
            echo $this->upload->display_errors('', '');
        }
        else
        {
            $img = $this->upload->data();
            // Нарезка изображений нужного размера
            $this->model->create_images($img);

            echo $img['file_name'];
        }
    }
    
    /** Все поля */
    protected function all_fields()
    {
        $data = array();

        // Места отдыха
        $data['resorts'] = $this->model->get_resorts();
        // Регионы
        $data['regions'] = $this->model->get_regions();
        // Типы объектов
        $data['types'] = $this->model->get_field('types');
        // Типы объектов
        $data['beachs'] = $this->model->get_field('beachs');
        // Типы объектов
        $data['room'] = $this->model->get_field('room');
        // Инфраструктура
        $data['infrastructure'] = $this->model->get_field('infrastructure');
        // Сервис
        $data['service'] = $this->model->get_field('service');
        // Развлечения и спорт
        $data['entertainment'] = $this->model->get_field('entertainment');
        // Для детей
        $data['for_children'] = $this->model->get_field('for_children');

        return $data;
    }

}