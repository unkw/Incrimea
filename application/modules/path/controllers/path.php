<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Path extends MX_Controller {
     
    private $module_name;

    private $suffix = '.html';
    
    function __construct()
    {
        // Имя модуля
        $this->module_name = strtolower(get_class());

        // Подключение модели
        $this->load->model($this->module_name.'/'.$this->module_name.'_model');
        $model = $this->module_name.'_model';
        $this->model = $this->$model;
    }

    /** Получить реальный адрес */
    public function get_real_path()
    {
        $path = $this->uri->uri_string();
        $realpath = $this->model->get_by_path($this->uri->uri_string());

        return explode('/', $realpath ? $realpath : $path);
    }

    /** Получить поля формы для редактирования */
    public function get_form_field($alias_id = 0)
    {
        if ( ! empty($_POST) )
        {
            $data = array(
                'checked' => $this->input->post('pathauto') ? 'checked="checked"' : '',
                'disabled'=> $this->input->post('pathauto') ? 'disabled="disabled"' : '',
                'path'    => $this->input->post('path') ? $this->input->post('path') : '',
            );
        }
        else
        {
            if ($alias_id)
                $pathdata = $this->model->get($alias_id);

            $data = array(
                'checked' => (isset($pathdata['auto']) && $pathdata['auto'] == 1) || empty($pathdata) ? 'checked="checked"' : '',
                'disabled'=> (isset($pathdata['auto']) && $pathdata['auto'] == 1) || empty($pathdata) ? 'disabled="disabled"' : '',
                'path'    => !empty($pathdata) ? $pathdata['alias'] : '',
                'id'      => !empty($pathdata) ? $pathdata['id'] : 0,
            );
        }

        return $this->load->view($this->module_name . '/form_field.php', $data, TRUE);
    }

    /**
     * Detection admin url
     * @return bool
     */
    public function is_admin_url()
    {
        return $this->uri->segment(1) == 'admin' ? TRUE : FALSE;
    }

    /** Создать url синоним */
    public function create($data)
    {
        $data['alias'] = $this->valid_alias($data);
        return $this->model->create($data);
    }

    /** Обновить url синоним */
    public function update($data, $alias_id)
    {
        $data['alias'] = $this->valid_alias($data);
        return $this->model->update($data, $alias_id);
    }

    /** Очистка алиаса от недопустимых символов */
    private function valid_alias($data)
    {
//        print_r($data['alias']);
        $alias = explode('/', $data['alias']);

        $valid_alias = array();
        $this->load->helper('text');

        foreach ($alias as $a) {

            $valid_alias[] = strtolower(url_title(convert_accented_characters($a)));
        }

        $alias = implode('/', $valid_alias);
        if ($data['auto'])
            $alias .= $this->suffix;

        return $alias;
    }
}
