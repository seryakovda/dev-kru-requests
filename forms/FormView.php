<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 28.11.2019
 * Time: 15:46
 */

namespace forms;


abstract class FormView
{

    public $ParentObject;
    public $objectName;

    public $objectFullName;
    public $objectParentName;
    public $callFunction_txt;

    public $nameGreed;
    public $nameGreedDIV;
    public $nameEditDIV;
    public $allInsertOff;

    public $ELM;
    public $WND;
    public $BTN;

    public $TXT_headSmallTitle;
    public $TXT_headBigTitle;

    public $formWidth;

    public $windowContent;

    function __construct()
    {
        $this->initClass();
    }

    public function setCallFunctionTxt($callFunction_txt)
    {
        $this->callFunction_txt = $callFunction_txt;
    }

    public function initClass()
    {
        $this->TXT_headBigTitle="";
        $this->TXT_headSmallTitle="SmallHead";
    }

    public function printElement($HTML)
    {

        print "<code>";
        print $HTML;
        print "</code>";
        print "<script> function loadscript(){} </script>";
    }
    public function mainWindow()
    {
        $window = new \views\elements\Window\Window();
        $this->windowContent = $window->set()
            ->titleSmall($this->TXT_headSmallTitle)
            ->width($this->formWidth)
            ->content($this->windowContent)
            ->headSizeSmall()
            ->nameId($this->objectFullName)
            ->floatLeft()
            ->get();
    }
    public function autoCenterBlock()
    {
        $elements = new \views\elements\VElements();
        $this->windowContent = $elements->tag("div")->setClass("MsgBlockAPP")
        //->setStyle("width:{$this->formWidth}px;")// для прорисовки сообщения по центру экрана
            ->setStyle("top:0px;left:0px;width:{$this->formWidth}px;")// для прорисовки сообщения по центру экрана
        ->setCaption($this->windowContent)->getHTTPTag();
    }
    public function printEmptyLoadScript()
    {
        print "<script> function loadscript(){} </script>";
    }

}