<?php
class Path_Model extends CI_Model {
    
    private $table;

    public function __construct() {
        parent::__construct();

        $this->table = 'alias';
    }
    
    /** Получить псевдоним по id */
    public function get($id)
    {
        return $this->db->get_where($this->table, array('id' => (int)$id))->row_array();
    }

    /** Получить настоящий адрес */
    public function get_by_path($path)
    {
        $q = $this->db->get_where($this->table, array('alias' => $path))->row_array();

        return $q ? $q['realpath'] : NULL;
    }

    /** Создать url синоним */
    public function create($data)
    {
        $data['alias'] = $this->check_dublicate_path($data['alias']);
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /** Обновить url синоним */
    public function update($data, $id)
    {
        if ( ! $this->get($id) )
            return FALSE;

        $data['alias'] = $this->check_dublicate_path($data['alias'], $id);
        return $this->db->update($this->table, $data, array('id' => (int)$id));
    }

    /** Удалить url синоним */
    public function delete()
    {

    }

    /** Проверка сохраняемого алиаса на дубликаты */
    private function check_dublicate_path($alias, $id = 0)
    {
        $i = 1;
        while ($this->db->get_where($this->table, array('alias' => $alias, 'id <>' => $id))->num_rows())
        {
            $pattern = '/(\.html)$/';
            $ext = false;
            if ( preg_match($pattern, $alias) ) {
                $ext = true;
                $alias = preg_replace($pattern, '', $alias);
            }
            $alias .= '_'.$i.($ext ? '.html' : '');
            $i++;
        }

        return $alias;
    }
}
