<?php
class Event_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_count_all()
    {
        return $this->db->count_all('events');
    }

    /** Получить список страниц */
    public function get_pages($num, $offset)
    {
        $this->db
            ->select('e.*, u.username')
            ->from('events e')
            ->join('users u', 'u.id = e.uid')
            ->order_by('e.created_date', 'desc')
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

    /** Получить страницу */
    public function get($id, $published = FALSE)
    {
        $where = array('id' => $id);

        if ($published)
            $where['status'] = 1;

        $q = $this->db->get_where('events', $where);

        $data = $q->row_array();

        // Преобразование даты в нужный формат
        $data['date_start'] = $this->toDate($data['date_start']);
        $data['date_end'] = $this->toDate($data['date_end']);

        return $data;
    }
    
    /** Добавить страницу */
    public function add($data)
    {
        $data['date_start'] = $this->toTimestamp($data['date_start']);
        $data['date_end'] = $this->toTimestamp($data['date_end']);

        // Сохранение метатегов
        $data['meta_id'] = $this->metatags->create(); 

        $this->db->insert_batch('events', array($data));
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        $data['date_start'] = $this->toTimestamp($data['date_start']);
        $data['date_end'] = $this->toTimestamp($data['date_end']);

        // Сохранение метатегов
        if ( ! $this->metatags->update($data['meta_id']) )
            $data['meta_id'] = $this->metatags->create();

        $this->db->where('id', $id)
            ->update('events', $data);
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
        $origin_img = 'images/event/' . $img['file_name'];

        /** Создание основного изображения */
        $size = $this->config->config['image_main'];
        $large_img = $origin_img;
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        /** Создание превью изображения */
        $size = $this->config->config['thumb'];
        $thumb = 'images/event/thumb/' . $img['file_name'];
        $config = array();
        $config = array_merge($default_config, $size);
        $config['source_image'] = $large_img;
        $config['new_image'] = $thumb;

        $this->image_lib->resize_and_crop($config);
    }

}