<?php
class Object_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_count_all()
    {
        return $this->db->count_all('objects');
    }

    /** Получить список страниц */
    public function get_pages($num, $offset)
    {
        $this->db
            ->select('o.*, u.username')
            ->from('objects o')
            ->join('users u', 'u.id = o.uid')
            ->order_by('o.created_date', 'desc')
            ->limit($num, $offset);

        $query = $this->db->get();

        return $query->result_array();
    }

    /** Получить список всех курортов */
    public function get_resorts()
    {
        $this->db->select('*')
            ->from('resorts')
            ->order_by('name', 'asc');

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Получить список всех типов объектов */
    public function get_types()
    {
        $this->db->select('*')
            ->from('object_types')
            ->order_by('name', 'asc');

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Получить список того, что входит в инфраструктуру */
    public function get_structure()
    {
        $this->db->select('*')
            ->from('object_structure')
            ->order_by('id', 'asc');

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Получить страницу */
    public function get($id, $published = FALSE)
    {
        $where = array('o.id' => $id);

        if ($published)
            $where['o.status'] = 1;

        $this->db->select('o.*, t.name as type, r.name as resort')
            ->from('objects o')
            ->join('object_types t', 't.id = o.type_id')
            ->join('resorts r', 'r.id = o.resort_id')
            ->where($where);

        $q = $this->db->get();

        $data = $q->row_array();

        $data['structure'] = json_decode($data['structure']);

        $data['images'] = $data['images'] ? json_decode($data['images']) : array();

        return $data;
    }

    /** Получить инфраструктурные данные объекта в виде массива */
    public function structure_to_array($data)
    {
        $this->db->select('*')
            ->from('object_structure')
            ->where_in('url_name', $data);

        $q = $this->db->get();

        return $q->result_array();
    }
    
    /** Добавить страницу */
    public function add($data)
    {
        // Сохраняем значения чекбоксов "Инфраструктуры" в формате json
        $data['structure'] = json_encode($data['structure']);

        // Преобразование изображений в json формат
        $data['images'] = $this->img_src_to_json($data['images']);

        $this->db->insert_batch('objects', array($data));
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        // Сохраняем значения чекбоксов "Инфраструктуры" в формате json
        $data['structure'] = json_encode($data['structure']);

        // Преобразование изображений в json формат
        $data['images'] = $this->img_src_to_json($data['images']);

        $this->db->where('id', $id)
            ->update('objects', $data);
    }

    /** Преобразование массива с именами изображений в json */
    public function img_src_to_json($srcArr)
    {
        return json_encode($srcArr);
    }

    /** Перевод строки вида "dd-mm-yyyy" в timestamp */
    public function toTimestamp($string)
    {
        $arr = explode('-', $string);

        return mktime(0, 0, 0, $arr[1], $arr[0], $arr[2]);
    }

    /** Перевод timestamp в date формат */
    public function toDate($timestamp, $format = FALSE)
    {
        $format = $format ? $format : 'd-m-Y';

        return date($format, $timestamp);
    }

    /** Ресайз изображения и создание его превьюшки */
    public function create_images($img)
    {
        // Основные параметры
        $default_config = $this->config->config['image_lib'];
        // Оригинальный файл
        $origin_img = 'images/object/large/' . $img['file_name'];

        /** Создание large изображения */
        $size = $this->config->config['image_large'];
        $large_img = $origin_img;
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        /** Создание medium изображения */
        $size = $this->config->config['image_medium'];
        $medium_img = 'images/object/medium/' . $img['file_name'];
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;
        $config['new_image'] = $medium_img;

        $this->resize_and_crop($config);

        /** Создание превью изображения */
        $size = $this->config->config['thumb'];
        $thumb = 'images/object/thumb/' . $img['file_name'];
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;
        $config['new_image'] = $thumb;

        $this->resize_and_crop($config);
    }

    /** Resize and Crop :) */
    function resize_and_crop($conf)
    {
        // Исходный файл
        $source = getimagesize($conf['source_image']);
        // Ширина и высота исходного файла
        $x = $source[0];
        $y = $source[1];

        // Определяем параметры для ресайза и кропа
        if ($x > $y)
        {
            $w = round( ($x/$y) * $conf['height'] );
            $h = $conf['height'];
            $x_axis = round(($w - $conf['width'])/2);
            $y_axis = 0;
        }
        else
        {
            $h = round( ($y/$x) * $conf['width'] );
            $w = $conf['width'];
            $y_axis = round(($h - $conf['height'])/2);
            $x_axis = 0;
        }

        /** Создание временного файла, для дальнейшего кропа */
        $temp_conf = $conf;
        $temp_conf['width'] = $w;
        $temp_conf['height'] = $h;
        $this->image_lib->initialize($temp_conf);
        $this->image_lib->resize();
        $this->image_lib->clear();

        /** Делаем crop */
        $config = array();
        $config['source_image'] = $conf['new_image'];
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $conf['width'];
        $config['height'] = $conf['height'];
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
        
        return TRUE;
    }
}