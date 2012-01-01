<?php
class Filter_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /** Получить список объектов */
    public function get_objects($params, $per_page, $offset)
    {
        $params = $params ? $params : array();

        // Только опубликованные
        $where = array(
            'o.status' => 1
        );

        /** Основные данные отелей */
        $this->db->select('o.id, o.title, o.body, o.images, o.min_price, o.structure, r.name as resort')
            ->from('objects o')
            ->join('resorts r', 'o.resort_id = r.id')
            ->where($where);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        $data = $this->db->order_by('o.sticky desc, o.created_date desc')
            ->limit($per_page, $offset)
            ->get()
            ->result_array();

        // Инфраструктура - все чекбоксы
        $structure_all = $this->db->get('object_structure')->result_array();

        /** Преобразование данных */
        foreach ($data as &$obj)
        {
            // Галерея изображений
            $obj['images'] = $obj['images'] ? json_decode($obj['images']) : array();
            
            /** Инфраструктура */
            $obj['structure'] = $obj['structure'] ? json_decode($obj['structure']) : array();
            $_structure = array();
            foreach ($structure_all as $s)
            {
                $index = array_search($s['url_name'], $obj['structure']);
                if ($index !== FALSE)
                    $_structure[] = $s;
            }
            $obj['structure'] = $_structure;
        }

        return $data;
    }

    /** Кол-во всех отелей */
    public function count_all_objects($params)
    {
        $this->db->from('objects o')->join('resorts r', 'r.id = o.resort_id')->where('o.status', 1);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        return $this->db->count_all_results();
    }

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

    /** Получить инфраструктурные данные объекта в виде массива */
    public function structure_to_array($data)
    {
        $this->db->select('*')
            ->from('object_structure')
            ->where_in('url_name', $data);

        $q = $this->db->get();

        return $q->result_array();
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

}