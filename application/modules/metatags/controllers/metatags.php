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
    public function create()
    {
        $metadata = array(
            'title'       => $this->input->post('edit-metatitle'),
            'keywords'    => $this->input->post('edit-keywords'),
            'description' => $this->input->post('edit-desc'),
        );

        return $this->model->create($metadata);
    }

    /**
     * Обновить метатеги
     * @param int $id - primary key в таблице metatags
     */
    public function update($meta_id)
    {
        $metadata = array(
            'title'       => $this->input->post('edit-metatitle'),
            'keywords'    => $this->input->post('edit-keywords'),
            'description' => $this->input->post('edit-desc'),
        );

        return $this->model->update($meta_id, $metadata);
    }

    public function html_form_fields($id = 0)
    {
        if (!$id || !empty($_POST))
            $data = array(
                'title'    => $this->input->post('edit-metatitle'),
                'keywords' => $this->input->post('edit-keywords'),
                'desc'     => $this->input->post('edit-desc'),
                'id'       => $this->input->post('edit-metaid')
            );
        else {
            $result = $this->model->get($id);
            $data = array(
                'title'    => $result['title'],
                'keywords' => $result['keywords'],
                'desc'     => $result['description'],
                'id'       => $result['id']
            );
        }

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
