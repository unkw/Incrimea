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
            'o.published' => 1
        );

        /** Основные данные отелей */
        $this->db->select('o.id, o.title, o.body, o.images, o.price, o.infrastructure, r.name as resort')
            ->from('objects o')
            ->join('resorts r', 'o.resort_id = r.id')
            ->join('obj_beachs b', 'o.beach_id = b.id');

        // Дополнительные фильтры
        $this->obj_add_filters($params);

        $data = $this->db->where($where)
            ->order_by('o.priority desc, o.created_date desc')
            ->limit($per_page, $offset)
            ->get()
            ->result_array();

        // Инфраструктура - все чекбоксы
        $structure_all = $this->db->get('obj_infrastructure')->result_array();

        /** Преобразование данных */
        foreach ($data as &$obj)
        {
            // Галерея изображений
            $obj['images'] = $obj['images'] ? json_decode($obj['images']) : array();
            
            /** Инфраструктура */
            $obj['infrastructure'] = $obj['infrastructure'] ? json_decode($obj['infrastructure']) : array();
            $_structure = array();
            foreach ($structure_all as $s)
            {
                $index = array_search($s['url_name'], $obj['infrastructure']);
                if ($index !== FALSE)
                    $_structure[] = $s;
            }
            $obj['infrastructure'] = $_structure;
        }

        return $data;
    }

    /** Получить список статей */
    public function get_articles($params, $per_page, $offset)
    {
        $params = $params ? $params : array();

        // Только опубликованные
        $where = array(
            'a.status' => 1
        );

        /** Основные данные статей */
        $this->db->select('a.id, a.title, a.preview, a.image_src, a.image_desc, r.name as resort')
            ->from('articles a')
            ->join('resorts r', 'a.resort_id = r.id')
            ->where($where);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        $data = $this->db->order_by('a.sticky desc, a.created_date desc')
            ->limit($per_page, $offset)
            ->get()
            ->result_array();

        return $data;
    }

    /** Получить список событий */
    public function get_events($params, $per_page, $offset)
    {
        $params = $params ? $params : array();

        // Только опубликованные
        $where = array(
            'e.status' => 1
        );

        /** Основные данные статей */
        $this->db->select('e.id, e.title, e.preview, e.image_src, e.image_desc, r.name as resort')
            ->from('events e')
            ->join('resorts r', 'e.resort_id = r.id')
            ->where($where);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        $data = $this->db->order_by('e.sticky desc, e.created_date desc')
            ->limit($per_page, $offset)
            ->get()
            ->result_array();

        return $data;
    }

    /** Получить список мест отдыха */
    public function get_resorts()
    {
        $this->db->select('*')
            ->from('resorts')
            ->order_by('name', 'asc');

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Получить список того, что входит в инфраструктуру */
    public function get_field($field, $where = FALSE)
    {
        if (is_array($where) && !$where)
            return array();

        $prefix = 'obj_';

        $this->db->select('*')
            ->from($prefix.$field)
            ->order_by('name', 'asc');

        if ($where)
            $this->db->where_in('url_name', $where);

        $q = $this->db->get();

        return $q->result_array();
    }

    /** Кол-во всех отелей */
    public function count_all_objects($params)
    {
        $this->db->from('objects o')
            ->join('resorts r', 'r.id = o.resort_id')
            ->join('obj_beachs b', 'b.id = o.beach_id')
            ->where('o.published', 1);

        $this->obj_add_filters($params);

        return $this->db->count_all_results();
    }

    /** Кол-во всех статей */
    public function count_all_articles($params)
    {
        $this->db->from('articles a')->join('resorts r', 'r.id = a.resort_id')->where('a.status', 1);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        return $this->db->count_all_results(); 
    }

    /** Кол-во всех событий */
    public function count_all_events($params)
    {
        $this->db->from('events e')->join('resorts r', 'r.id = e.resort_id')->where('e.status', 1);

        if (isset($params['resorts']))
            $this->db->where_in('r.url_name', explode(',', $params['resorts']));

        return $this->db->count_all_results();
    }

    /** Условия накладываемые дополнительными фильтрами объектов */
    private function obj_add_filters($params)
    {
        $fields = array(
            'resorts' => 'r',
            'beachs' => 'b',
        );

        foreach ($fields as $k => $alias)
            $this->fields_query_builder($params, $k, $alias);

        $json_fields = array(
            'room' => 'room',
            'infr' => 'infrastructure',
            'service' => 'service',
            'entment' => 'entertainment',
            'child' => 'for_children',
        );

        foreach ($json_fields as $key => $column)
            $this->like_query_builder($params, $key, $column);
    }

    /** Построитель запросов для связей один ко многим */
    private function fields_query_builder($params, $name, $alias)
    {
        if (isset($params[$name])) {
            $this->db->where_in($alias.'.url_name', explode(',', $params[$name]));
        }
    }

    /** Построитель запросов для извлечения LIKE(-ом) */
    private function like_query_builder($params, $name, $column)
    {
        if (isset($params[$name])) {
            $data = explode(',', $params[$name]);
            foreach ($data as $d)
                $this->db->like('o.'.$column, '"'.$d.'"');
        }
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