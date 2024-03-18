<?php

namespace forms\skeletonApp;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
    }

    public function defaultMethod(){
        $this->VIEW->skeletonApplication();
    }
    public function logOut()
    {
        $this->MODEL->logOut();
    }
}
