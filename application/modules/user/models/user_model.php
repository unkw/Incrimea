<?php
class User_Model extends CI_Model {
    
    public function __construct() {

        parent::__construct();
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

    /** Получить список ролей для пользователей */
    public function get_roles()
    {
        $query = $this->db->get('roles');

        return $query->result_array();
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

    /** Проверка имеется ли пользователь с таким же именем */
    public function is_unique_username($name, $uid)
    {
        $uid = (int)$uid;

        $q = $this->db->query(
            'SELECT username FROM users'
            . ' WHERE username = ?' . ($uid ? ' AND id != ' . $uid : ''),
                array($name)
        );

        return !$q->row() ? TRUE : FALSE;
    }

    /** Проверка имеется ли пользователь с таким же email */
    public function is_unique_email($email, $uid)
    {
        $uid = (int)$uid;

        $q = $this->db->query(
            'SELECT username FROM users'
            . ' WHERE email = ?' . ($uid ? ' AND id != ' . $uid : ''),
                array($email)
        );

        return !$q->row() ? TRUE : FALSE;
    }
}