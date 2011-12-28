<?php
class Filter_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
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