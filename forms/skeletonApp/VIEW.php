<?php
namespace forms\skeletonApp;

class VIEW extends \forms\FormView
{
    private $menuData;
    private $nameMonth="Месяц не определён";

    public function initClass()
    {
        $this->TXT_headSmallTitle="";
    }

    public function setNameMonth($val)
    {
        $this->nameMonth=$val;
        return $this;
    }
    public function setMenuDate($val)
    {
        $this->menuData=$val;
        return $this;
    }

    public function skeletonApplication()
    {
        $elements = new \views\elements\VElements();
        $BTN = new \views\elements\Button\Button();
        $width = 820;
        $height = 25;
        $HTML_BTN = "";
        $HTML_BTN = $HTML_BTN.$BTN->set("Список заявок")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("run('Request')")
                ->get();
        if ($_SESSION['userAdmin']==1){
            $width = ($width/4)-5;
            $HTML_BTN = $HTML_BTN.$BTN->set("Сотрудники")
                    ->width($width)->height($height)
                    ->func("run('Worker')")
                    ->floateLeft()
                    ->get();

            $HTML_BTN = $HTML_BTN.$BTN->set("Ресурсы")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->func("run('Resource')")
                    ->get();

            $HTML_BTN = $HTML_BTN.$BTN->set("Подразделения")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->func("run('Department')")
                    ->get();
            $HTML_BTN = $HTML_BTN.$BTN->set("Сетевые диски")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->func("run('NetDisk')")
                    ->get();

        }

        $image="";
        /*

        $Media= new \views\elements\Media\Media();
        $image=$Media->width(1080)->height(1080)
            ->image("forms/skeletonApp/img/instruction.jpg");
        */

        $mainContent=$elements
            ->tag("div")
            ->setId("mainContent")
            ->setClass("mainContent")
            ->setCaption($image)
            ->getHTTPTag();

        $heightBrowse = $_SESSION['heightBrowse'];

        $mainContent = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("width:1050px; ")// для прорисовки сообщения по центру экрана
//            ->setStyle("top:0px;left:0px;width:1050px;height:{$heightBrowse}px ")// для прорисовки сообщения по центру экрана
            ->setCaption($mainContent)->getHTTPTag();


        $fixHeadBlock=$elements->tag("div")->setId("fixHeadBlock")->setClass("fixHeadBlock")->setCaption($HTML_BTN)->getHTTPTag();
       // $fixLeftBlock=$elements->tag("div")->setId("fixLeftBlock")->setClass("fixLeftBlock")->setCaption("Меню")->getHTTPTag();

        $printHTTP =  $elements->tag("div")
            ->setId("frameApp")
            ->setCaption($mainContent.$fixHeadBlock)
            ->getHTTPTag();



        include("HTML.php");
    }
    public function logOut()
    {
        require "HTML_logOut.php";
    }
}