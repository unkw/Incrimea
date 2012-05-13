<?php

class Widget {
    
    private static $name;

    function run($name) {

        self::$name = $name;

        $args = func_get_args();

        require_once APPPATH.'widgets/'.$name.'/index'.EXT;
        $name = ucfirst($name);

        $widget = new $name();
        return call_user_func_array(array($widget, 'run'), array_slice($args, 1));    
    }

    function render($data = array()) {

        extract($data);
        include APPPATH.'widgets/'.self::$name.'/view'.EXT;
    }

    function load($object) {
        
        $this->$object =& load_class(ucfirst($object));
    }

    function __get($var) {

        static $ci;
        isset($ci) OR $ci = get_instance();
        return $ci->$var;
    }
}