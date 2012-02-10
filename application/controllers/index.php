<?php
class Index extends MX_Controller {

    /** Action prefix */
    private $pre = 'action_';

    /** Autoloaded modules */
    private $autoload_modules = array('user', 'metatags', 'path');
    
    function __construct()
    {
        // Автоподгрузка модулей
        foreach ($this->autoload_modules as $m)
            $this->common->load_module($m);

        // Обновление сессии
        $this->session_update();
    }

    /** Point of entry to the site */
    function index()
    {
        // Роутер
        $this->load_page_by_path();
        // Отображение страницы
        if (!$this->input->is_ajax_request())
            $this->theme->render();
    }

    /** Router action */
    function load_page_by_path()
    {
        $this->theme->set_breadcrumb('Главная', '');
        
        if ($this->uri->segment(1))
            if ( $this->path->is_admin_url() )
                if ($this->auth->is_admin())
                    $this->load_admin_page();
                else
                    redirect('user/login');
            else
                $this->load_module_page();
        else
            $this->load_main_page();
    }

    /** Display home page */
    function load_main_page()
    {
        $this->theme->setVar('is_front', TRUE);

        $this->common->load_module('filter');
        // Контент
        $this->filter->action_index();
    }

    /** Display administration pages */
    function load_admin_page()
    {
        // Library system messages
        $this->load->library('message');
        // Template admin panel
        $this->theme->tpl = 'theme/backend.php';
        // Disable metatags for admin pages
        $this->theme->metatags_disable = TRUE;
        // Set breadcrumb to main admin panel page
        $this->theme->set_breadcrumb('Админ. панель', 'admin');

        $path = array_splice($this->uri->segment_array(), 1);
        // Module
        $module = isset($path[0]) ? $path[0] : 'admin';
        // Method
        $method = $this->pre . (isset($path[1]) ? $path[1] : 'index');
        // Arguments
        $params = array_splice($path, 2);
        
        $this->common->load_controller($module, 'admin');
        if (method_exists($this->admin, $method))
            call_user_func_array(array($this->admin, $method), $params);
        else
            show_404();
    }

    /** Display module pages */
    function load_module_page()
    {
        // Get real path
        $path = $this->path->get_real_path();
        // Module
        $module = $path[0];
        $this->common->load_module($module);
        // Method
        $method = $this->pre . (isset($path[1]) ? $path[1] : 'index');
        // Arguments
        $params = array_splice($path, 2);

        if (method_exists($this->$module, $method))
            call_user_func_array(array($this->$module, $method), $params);
        else
            show_404();
    }

    /** Update session */
    function session_update()
    {
        if ($this->session->userdata('last_activity') < time() - 300)
        {
            $this->session->set_userdata('last_activity', time());

            $this->auth->update_activity();
        }
    }
}
?>