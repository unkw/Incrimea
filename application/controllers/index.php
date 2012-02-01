<?php
class Index extends MX_Controller {

    // Приставка для методов, предназначенных для отображения
    private $pre = 'action_';

    private $autoload_modules = array('user', 'metatags', 'path');
    
    function __construct()
    {
        // Автоподгрузка модулей
        foreach ($this->autoload_modules as $m)
            $this->common->load_module($m);

        $this->session_update();
    }

    /** Точка входа на сайт */
    function index()
    {
        // Роутер
        $this->load_page_by_path();
        // Отображение страницы
        if (!$this->input->is_ajax_request())
            $this->theme->render();
    }

    /** Определяем страницу какой сущности отобразить */
    function load_page_by_path()
    {
        $this->theme->set_breadcrumb('Главная', '');

        if ($this->uri->segment(1))
            if ($this->is_admin_url())
                if ($this->auth->is_admin())
                    $this->load_admin_page();
                else
                    redirect('user/login');
            else
                $this->load_module_page();
        else
            $this->load_main_page();
    }

    /** Отображение главной страницы */
    function load_main_page()
    {
        $this->theme->setVar('is_front', TRUE);

        $this->common->load_module('filter');
        // Контент
        $this->filter->action_index();
    }

    /** Отображение административных страниц */
    function load_admin_page()
    {
        // Библиотека сообщений системы
        $this->load->library('message');
        // Шаблон админ. панели
        $this->theme->tpl = 'theme/backend.php';
        // Выключить метатеги для страниц админки
        $this->theme->metatags_disable = TRUE;
        // "Хлебная крошка" на главную страницу админ. панели
        $this->theme->set_breadcrumb('Админ. панель', 'admin');

        $path = array_splice($this->uri->segment_array(), 1);
        
        // Модуль
        $module = isset($path[0]) ? $path[0] : 'admin';

        // Метод
        $method = $this->pre . (isset($path[1]) ? $path[1] : 'index');

        // Аргументы
        $params = array_splice($path, 2);

        $this->common->load_controller($module, 'admin');

        // Добавляем хлебные крошки
//        $this->theme->setBreadcrumb('');
        
        if (method_exists($this->admin, $method))
            call_user_func_array(array($this->admin, $method), $params);
        else
            show_404();
    }

    /** Отображение страниц модулей */
    function load_module_page()
    {
        // Получаем реальный адрес, если задан синином пути
        $path = $this->path->get_real_path();

        // Модуль
        $module = $path[0];
        $this->common->load_module($module);

        // Метод
        $method = $this->pre . (isset($path[1]) ? $path[1] : 'index');
        
        // Аргументы
        $params = array_splice($path, 2);

        if (method_exists($this->$module, $method))
            call_user_func_array(array($this->$module, $method), $params);
        else
            show_404();
    }

    /**
     * Определяет является ли путь путем в административную панель
     * @return bool 
     */
    function is_admin_url()
    {
        return $this->uri->segment(1) == 'admin' ? TRUE : FALSE;
    }

    /** Обновление сессий */
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