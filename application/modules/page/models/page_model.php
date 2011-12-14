<?php
class Page_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function get_count_all()
    {
        return $this->db->count_all('page');
    }

    /** Получить список страниц */
    public function get_pages($num, $offset)
    {
        $this->db
            ->select('p.*, u.username')
            ->from('page p')
            ->join('users u', 'u.id = p.uid')
            ->order_by('p.created_date', 'desc')
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
    public function get($id)
    {
        $q = $this->db->get_where('page', array('id' => $id));

        return $q->row_array();
    }
    
    /** Добавить страницу */
    public function add($data)
    {
        $this->db->insert_batch('page', array($data));
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        $this->db->where('id', $id)
            ->update('page', $data);
    }
}