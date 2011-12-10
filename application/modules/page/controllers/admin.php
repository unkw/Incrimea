<?php

class Admin extends MX_Controller {

    function  __construct() {


    }

    function action_index()
    {
        $this->theme->setVar('title', 'Pages module');
        $this->theme->setVar('content', 'Administration PAGES module');      
    }

    function action_create()
    {
        $this->theme->setVar('page_title', 'Create new page');
        $this->theme->setVar('content', 'content is empty');
    }

    function action_edit($id = 0)
    {
        $this->theme->setVar('page_title', 'Edit page');
        $this->theme->setVar('content', 'content is empty ... ' . $id);
    }
}