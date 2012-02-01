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

    /** Создать url синоним */
    public function create($data)
    {
        $data['alias'] = $this->clear_alias($data);
        return $this->model->create($data);
    }

    /** Обновить url синоним */
    public function update($data, $alias_id)
    {
        $data['alias'] = $this->clear_alias($data);
        return $this->model->update($data, $alias_id);
    }

    /** Очистка алиаса от недопустимых символов */
    private function clear_alias($data)
    {
        $alias = $data['alias'];
        // Транслитерация
        $this->load->helper('text');
        $alias = strtolower(convert_accented_characters($alias));

        // Очистка
        $alias = preg_replace('/[^a-z 0-9~%.:_\-\/]/i', '', $alias);
        $alias = preg_replace('/\s+/', '_', $alias);

        if ($data['auto'])
            $alias .= $this->suffix;

        return $alias;
    }
}
