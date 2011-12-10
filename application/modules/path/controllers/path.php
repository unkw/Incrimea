<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Path extends MX_Controller {
     
    private $module_name;
    
    function __construct()
    {
        $this->module_name = strtolower(get_class());
    }

    function get_real_path()
    {
        // 1st step: request to database
        //
        // 2nd step: checking an alias exists
        $path = explode('/', $this->uri->uri_string());
        return $path;
    }
    
}
