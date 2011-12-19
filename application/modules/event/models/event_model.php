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

        $this->db->insert_batch('events', array($data));
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        $data['date_start'] = $this->toTimestamp($data['date_start']);
        $data['date_end'] = $this->toTimestamp($data['date_end']);

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
        $config = $this->config->config['image_lib'];
        $origin_img = 'images/temp/' . $img['file_name'];
        $new_img = 'images/event/' . $img['file_name'];
        $img_info = explode('.', $img['file_name']);
        $img_ext = array_pop($img_info);
        $thumb = 'images/event/' . implode('.', $img_info) .'_thumb.'.$img_ext;
        // Оригинальный файл
        $config['source_image'] = $origin_img;

        // Создание основного изображения
        $config['new_image'] = $new_img;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();

        $this->image_lib->clear();

        /** Создание превьюшки */
        $source = getimagesize($new_img);
        $x = $source[0];
        $y = $source[1];
        // Ширина и высота превью
        $w_preview = 100;
        $h_preview = 75;

        if ($x > $y)
        {
            $w = round( ($x/$y) * $h_preview );
            $h = $h_preview;
            $x_axis = round(($w - $w_preview)/2);
            $y_axis = 0;
        }
        else
        {
            $h = round( ($y/$x) * $w_preview );
            $w = $w_preview;
            $y_axis = round(($h - $h_preview)/2);
            $x_axis = 0;
        }

        $config['source_image'] = $config['new_image'];
        unset($config['new_image']);
        $config['create_thumb'] = TRUE;
        $config['thumb_marker'] = '_thumb';
        $config['width'] = $w;
        $config['height'] = $h;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();

        $this->image_lib->clear();

        $config['source_image'] = $thumb;
        $config['maintain_ratio'] = FALSE;
        $config['create_thumb'] = FALSE;
        $config['width'] = $w_preview;
        $config['height'] = $h_preview;
        $config['x_axis'] = $x_axis;
        $config['y_axis'] = $y_axis;

        $this->image_lib->initialize($config);
        $this->image_lib->crop();

        unlink($origin_img);
    }
}