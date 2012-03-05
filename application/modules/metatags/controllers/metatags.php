<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metatags extends MX_Controller {
     
    private $module_name, $model;
    
    function __construct()
    {
        $this->module_name = strtolower(get_class());

        // Подгрузка модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;
    }

    /** Получить метатеги по адресу */
    public function get_by_path($path)
    {
        return $this->model->get_by_path($path);
    }

    /** Получить метатеги по id */
    public function get($id, $title = '')
    {
        return $this->model->get($id);
    }

    /**
     * Создать метатеги
     */
    public function create($path = FALSE)
    {
        $metadata = array(
            'title'       => $this->input->post('edit-metatitle'),
            'keywords'    => $this->input->post('edit-keywords'),
            'description' => $this->input->post('edit-desc'),
        );

        if ($path)
            $metadata['path'] = $path;

        return $this->model->create($metadata);
    }

    /**
     * Обновить метатеги
     * @param int $id - primary key в таблице metatags
     * @param string $path - url адрес метатегов
     */
    public function update($meta_id, $path = FALSE)
    {
        $metadata = array(
            'title'       => $this->input->post('edit-metatitle'),
            'keywords'    => $this->input->post('edit-keywords'),
            'description' => $this->input->post('edit-desc'),
        );

        if ($path)
            $metadata['path'] = $path;

        $this->model->update($meta_id, $metadata);
    }

    public function html_form_fields_by_path($path = 0)
    {
        if (!$path || !empty($_POST))
            $data = array(
                'title'    => $this->input->post('edit-metatitle'),
                'keywords' => $this->input->post('edit-keywords'),
                'desc'     => $this->input->post('edit-desc'),
                'id'       => $this->input->post('edit-metaid')
            );
        else {
            $result = $this->model->get_by_path($path);
            $data = array(
                'title'    => isset($result['title']) ? $result['title'] : '',
                'keywords' => isset($result['keywords']) ? $result['keywords'] : '',
                'desc'     => isset($result['description']) ? $result['description'] : '',
                'id'       => isset($result['id']) ? $result['id'] : 0
            );
        }

        // Не отображать
        $data['path'] = FALSE;
        // Не отображать
        $data['view_path_field'] = FALSE;

        return $this->load->view('metatags/form_fields.php', $data, TRUE);
    }
    
    public function html_form_fields($id = 0, $viewPathField = FALSE)
    {
        if (!$id || !empty($_POST))
        {
            $data = array(
                'title'    => $this->input->post('edit-metatitle'),
                'keywords' => $this->input->post('edit-keywords'),
                'desc'     => $this->input->post('edit-desc'),
                'id'       => $this->input->post('edit-metaid'),
                'path'     => $this->input->post('edit-path')
            );
        }

        else {
            $result = $this->model->get($id);
            $data = array(
                'title'    => $result['title'],
                'keywords' => $result['keywords'],
                'desc'     => $result['description'],
                'id'       => $result['id'],
                'path'     => $result['path']
            );
        }

        // Отображать ли поле адреса метатега
        $data['view_path_field'] = $viewPathField ? $viewPathField : FALSE;

        return $this->load->view('metatags/form_fields.php', $data, TRUE);
    }

    public function html($meta)
    {
        $data = array(
            'keywords'    => isset($meta['keywords']) ? $meta['keywords'] : '',
            'description' => isset($meta['description']) ? $meta['description'] : '',
        );

        return $this->load->view('metatags/head.php', $data, TRUE);
    }
}
