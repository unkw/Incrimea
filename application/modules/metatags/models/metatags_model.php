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
        if ( ! $this->db->get_where($this->table, array('id' => (int)$meta_id))->row_array() )
            return FALSE;

        return $this->db->update($this->table, $metadata, array('id' => (int)$meta_id));
    }

    /**
     * Получить список пользователей
     * @param int $num - кол-во получаемых пользователей
     * @param int $offset - позиция в таблице бд
     * @return array
     */
    public function get_users($num, $offset)
    {

        $this->db
            ->select('u.*, r.name as role')
            ->from('users u')
            ->join('roles r', 'u.role_id = r.id')
            ->limit($num, $offset);

        $query = $this->db->get();

        return $query->result_array();
    }

    /** Получить кол-во всех пользователей */
    public function get_count_all()
    {
        return $this->db->count_all('users');
    }
    /** Добавление нового пользователя */
    public function add_user($data)
    {
        $this->db->insert_batch('users', array($data));
    }

    /** Получить данные пользователя по ID */
    public function get_user($uid)
    {
        $q = $this->db->get_where('users', array('id' => $uid));

        return $q->row_array();
    }

    /** Редактирование пользователя */
    public function update_user($uid, $data)
    {
        $this->db->where('id', $uid)
            ->update('users', $data);
    }

    /** Удаление пользователя */
    public function delete_user($uid)
    {
        // Главного админа удалить нельзя :)
        if ($uid == 1)
            return FALSE;

        $this->db->delete('users', array('id' => $uid));
    }
}