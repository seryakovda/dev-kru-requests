<?php
namespace forms\inputEditVariable;

class Control extends \forms\FormsControl
{

    function __construct()
    {
        parent::__construct();
    }

    public function defineFilterParent($filterParent = false,$fieldName = false,$value = false)
    {
        $this->filterParent = $filterParent;
        $this->filterPatentFieldName = $fieldName;
        $this->filterParentValue = $value;
    }
    private $route;
    private $className;
    private $methodName;
    private $varName;
    private $oldValue;
    private $callFunction;
    private $functionRefresh;
    private $Properties;
    private $massage;
    private $pattern;
    private $placeholder;
    private $AllInsertOff;
    private $_REQUEST_Array;

    public function run()
    {
        $this->_REQUEST_Array = Array();
        if ($_SERVER['REQUEST_METHOD']=="GET"){
            $this->_REQUEST_Array = $_GET;

        }else {
            $this->_REQUEST_Array = $_POST;
        }
        $this->route = empty($this->_REQUEST_Array["r1"])?"defaultMethod":$this->_REQUEST_Array["r1"];
        $this->className = empty($this->_REQUEST_Array["className"]) ? "" : $this->_REQUEST_Array["className"];
        $this->methodName = empty($this->_REQUEST_Array["methodName"]) ? "" : $this->_REQUEST_Array["methodName"];
        $this->varName = empty($this->_REQUEST_Array["varName"]) ? "" : $this->_REQUEST_Array["varName"];
        $this->oldValue = empty($this->_REQUEST_Array["oldValue"]) ? "" : $this->_REQUEST_Array["oldValue"];
        $this->callFunction = empty($this->_REQUEST_Array["callFunction"]) ? "" : $this->_REQUEST_Array["callFunction"];
        $this->functionRefresh = empty($this->_REQUEST_Array["functionRefresh"]) ? "" : $this->_REQUEST_Array["functionRefresh"];
        $this->massage = empty($this->_REQUEST_Array["message"]) ? "" : $this->_REQUEST_Array["message"];
        $this->pattern = empty($this->_REQUEST_Array["pattern"]) ? false : $this->_REQUEST_Array["pattern"];
        $this->placeholder = empty($this->_REQUEST_Array["placeholder"]) ? false : $this->_REQUEST_Array["placeholder"];
        $this->AllInsertOff = empty($this->_REQUEST_Array["AllInsertOff"]) ? true : false;
        if (method_exists ($this,$this->route)){
            $runMethod=$this->route;
            $this->$runMethod();
        }else {
            $this->defaultMethod();
        };

    }


    public function defaultMethod()
    {

    }

    /**
     * message
     * callFunction
     */
    private function yesOrNotButtons()
    {
        $window = new \views\elements\Window\Window();
        $elements = new \views\elements\VElements();
        $text = new \views\elements\MyText\MyText();
        $button = new \views\elements\Button\Button();

        $mainMessage = $text// заголовок с названием изменяемого
        ->text($this->massage)
            ->class_("textColorBlack")
            ->topLeft(10,20)
            ->position("absolute")->borderOff()->fontSizeBig()
            ->get();

        $closeBottom = $button//кнопка отмена
        ->set("Нет")
            ->topLeft(150,90)->height(30)->width(200)->position("absolute")->func("closeBlockAPP()")
            ->get();

        $yesBottom = $button//кнопка Да
        ->set("Да")
            ->topLeft(150,300)->height(30)->width(200)->position("absolute")
            ->func("HTML_yesOrNotButton()")
            ->get();

        $windows = $window
            ->set()->titleSmall("Внимание !!!")
            ->top(0)->left(0)->height(240)->width(600)->sizeHead($window::sizeHeadWinSmall)
            ->content($mainMessage.$closeBottom.$yesBottom )
            ->get();

        $HTTP = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:447px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)
            ->getHTTPTag();
        include "HTML_yesOrNotButton.php";
    }
    private function messageReadeOnly()
    {
        $window = new \views\elements\Window\Window();
        $elements = new \views\elements\VElements();
        $text = new \views\elements\MyText\MyText();
        $button = new \views\elements\Button\Button();
        $mainMessage = $text// заголовок с названием изменяемого
        ->text($this->massage)
            ->class_("textColorBlack")
            ->topLeft(10,20)
            ->position("absolute")->borderOff()->fontSizeBig()
            ->get();

        $closeBottom = $button//кнопка отмена
        ->set("ОК")
            ->topLeft(150,195)->height(30)->width(200)->position("absolute")->func("closeBlockAPP()")
            ->get();

        $windows = $window
            ->set()->titleSmall("Внимание !!!")
            ->top(0)->left(0)->height(240)->width(600)->sizeHead($window::sizeHeadWinSmall)
            ->content($mainMessage.$closeBottom )
            ->get();

        $HTTP = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:447px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)->getHTTPTag();
        print "<code> $HTTP </code>";
    }

    /**
     * callFunction вункция JS которая будет вызвана при нажатии [выбрать]
     * ===========calss============== НЕ!!!!! className!!!!!  содержи полный путь между forms  и control.php (SPR\Memu SPR\Street)
     * AllInsertOff по умолчанию если не присутствует то false если присутствует то true
     */
    private function executeCatalog()
    {
        $className = empty($this->_REQUEST_Array["class"])?"":$this->_REQUEST_Array["class"];

        $class="\\forms\\$className\\Control";
        $run= new $class;
        $run->setAllInsertOff($this->AllInsertOff);
        $run->formForSelect($this->_REQUEST_Array['callFunction']);
    }

    /**
     *  message
     *  oldValue
     *  callFunction
     *  pattern (шаблон(маска) ввода реализованый на JQuery)
     *  placeholder (Объек,функия ля привязки к шаблону(маски) ввода)
     *                        -pattern-    ---placeholder------
     * jQuery('.money').mask('## ##0.00'   , { reverse: true, }     );
     */
    private function editVariable()
    {
        $window = new \views\elements\Window\Window();
        $elements = new \views\elements\VElements();
        $text = new \views\elements\MyText\MyText();
        $button = new \views\elements\Button\Button();
        $input = new \views\elements\Input\Input();

        $mainMessage = $text
            ->text($this->massage)
            ->class_("textColorBlack")
            ->topLeft(10,20)->position("absolute")->borderOff()->fontSizeBig()
            ->get();
        $inputText = $input
            ->set("")
            ->top(70)->left(20)->position("absolute")
            ->width(550)
            ->NameId("inputEditValue")
            ->value($this->oldValue)
            //->functionOnKeyUp("functionKeyUp(this)")
//            ->pattern()
            ->get();
        $closeBottom = $button
            ->set("Отмена")
            ->topLeft(150,155)->height(30)->width(200)->position("absolute")->func("closeBlockAPP()")
            ->get();
        $appleyBottom = $button
            ->set("OK")
            ->topLeft(150,365)->height(30)->width(200)->position("absolute")->func("saveDate()")// нужно указать функцию которая запустит метод сохранения
            ->get();
        $windows = $window
            ->set()->titleSmall("Редактирование")
            ->top(0)->left(0)->height(240)->width(600)->sizeHead($window::sizeHeadWinSmall)
            ->content($mainMessage . $inputText . $closeBottom . $appleyBottom)
            ->get();
        $HTTP = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:447px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)->getHTTPTag();

        include ("HTML_editValue.php");
    }

    private function appendValue()
    {
        $window = new MyWindow();
        $elements = new VElements();
        $text = new MyText();
        $button = new MyButton();
        $input = new MyInput();

        $mainMessage = $text// заголовок с названием изменяемого
        ->text("Введите значение")
            ->class_("textColorBlack")
            ->topLeft(10,20)->position("absolute")->borderOff()->fontSizeBig()
            ->get();
//            $titleMessage = $text ->text($this->oldValue)->class_("textColorBlack")->topLeft(70,50)->position("absolute")->border($text::borderOff)->get();
        $inputText = $input//ввод нового значения с заголовком старого
        ->input($this->oldValue)
            ->top(70)->left(50)->position("absolute")->width(450)->NameId("inputAppendValue")
            ->get();
        $closeBottom = $button//кнопка отмена
        ->button("Отмена")
            ->topLeft(150,155)->height(30)->width(200)->position("absolute")->func("closeBlockAPP()")
            ->get();
        $appleyBottom = $button// кнопка применения изменения
        ->button("OK")
            ->topLeft(150,365)->height(30)->width(200)->position("absolute")
            ->func("appendValue()")// нужно указать функцию которая запустит метод добавления
            ->get();
        $windows = $window
            ->window()->titleSmall("Редактирование")
            ->top(0)->left(0)->height(240)->width(600)->sizeHead($window::sizeHeadWinSmall)
            ->content($mainMessage . $inputText . $closeBottom . $appleyBottom)
            ->get();
        $HTTP = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("top:0px;left:0px;width:447px;height:465px ")// для прорисовки сообщения по центру экрана
            ->setCaption($windows)->getHTTPTag();
        print "<style> </style>";
        print "<code> $HTTP </code>";
        print "<script>";
        print "function appendValue()";
        print "{";
        print 'varVal=$("#inputAppendValue").val();';
        print "$this->callFunction('$this->oldValue',varVal);";
        print "closeBlockAPP();";
        print "    }";
        print "</script>";

    }
}

?>