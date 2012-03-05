<?php
class Metatags_Model extends CI_Model {
    
    private $table = 'metatags';

    public function __construct() {

        parent::__construct();
    }

    /** Получить метатеги по адресу из URL */
    public function get_by_path($path)
    {
        return $this->db->get_where($this->table, array('path' => $path))->row_array();
    }

    /** Получить метатеги по id */
    public function get($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row_array();
    }
    
    /** Создать метатеги */
    public function create($metadata)
    {
        $this->db->insert($this->table, $metadata);

        return $this->db->insert_id();
    }

    public function update($meta_id, $metadata)
    {
        $this->db->update($this->table, $metadata, array('id' => (int)$meta_id));
    }

    /**
     * Получить список метатегов
     * @param int $num - кол-во
     * @param int $offset - позиция в таблице бд
     * @return array
     */
    public function get_list($num, $offset, $params)
    {
        $this->db
            ->select('m.*')
            ->from($this->table.' m')
            ->order_by('id', 'desc')
            ->limit($num, $offset);

        $this->add_condition($params);

        return $this->db->get()->result_array();
    }

    /** Получить кол-во всех метатегов */
    public function get_count_all($params)
    {
        $this->db->from($this->table);

        $this->add_condition($params);

        return $this->db->count_all_results();
    }

    /** Дополнительные условия */
    private function add_condition($params)
    {
        // Показывать только метатеги для произвольных страниц
        $this->db->where('path <>', '');
    }

}