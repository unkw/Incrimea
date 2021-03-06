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

        // Подменю
        $this->theme->add_submenu_item($this->config->config['admin_submenu']);
    }

    ////////////////////
    ///// ACTIONS //////
    ////////////////////

    public function action_index()
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
        $data['content'] = $this->model->get_list($config['per_page'], $offset);

        // Контент
        $this->theme->setVar('content', $this->load->view($this->module_name . '/admin_list.php', $data, TRUE));
    }

    /** Создание отеля */
    public function action_new()
    {
        $data = array();

        $this->form_validation->set_rules($this->config->config['validation_new']);

        if ($this->form_validation->run($this) === TRUE)
        {
            $this->model->create($this->processing_request_data());

            $this->message->set('success', 'Объект создан успешно');

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
        $data['obj'] = $this->config->config['default_fields'];
        // Метатеги
        $data['metatags'] = $this->metatags->html_form_fields();
        // Алиас
        $data['alias'] = $this->path->get_form_field();
        // Номерной фонд
        if ($this->input->post('room'))
            $data['obj']['room_found'] = $this->input->post('room');

        // CKEditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit.php', $data, TRUE));
    }

    /** Редактирование отеля */
    public function action_edit($id = 0)
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

        // Заголовок
        $title = 'Редактирование объекта';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Назначение заголовка
        $this->theme->setVar('title', $title);
        // Дополнительные поля
        $data = $this->all_fields();
        // Основные данные
        $data['obj'] = $this->model->get($id);
        
        if (!$data['obj'])
            show_404();

        // Метатеги
        $data['metatags'] = $this->metatags->html_form_fields($data['obj']['meta_id']);
        // Алиас
        $data['alias'] = $this->path->get_form_field($data['obj']['alias_id']);
        // CKeditor
        $this->editor_init();
        // Отображение
        $this->theme->setVar('content', $this->load->view($this->module_name.'/edit.php', $data, TRUE));
    }

    /** Удаление отеля */
    public function action_delete($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404();

        if ( $this->input->post('id') )
        {
            $this->model->delete($this->input->post('id'));
            
            $this->message->set('success', 'Удаление прошло успешно');
            redirect('admin/object/');
        }

        $title = 'Удаление отеля';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();
        $data['id'] = $id;
        $this->theme->setVar('content', $this->load->view('object/delete.php', $data, TRUE));        
    }

    /** Дополнительные поля отеля */
    public function action_fields()
    {
        $title = 'Редактирование полей';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);

        $data = $this->all_fields();

        $this->theme->setVar('content', $this->load->view($this->module_name.'/fields.php', $data, TRUE));
    }

    /** Загрузка изображений */
    public function ajax_upload()
    {
        $this->load->library('upload', $this->config->config['upload']);

        if (!$this->upload->do_upload('edit-images'))
        {
            echo 'ERROR: ' . $this->upload->display_errors('', '');
        }
        else
        {
            $img = $this->upload->data();
            // Нарезка изображений нужного размера
            $this->model->create_images($img);

            echo $img['file_name'];
        }
    }

    public function action_test(){

        echo 'html5 upload';
    }

    ////////////////////
    ////// AJAX ////////
    ////////////////////
    public function ajax_load_edit_room_form()
    {
        $chbx = $this->model->get_addition_fields();

        $data = array(
            'room' => $chbx['room'],
        );

        echo $this->load->view($this->module_name.'/edit-room.php', $data);
    }

    ////////////////////
    ///// METHODS //////
    ////////////////////
    /**
     * Обработка данных запроса для сохранения данных отеля
     * @param int $id - ID отеля
     */
    private function processing_request_data($id = 0)
    {
        $data = array(
            'title'         => $this->input->post('edit-title'),
            'info'          => $this->input->post('edit-info'),
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
    }

    /** Получить все поля с их значениями */
    protected function all_fields()
    {
        $data = array();

        // Места отдыха
        $data['resorts'] = $this->model->get_resorts();

        $data = array_merge($data, $this->model->get_addition_fields());
        
        return $data;
    }

}