<?php
namespace forms\skeletonApp;

class MODEL
{

    public function logOut()
    {
        $_SESSION["id_user"]=0;
    }
}