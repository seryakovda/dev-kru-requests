<?php

namespace views\elements\calendar;

class Calendar {
    private $idCalendar,$func,$caption,$year;
    function __construct()
    {
        $this->year=1900;
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }
    public function setId($val)
    {
        $this->idCalendar=$val;
        return $this;
    }

    public function setFunction($val)
    {
        $this->func=$val;
        return $this;
    }
    public function setCaption($val)
    {
        $this->caption=$val;
        return $this;
    }

    public function calendar()
    {
        $idCalendar=$this->idCalendar;
        $func=$this->func;
        $caption=$this->caption;

        $element=new \views\elements\VElements();
        $Button= new \views\elements\Button\Button();
        $inpYear=$element->tag("input")->setId("mainDateYear")->setClass("inputYear textFontSmall")->setFunction("value='".$_SESSION["id_month_0_year"]."'")->setFunction("disabled")->getHTTPTag();
        $inpMonth=$element->tag("input")->setId("mainDateMonth")->setClass("inputYear textFontSmall")->setFunction("value='".$_SESSION["id_month_0_name"]."'")->setFunction("disabled")->getHTTPTag();

//        $CalendarButton=$element->MyButton(0,0,35,250,"absolute","textFontSmall ",$caption." Февраль 2017","clickYear('".$idCalendar."')","ButtonCalendar");
        $CalendarButton = $Button->set($caption." ".$_SESSION["id_month_0_name"]." ".$_SESSION["id_month_0_year"])
            ->topLeft(0,0)
            ->height(35)->width(250)
            ->position("absolute")
            ->fontSmall()
            ->func("clickYear('".$idCalendar."')")->nameId("ButtonCalendar")->get();
        $inpYear2=$element->tag("input")->setId("secondaryDateYear")->setClass("inputYear  textFontNormal")->setFunction("value='$this->year'")->setFunction("disabled")->getHTTPTag();
        $inpYear2div=$element->tag("div")->setClass("textColorBlack")->setStyle("position:absolute; top:10px;left:110px;height:20px;width:70px")->setCaption($inpYear2)->getHTTPTag();


        $mes=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
        $HTMLButton="";
        $k=0;
        $HTMLButtonLeft=$Button->set("Назад")->topLeft(10,10)->height(20)->width(70)->position("absolute")->fontSmall()->func("minusYear('".$idCalendar."')")->nameId("ButtonMinus")->get();
        $HTMLButtonRight=$Button->set("Вперёд")->topLeft(10,174)->height(20)->width(70)->position("absolute")->fontSmall()->func("plusYear('".$idCalendar."')")->nameId("ButtonPlus")->get();

        $HTMLButton=$HTMLButton.$HTMLButtonLeft.$inpYear2div.$HTMLButtonRight;
        for ($i=0;$i<4;$i++){
            for ($j=0;$j<3;$j++)
            {
                $HTMLButton1=$Button->set($mes[$k])->
                topLeft(40+$i*30,10+$j*82)->
                height(20)->
                width(70)->
                position("absolute")->
                fontSmall()->
                func("clickMonth('".$idCalendar."',".$k.",'".$mes[$k]."','".$func."','".$caption."')")->nameId("Calendar")->get();
                $HTMLButton=$HTMLButton.$HTMLButton1;
                $k++;
            }
        }

        $bodyCalendar = $element->tag("div")->setId("BodyCalendar")->setClass("backgroundCalendar")->setCaption($HTMLButton)->getHTTPTag();
        $HTML =  $element->tag("div")->setId($idCalendar)->setCaption($CalendarButton.$bodyCalendar)->getHTTPTag();

        ob_start();
        require "HTTPcalendar.php";
        $output=ob_get_contents();
        ob_end_clean();
        return $output;    }

}