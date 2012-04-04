<?php
class Path_Model extends CI_Model {
    
    private $table;

    public function __construct() {
        parent::__construct();

        /** Таблица БД модуля */
        $this->table = 'alias';
    }
    
    /**
     * Получить данные псевдонима по id
     * @param int $id
     * @return array|null 
     */
    public function get($id)
    {
        return $this->db->get_where($this->table, array('id' => (int)$id))->row_array();
    }

    /**
     * Получить реальный адрес по синониму
     * @param string $path
     * @return string|null 
     */ 
    public function get_by_path($path_string)
    {
        $q = $this->db->get_where($this->table, array('alias' => $path_string))->row_array();

        return $q ? $q['realpath'] : NULL;
    }

    /**
     * Создать синоним
     * @param array $data - данные синонима
     * @return int 
     */
    public function create($data)
    {
        // Преобразование и очистка от недопустимых символов
        $data['alias'] = $this->converting($data['alias']);
        // Проверка алиаса на дубликат
        $data['alias'] = $this->check_duplicate($data['alias']);        
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Обновить синоним
     * @param int $alias_id - id синонима
     * @param array $pathdata - данные
     */
    public function update($alias_id, $pathdata)
    {
        // Преобразование и очистка от недопустимых символов
        $pathdata['alias'] = $this->converting($pathdata['alias']);
        // Проверка алиаса на дубликат
        $pathdata['alias'] = $this->check_duplicate($pathdata['alias'], $alias_id);
        
        $this->db->update($this->table, $pathdata, array('id' => (int)$alias_id));
    }

    /** 
     * Удалить url синоним
     * @param int $alias_id - id синонима 
     */
    public function delete($alias_id)
    {
        $this->db->delete($this->table, array('id' => $alias_id));
    }
    
    /**
     * Очистка алиаса от недопустимых символов 
     * и преобразование к допустимому виду
     * @param array $alias - кусочки синонима
     * @return string 
     */
    private function converting($alias)
    {
        $valid_alias = array();
        $this->load->helper('text');

        foreach ($alias as $a) {

            $valid_alias[] = url_title(convert_accented_characters(str_replace('/', '', $a)), 'dash', TRUE);
        }
        
        return implode('/', $valid_alias);
    }
    
    private function check_duplicate($alias, $id = 0)
    {
        $unique = $alias;
        $suffix = 2;
        
        while ( $this->db->get_where($this->table, array('alias' => $unique, 'id <>' => $id))->row() )
        {
            $unique = $alias . '-' . $suffix;
            $suffix++;
        }
        
        return $unique;
    }
}
