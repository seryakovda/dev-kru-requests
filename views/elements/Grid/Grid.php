<?php

namespace views\elements\Grid;
class Grid
{

    public $GridHead = array();
    public $GridId;
    public $columnId;
    private $row;
    private $allInsertOff;
    private $typeDataArray;
    private $fieldName;
    private $width;
    private $checked;
    private $onClickFunctionForAllTable;
    private $onDblClickFunctionForAllTable;

    const horizontalPosLeft="left";
    const horizontalPosCenter="center";
    const horizontalPosRight="right";

    static $typeDataArrayObject="Object";
    static $typeDataArrayArray="Array";

    public function GNew($GridId)
    {
        $this->onClickFunctionForAllTable = false;
        $this->onDblClickFunctionForAllTable = false;
        $this->checked = false;
        $this->GridHead = array();
        $this->GridId = $GridId;
        $this->row= 8;
        $this->allInsertOff=true;
        $this->typeDataArray=$this::$typeDataArrayObject;
        $this->width = false;

        return $this;
    }


    public function setonDblClickFunctionForAllTable($onDblClickFunctionForAllTable)
    {
        $this->onDblClickFunctionForAllTable = $onDblClickFunctionForAllTable;
        return $this;
    }

    public function setOnClickFunctionForAllTable($onClickFunctionForAllTable)
    {
        $this->onClickFunctionForAllTable = $onClickFunctionForAllTable;
        return $this;
    }

    public function typeDataArray()
    {
        $this->typeDataArray=$this::$typeDataArrayArray;
        return $this;
    }
    public function ColumnID($columnId)
    {
        $this->columnId = $columnId;
        return $this;
    }

    public function checked($checked = "checked")
    {
        $this->checked = $checked;
        return $this;
    }
    /**
     * width ширина колонки
     * input ячейка содержит элемент ввода (не просто текст)
     */
    public function Column($fieldName)
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    public function Column_Width($value)
    {
        $this->GridHead[$this->fieldName]["width"] = $value;
        return $this;
    }

    public function Column_Caption($value)
    {
        $this->GridHead[$this->fieldName]["caption"] = $value;
        return $this;
    }

    public function Column_TypeInput()
    {
        $this->GridHead[$this->fieldName]["element"] = "input";
        return $this;
    }
    /*
    const horizontalPosLeft="left";
    const horizontalPosCenter="center";
    const horizontalPosRight="right";
    */
    public function Column_horizontalPosLeft()
    {
        $this->GridHead[$this->fieldName]["horizontalPos"] = self::horizontalPosLeft;
        return $this;
    }

    public function Column_horizontalPosCenter()
    {
        $this->GridHead[$this->fieldName]["horizontalPos"] = self::horizontalPosCenter;
        return $this;
    }

    public function Column_horizontalPosRight()
    {
        $this->GridHead[$this->fieldName]["horizontalPos"] = self::horizontalPosRight;
        return $this;
    }

    public function Column_number_format($number_format = 2)
    {
        $this->GridHead[$this->fieldName]["number_format"] = $number_format;
        return $this;
    }

    public function row($value)
    {
        $this->row= $value;
        return $this;
    }

    public function allInsertOff()
    {
        $this->allInsertOff=false;
        return $this;
    }

    public function my_array_key_exists($key, $array,$ret="")
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return $ret;
        }
    }
    public function width($width)
    {
        $this->width= $width;
        return $this;
    }

    private function transformWidthColumn()
    {
        $widthAllColumn = 0;
        foreach ($this->GridHead as $key => $value) {
            $widthAllColumn = $widthAllColumn + $this->my_array_key_exists('width', $value);
        }
        if ($this->width-95<$widthAllColumn){
            $coefficient = ($this->width - 95) / $widthAllColumn;
            foreach ($this->GridHead as $key => $value) {
                $this->GridHead[$key]['width'] = round($this->my_array_key_exists('width', $value) * $coefficient );
            }
        }
    }

    /**
     * @param $condition
     * @param $hexColor
     * @return $this
       ->dynamicBackgroundColor('$ROW["totalPayments"]==$ROW["fiscal"]','#2aa53370')
       ->dynamicBackgroundColor('(($ROW["totalPayments"]!=$ROW["fiscal"]) and ($ROW["fiscal"]!=0))','#a52a4c70')
     */
    public function dynamicBackgroundColor($condition,$hexColor)
    {
        $arr  = array('condition'=>$condition,'hexColor'=>$hexColor);
        $this->GridHead[$this->fieldName]["dynamicBackgroundColor"][] = $arr;
        return $this;
    }

    private function dynamicBackgroundColor_processing($ROW,$condition_array)
    {
        $hexColor = false;
        foreach ($condition_array as $key => $value) {
            $condition = $value['condition'];
            $res = false;
            $condition = '$res = '.$condition.';';
            eval($condition);
            if ($res) $hexColor = $value['hexColor'];
        }
        return $hexColor;
    }

    public function GetTable($data)
    {
        $colRowInData = 0;
        $mainArray = array();
        $element = new \views\elements\VElements();


        if ($this->typeDataArray==$this::$typeDataArrayObject){
            while($row = $data->fetch()){
                $colRowInData = $colRowInData +1;
                $mainArray[] = $row;
            }
        }else{
            $colRowInData = count ($data);
            $mainArray = $data;
        }
        if ($colRowInData == 0){
            $allWidth = 0;
            foreach ($this->GridHead as $key => $value) { // перебираем все элементы шапки
                $width = $this->my_array_key_exists('width', $value); // ширина
                $allWidth = $allWidth + $width+1 ; //увеличить ширину таблиы на ширину ячеки и пикселя
            }
            $headGrid = $element->tag("th") // блок с крыжиком который выделяет все строки либо пустая ячейка
            ->setStyle("width:20px")
                ->setStyle("padding-left:0px")
                ->setStyle("border-top-style: none;")
                ->setStyle("border-left-style: none;")
                ->setClass("BorderGrid_td")->getHTTPTag();

            $headGrid=$headGrid.$element->tag("th") // элемент за полосу прокрутки
                ->setStyle("width:17px")
                    ->setStyle("padding-left:0px")

                    //->setClass("BorderGrid_td")
                    ->getHTTPTag();
            $headGrid=$element->tag('tr')// строка заголовка таблицы
            ->setStyle("display:table;width:".($allWidth+49)."px;table-layout:fixed;")
                ->setStyle("height: 30px;")
                ->getHTTPTag();
            $headGrid0=$element->tag("thead")->setCaption($headGrid)->getHTTPTag();


            $returnHTML=$element->tag("table")

                ->setStyle("border-spacing:0px")
                ->setStyle("border-collapse: collapse")
                ->setStyle("margin:5px")
                ->setId($this->GridId)
                ->setClass("BorderGrid test")

                ->setCaption($headGrid0)
                ->getHTTPTag();
            return $returnHTML;
        }

        if ($this->width){
            $this->transformWidthColumn();
        }
        $allWidth = 0;//Вся ширина таблицы
        $allTop = 0;
        $inp ="";
        if ($this->allInsertOff){ //кнопику которая выделяет все строки грида
            $func="$('#".$this->GridId."').find('.".$this->GridId."').prop('checked', this.checked);";
            $inp = $element->tag("input")->setFunction('type="checkbox"')->setFunction('onclick="'.$func.'"')
                ->getHTTPTag();
        }

        $headGrid = $element->tag("th") // блок с крыжиком который выделяет все строки либо пустая ячейка
            ->setStyle("width:20px")
            ->setStyle("padding-left:0px")
            ->setStyle("border-top-style: none;")
            ->setStyle("border-left-style: none;")
            ->setClass("BorderGrid_td")->setCaption($inp)->getHTTPTag();

        $allWidth = $allWidth +48; //общую ширину увеличиываем на 2 блока (первый 20 пикселей который выделяет все строи и второй 21 пиксель полоса прокрутки)

        foreach ($this->GridHead as $key => $value) { // перебираем все элементы шапки

            $width = $this->my_array_key_exists('width', $value)-5; //отнят padding-left: 5px
            $headGrid=$headGrid.$element->tag("th")
                    ->setStyle("width:".($width)."px") // ширина
                    ->setStyle("overflow:hidden;")   // непоказывать выступающие элементы
                    ->setStyle("text-align: center;")
                    ->setStyle("border-top-style: none;")
                    ->setClass("BorderGrid_td")
                    ->setCaption($this->my_array_key_exists('caption', $value))->getHTTPTag();

            $allWidth = $allWidth + $width+6 ; //увеличить ширину таблиы на ширину ячеки и пикселя
        }
        $headGrid=$headGrid.$element->tag("th") // элемент за полосу прокрутки
                ->setStyle("width:17px")
                ->setStyle("padding-left:0px")

                //->setClass("BorderGrid_td")
                ->getHTTPTag();
        $headGrid=$element->tag('tr')// строка заголовка таблицы
            ->setStyle("display:table;width:".($allWidth+2)."px;table-layout:fixed;")
            ->setStyle("height: 30px;")

            ->setCaption($headGrid)->getHTTPTag();

        $bodyGrid = "";
        $factRows=0;

        foreach ($mainArray as $mainKey => $ROW)  {
            $factRows++;
            $id=$ROW[$this->columnId];
            $id = " ".$id." ";
            $id = preg_replace("/\s+/", "", $id);
            $nameForidInput = $id;
            $id = str_replace("\\","_" , $id);

          //  $allWidth = 0;
            $srtoka = "";
            $tableGrid="";

            $func="";
            if (!$this->allInsertOff){
                $func="$('#".$this->GridId."').find('.".$this->GridId."').prop('checked', false);";
                $checkFlag="true";
            }else{
                $checkFlag = $func."!$('.$this->GridId.$id').prop('checked')";
            }
            //$func = $func."$('.".$this->GridId.".".$id."').prop('checked',true)";
            $func = $func."$('.$this->GridId.$id').prop('checked',$checkFlag)";

            $element->tag("input") // элемент управления строкой input который помечает выбраную строку
                ->setClass($this->GridId)
                ->setClass($id)
                ->setFunction('type="checkbox"')
                ->setFunction('readonly="readonly"')
                ->setFunction('onclick="'.$func.'"')
                ->setFunction("name='".$nameForidInput."'");
            if ($this->checked){
                if ($valCheck = $this->my_array_key_exists($this->checked, $ROW, false)){
                    if ($valCheck != 0) $element->setFunction(" checked ");
                }
            }
            $inp0 = $element->getHTTPTag();

            $tableGrid = $tableGrid.$element->tag("td") // оборачиваем элемент управления в ячейку таицы
                    ->setStyle("width:20px;") //ширина 18 пикселей
                    ->setStyle("padding-left:0px")
                    ->setStyle("border-left-style: none;")
                    ->setClass("id")
                    ->setClass("BorderGrid_td")
                    ->setCaption($inp0)->getHTTPTag();
            //$allWidth = $allWidth + 50;
            $num_kolumn=1;
            foreach ($this->GridHead as $headColName => $headCol) {
                $headColName = " ".$headColName." ";
                $headColName = preg_replace("/\s+/", "", $headColName);

                $width = $this->my_array_key_exists('width', $headCol)-5; //отнят padding-left: 5px
                if (array_key_exists($headColName, $ROW)){
                    $val = $ROW[$headColName];
                    if ( $number_format = $this->my_array_key_exists('number_format', $headCol)){
                        $val = number_format($val, $number_format, ',', ' ');
                    }

                    $tElement=$this->my_array_key_exists('element', $headCol);
                    if ($tElement=="input"){
                        $val=$element->tag("input")
                            ->setClass($this->GridId)
                            ->setClass($this->GridId."_".$num_kolumn."_".$factRows)
                            ->setClass("GridInput inpData")
                            ->setFunction("data-column='".$this->GridId."_".$num_kolumn."'")
                            ->setFunction("data-row='".$factRows."'")
                            ->setFunction("data-field='".$headColName."'")
                            ->setFunction('onkeyup="eventGreed(this,event)"')
                            ->setFunction("name='".$id."'")
                            ->setFunction("value='".$val."'")
                            ->getHTTPTag();
                    }
                }else{
                    $val = "NoField";
                }

                $element->tag("td")
                        ->setId($headColName)
                        ->setClass("BorderGrid_td")
                        ->setStyle("width:".$width."px")
                        ->setStyle("overflow:hidden; white-space: nowrap;")
                        ->setCaption($val);

                if ( is_array($dynamicBackgroundColor = $this->my_array_key_exists('dynamicBackgroundColor', $headCol))){
                    if ($hexColor = $this->dynamicBackgroundColor_processing($ROW,$dynamicBackgroundColor)){
                        $element->setStyle("background-color:$hexColor");
                    }
                }
                if ( $textAlign = $this->my_array_key_exists('horizontalPos', $headCol)){
                        $element->setStyle("text-align:$textAlign");
                }

                $tableGrid = $tableGrid.$element->getHTTPTag();
               // $allWidth = $allWidth + $width + 0;
                $num_kolumn = $num_kolumn + 1;
            }

            $tableGrid = $tableGrid.$element->tag("td")->setStyle("width:14px")->setCaption(" ")->getHTTPTag();
//

            $bodyGrid=$bodyGrid.$element->tag('tr')->setId($id)
                    ->setStyle("display:table;width:".($allWidth)."px;table-layout:fixed;height:20px;overflow:hidden;")
                    ->setClass("GridLine")
                    ->setFunction('onclick="'.$func.'"')
                    ->setCaption($tableGrid)->getHTTPTag();
        }
        $headGrid0=$element->tag("thead")->setCaption($headGrid)->getHTTPTag();
        $factRows=$factRows<$this->row?$factRows:$this->row;

        $bodyGrid0=$element->tag('tbody')
            ->setStyle("display: block;height:".((20*$factRows))."px;width: ".($allWidth+1)."px;overflow-x:hidden; overflow-y:auto;")
            ->setCaption($bodyGrid)->getHTTPTag();

        $returnHTML=$element->tag("table")
//            ->setStyle("width:".(100+(25))."px")
            ->setStyle("border-spacing:0px")
            ->setStyle("border-collapse: collapse")
            ->setStyle("margin:5px")
            ->setId($this->GridId)
            ->setClass("BorderGrid test")
            ->setCaption($headGrid0.$bodyGrid0);

        if ($this->onClickFunctionForAllTable !== false )$element->setFunction("onclick='{$this->onClickFunctionForAllTable}'");
        if ($this->onDblClickFunctionForAllTable !== false )$element->setFunction("ondblclick='{$this->onDblClickFunctionForAllTable}'");

        $returnHTML=$element->getHTTPTag();

//        print"<code>";
//        print "$returnHTML";
//        print"</code>";
        ob_start();
        require "HTML.php";
        $output=ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

