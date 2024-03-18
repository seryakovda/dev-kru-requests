<?php

namespace views\elements\Check;

class Check{
    private $caption;
    private $NameId;
    private $style;
    private $func;
    private $width;
    private $height;
    private $class;
    private $checked;
    private $borderOn;
    private $position;


    private $floatLeft;
    const floatLeftOff=0;
    const floatLeftOn=1;

    public function set($caption){
        $this->caption=$caption;
        $this->height = 19;
        $this->style ="";
        $this->class="";
        $this->func="";
        $this->NameId="checkbox";
        $this->checked='checked="checked"';
        $this->borderOn = "BorderAll";

        $this->floatLeft=$this::floatLeftOff;
        $this->position="relative";

        return $this;
    }
    public function floatLeft()
    {
        $this->floatLeft=$this::floatLeftOn;
        return $this;
    }

    public function borderOff()
    {
        $this->borderOn = "BorderOff";
        return $this;
    }
    public function style($val){
        $this->style=$this->style.$val.";";
        return $this;
    }
    public function top($val){
        $this->style=$this->style."top:".$val."px;";
        return $this;
    }
    public function left($val){
        $this->style=$this->style."left:".$val."px;";
        return $this;
    }
    public function height($val){
        $this->height = $val;
        return $this;
    }
    public function width($val){
        $this->width=$val;
        $this->style=$this->style."width:".$val."px;";
        return $this;
    }
    public function position($val){
        $this->position=$val;
        return $this;
    }
    public function class_($val){
        $this->class=$this->class." ".$val;
        return $this;
    }
    public function func($val){
        $this->func=$val;
        return $this;
    }
    public function checkedOff(){
        $this->checked='';
        return $this;
    }
    public function nameId($val){
        $this->NameId=$val;
        return $this;
    }
    public function get(){
        $element = new \views\elements\VElements();
        $this->style=$this->style."height:".$this->height."px;";

        $HTML = "";
        $HTML = $HTML . $element->tag("input")
                ->setFunction('onclick="'.$this->func.'"')
                ->setFunction($this->checked)
                ->setFunction('type="checkbox"')
                ->setId($this->NameId)
                ->setClass("checkbox")
                ->getHTTPTag();
        $HTML = $HTML . $element->tag("label")->setFunction('for="'.$this->NameId.'"')->setCaption($this->caption)->getHTTPTag();
        if ( $this->floatLeft==$this::floatLeftOn){
            $this->style=$this->style."display: inline-block; float:left ; ";
            $this->position = "static";
        }
        $this->style=$this->style."position:".$this->position.";";

        return $element->tag("div")
            ->setStyle("margin:5px; padding-top: 4px;padding-left: 3px;".$this->style)
            ->setClass($this->class)
            ->setClass($this->borderOn)
            ->setCaption($HTML)
            ->getHTTPTag(); //

    }
}
