<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 18.11.2019
 * Time: 12:15
 */

namespace forms;

abstract class FormsControl
{
    private $route;
    public $VIEW;
    public $MODEL;
    public $objectName;
    public $objectFullName;
    public $objectParentName;

    public $formWidth;
    public $formName;

    public $filterPatentFieldName;
    public $filterParentValue;
    public $filterParent;
    public $allInsertOff;

    public $superUserOnly;

    function __construct()
    {
        $this->defineObjectName();

    }

    public function run()
    {
        if (($this->superUserOnly) && ($_SESSION["superUser"]!=1)){
            \views\Views::MsgBlock("Внимание","Доступ заперщён");
            return;
        }

        if ($_SERVER['REQUEST_METHOD']=="GET"){
            $this->route = empty($_GET["r1"])?"defaultMethod":$_GET["r1"];
        }else {
            $this->route= empty($_POST["r1"])?"defaultMethod":$_POST["r1"];
        }
        if (method_exists ($this,$this->route)){
            $runMethod=$this->route;
            $this->$runMethod();
        }else {
            $this->defaultMethod();
        };

    }

    public function defaultMethod()
    {

        $this->setFormWidth(1050);
        $this->setTable("");

    }


    public function defineObjectName()
    {
        $this->objectFullName = get_called_class();
        $this->objectFullName = str_replace("forms\\","",$this->objectFullName);
        $this->objectFullName = str_replace("\\Control","",$this->objectFullName);
        $objectFullName = explode("\\",$this->objectFullName);
        $this->objectName = end($objectFullName);
        $this->objectParentName = str_replace("\\".$this->objectName,"",$this->objectFullName);
        $this->objectFullName = str_replace("\\","_",$this->objectFullName);
        $this->allInsertOff = true;

        $_SESSION["width_$this->objectFullName"] =   array_key_exists("width_$this->objectFullName",  $_SESSION) ? $_SESSION["width_$this->objectFullName"] : 1050;
        $this->formWidth = $_SESSION["width_$this->objectFullName"];
        $this->defineViewVariable();
        $this->defineModelVariable();
        $this->superUserOnly = false;
    }

    public function defineViewVariable()
    {
        if (is_object ($this->VIEW)){
            $this->VIEW->formWidth = $this->formWidth;
            $this->VIEW->objectParentName = $this->objectParentName;
            $this->VIEW->objectName = $this->objectName;
            $this->VIEW->allInsertOff = $this->allInsertOff;
            $this->VIEW->objectFullName = $this->objectFullName;
            $this->VIEW->nameGreed = $this->objectFullName."_greed";
            $this->VIEW->nameGreedDIV = $this->VIEW->nameGreed."_div";
            $this->VIEW->nameEditDIV = $this->objectFullName."_edit_div";
        }
    }
    public function defineModelVariable()
    {

        if (is_object ($this->MODEL)) {
            $_SESSION["table_$this->objectFullName"] = array_key_exists("table_$this->objectFullName", $_SESSION) ? $_SESSION["table_$this->objectFullName"] : "";
            $this->MODEL->table = $_SESSION["table_$this->objectFullName"];
        }
    }

    public function getData()
    {
       return $this->MODEL->getData();
    }
    /**
     * @return string Возвращает HTML вестку заголовка и табличной части справочника
     */
    public function getFormForParent()
    {
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->mainWindow();
        $ret = $this->VIEW->windowContent.$this->VIEW->includeHtmlFilter();
        return $ret ;
    }

    /**
     * @return string Возвращает HTML вестку только таблчной части
     */
    public function getGreedForParent()
    {
        $data = $this->MODEL->getData();
        return $this->VIEW->greed($data);
    }

    /**
     * Отвечает за фильтрацию табличной части и вывод на frontPage
     */
    public function getListFilter()
    {
        $filter = json_decode($_GET['filter'], true);
        $this->MODEL->setFilter($filter);
        $data = $this->MODEL->getData();
        $this->VIEW->printElement ($this->VIEW->Greed($data));
    }


    public function getIdMainGreed()
    {
        return $this->VIEW->nameGreed;
    }


    public function getTXT_headSmallTitle()
    {
        return $this->VIEW->TXT_headSmallTitle;
    }


    public function setFilter($field,$value,$znak="=")
    {
        $rrow = Array();
        $rrow['field'] =    $field;
        $rrow['value'] =    $value;
        $rrow['znak'] =     $znak;
        $this->MODEL->filter[] = $rrow;
    }

    public function setTable($table)
    {
        $this->MODEL->table = $table;
        $_SESSION["table_$this->objectFullName"] = $table;
    }

    public function setFormWidth($formWidth)
    {
        $this->VIEW->formWidth = $formWidth;
        $_SESSION["width_$this->objectFullName"] = $formWidth;
    }

    public function setAllInsertOff($allInsertOff)
    {
        $this->allInsertOff = $allInsertOff;
        $this->VIEW->allInsertOff = $this->allInsertOff;
    }


    /**
     * @param $callFunction_txt
     * // форма выбора для внешнего вызова в дальнейшем из HTML вызывается метод  для получения таблицы
     */
    public function formForSelect($callFunction_txt)
    {

        $data = $this->MODEL->getData();

        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->setCallFunctionTxt($callFunction_txt);
        $this->VIEW->createBottomWindowSelect();
        $this->VIEW->mainWindow();
        $this->VIEW->autoCenterBlock();
        $this->VIEW->printMainWindow();
    }

}