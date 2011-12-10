<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

    var $CI;
    /**
     * ID Ролей для доступа в админку
     * 1 - admin
     * 2 - editor
     */
    var $admin_roles = array(1, 2);

    public function __construct()
    {
        $this->CI = & get_instance();
        define('USER_AUTH_ID', $this->CI->session->userdata('uid') ? $this->CI->session->userdata('uid') : 0);
    }

    /**
     * Авторизация пользователя
     * @param string $login - username or email
     * @param string $pass - пароль
     * @return bool
     */
    function login($login, $pass){

        $query = $this->CI->db->query(''
            . 'SELECT * FROM users'
            . ' WHERE (username = ? OR email = ?) AND password = ?',
                array($login, $login, sha1($pass))
        );

        $userdata = $query->row();

        if ($userdata)
        {
            $this->CI->session->set_userdata(array(
                'uid' => $userdata->id,
            ));
            
            $this->update_activity($userdata->id);

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /** Залогинен ли пользователь */
    function loggin_in()
    {
        return $this->CI->session->userdata('uid') ? TRUE : FALSE;
    }

    /** Есть ли доступ в админку */
    function is_admin()
    {
        if ($this->loggin_in())
        {
            $this->CI->db
                ->select('role_id')
                ->from('users')
                ->where('id', $this->CI->session->userdata('uid'));

            $q = $this->CI->db->get()->row();

            return in_array($q->role_id, $this->admin_roles) ? TRUE : FALSE;
        }
        else
            return FALSE;
    }

    /** Обновление последнего доступа пользователя на сайт */
    function update_activity($uid = 0)
    {
        $uid = $uid ? $uid : USER_AUTH_ID;

        if ($uid)
            $this->CI->db->where('id', $uid)
                ->update('users', array('last_login' => time()));
    }
}

?>