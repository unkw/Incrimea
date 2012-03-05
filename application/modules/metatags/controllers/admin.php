<?php

class Admin extends MX_Controller {

    public $model;

    function  __construct() {

        // Имя модуля
        $this->module_name = 'metatags';

        // Хлебная крошка на главную страницу модуля
        $this->theme->set_breadcrumb('Метатеги', 'admin/metatags');

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

    public function action_index()
    {
        $this->load->library('pagination');

        // Заголовок
        $this->theme->setVar('title', 'Метатеги');

        // Параметры фильтров списка
        $params = array(
            'list' => $this->input->get('list', TRUE),
        );

        // Список метатегов и пагинация
        $config['base_url'] = base_url() . 'admin/metatags';
        $config['total_rows'] = $this->model->get_count_all($params);
        $config['per_page'] = 20;
        $config['first_url'] = base_url() . 'admin/metatags';
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $page = (int)$this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $config['per_page'];

        if ($offset > $config['total_rows'])
            show_404();

        $data = array();
        // Пейджер
        $data['pager'] = $this->pagination->create_links();
        // Метатеги
        $data['metatags'] = $this->model->get_list($config['per_page'], $offset, $params);
        // Значения параметров фильтров
        $data['params'] = $params;
        // Контент
        $this->theme->setVar('content', $this->load->view('metatags/admin.php', $data, TRUE));
    }

    /** Добавить метатег (только для произвольной страницы) */
    function action_new()
    {
        if (!empty($_POST)) {

            if ( !trim($this->input->post('edit-path')) )
                $this->message->set('error', 'Поле адреса обязательно для заполнения');
            else 
            {
                $this->metatags->create($this->input->post('edit-path'));

                $this->message->set('success', 'Метатеги созданы успешно');

                redirect('admin/metatags');  
            }
        }

        $title = 'Добавить';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();

        $data['metatags'] = $this->metatags->html_form_fields(0, TRUE);

        $this->theme->setVar('content', $this->load->view('metatags/edit_form.php', $data, TRUE));
    }

    /** Редактирование */
    function action_edit($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404 ();
        
        if (!empty($_POST)) {

            $this->metatags->update($this->input->post('edit-metaid'), $this->input->post('edit-path'));

            $this->message->set('success', 'Изменения сохранены успешно');

            redirect('admin/metatags');
        }

        $title = 'Редактирование';
        // Хлебная крошка
        $this->theme->set_breadcrumb($title, '');
        // Заголовок
        $this->theme->setVar('title', $title);
        // Контент
        $data = array();

        $data['metatags'] = $this->metatags->html_form_fields($id);

        $this->theme->setVar('content', $this->load->view('metatags/edit_form.php', $data, TRUE));
    }

    /** Удалить пользователя */
    function action_delete($id = 0)
    {
        if (!$id || !is_numeric($id))
            show_404 ();

    }
}