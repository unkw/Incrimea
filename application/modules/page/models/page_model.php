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
    public function get($id, $published = FALSE)
    {
        $where = array('id' => $id);

        if ($published)
            $where['status'] = 1;

        $q = $this->db->get_where('page', $where);

        return $q->row_array();
    }
    
    /** Добавить страницу */
    public function add($data)
    {
        // Сохранение метатегов
        $data['meta_id'] = $this->metatags->create();  

        $this->db->insert('page', $data);

        // Сохранение алиаса
        $this->save_path($this->db->insert_id(), $data, TRUE);
    }

    /** Обновить страницу */
    public function update($id, $data)
    {
        // Сохранение метатегов
        if ( ! $this->metatags->update($data['meta_id']) )
            $data['meta_id'] = $this->metatags->create();   

        // Сохранение синонима
        $this->save_path($id, & $data);

        $this->db->where('id', $id)
            ->update('page', $data);
    }

    /** Сохранение синонима */
    private function save_path($id, & $data, $create = FALSE)
    {
        $pathdata = array(
            'realpath' => 'page/view/'.$id,
            'auto'     => $this->input->post('pathauto') ? 1 : 0,
        );

        // Формируем алиас
        if (!$pathdata['auto'] && trim($this->input->post('path')))
            $pathdata['alias'] = $this->input->post('path');
        else
            $pathdata['alias'] = $data['title'];

        // Сохранение
        if ( $create || !$data['alias_id'] || !$this->path->update($pathdata, $data['alias_id']) )
            $data['alias_id'] = $this->path->create($pathdata);

        // Обновить alias_id контента при создании контента
        if ($create)
            $this->db->update('page', array('alias_id'=>$data['alias_id']), array('id'=>$id));

        return TRUE;
    }

}