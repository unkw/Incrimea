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
            ->select('p.*, a.alias, u.username')
            ->from('page p')
            ->join('alias a', 'a.id = p.alias_id', 'left')
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
        $where = array('id' => $id);

        return $this->db->get_where('page', $where)->row_array();
    }
    
    /** Добавить страницу */
    public function add($data)
    {
        // Сохранение метатегов
        $data['meta_id'] = $this->metatags->create();  

        $this->db->insert('page', $data);
        $page_id = $this->db->insert_id();

        // Сохранение синонима
        $path_data = $this->generate_path($page_id, $data);
        $alias_id = $this->path->create($path_data);
        $this->db->update('page', array('alias_id' => $alias_id), array('id' => $page_id));
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        // Сохранение метатегов
        $this->metatags->update($data['meta_id']);   

        // Сохранение синонима
        $path_data = $this->generate_path($id, $data);
        $this->path->update($data['alias_id'], $path_data);

        $this->db->where('id', $id)
            ->update('page', $data);
    }
    
    /**
     * Удаление страницы
     * @param int $id - ID 
     */
    public function delete($id)
    {
        $data = $this->db
            ->query('SELECT alias_id, meta_id FROM page WHERE id = ?', array($id))
            ->row_array();
        
        // Удаление страницы
        $this->db->delete('page', array('id' => $id));
        // Удалить синоним в таблице алиасов
        $this->db->delete('alias', array('id' => $data['alias_id']));
        // Удалить метатеги
        $this->db->delete('metatags', array('id' => $data['meta_id']));
    }    

    /**
     * Генерация url синонима
     * @param int $obj_id - ID отеля
     * @param array $data - данные
     * @return array
     */
    public function generate_path($id, $data)
    {
        $pathdata = array(
            'realpath' => 'page/view/'.$id,
            'auto' => $this->input->post('pathauto') ? 1 : 0,
        );
        
        if ($pathdata['auto'])
        {
            $pathdata['alias'] = array($data['title']);
        }
        else
        {
            $pathdata['alias'] = array($this->input->post('path'));
        }
        
        return $pathdata;
    }

}