<?php

namespace views\elements\Input;

class Input {
    private $caption;
    private $startFont;
    private $NameId;
    private $password;
    private $style;
    private $pattern;
    private $value;
    private $className;
    private $floatLeft;
    private $position;
    const floatLeftOff=0;
    const floatLeftOn=1;
    //private $functionOnKeyUp;

    public function set($caption){
        $this->caption=$caption;
        $this->startFont="Large";
        $this->password=false;
        $this->style ="";
        $this->pattern="";
        $this->value="";
        $this->style="height:40px;";
        //$this->functionOnKeyUp=false;
        $this->className='';
        $this->position = 'relative';
        return $this;
    }
    public function positionRelative()
    {
        $this->position = "relative";
        return $this;
    }
    public function positionAbsolute()
    {
        $this->position = "absolute";
        return $this;
    }
    public function position($val)
    {
        $this->position = $val;
        return $this;
    }

    public function value($val){
        $this->value = $val;
        return $this;
    }
    public function className($className)
    {
        $this->className = $this->className. ' '. $className;
        return $this;
    }
    public function pattern($val){
        $this->pattern=$val;
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
        return $this;
    }
    public function width($val){
        $this->style=$this->style."width:".$val."px;";
        return $this;
    }
    public function startFont($val){
        $this->startFont=$val;
        return $this;
    }
    public function NameId($val){
        $this->NameId=$val;
        return $this;
    }
    public function password(){
        $this->password=true;
        return $this;
    }
    public function floatLeft()
    {
        $this->floatLeft = $this::floatLeftOn;
        return $this;
    }

        /*
        public function functionOnKeyUp($functionName)
        {
            $this->functionOnKeyUp=$functionName;
            return $this;

        }*/

    public function get(){
        $element = new \views\elements\VElements;

        $this->style=$this->style."position:".$this->position.";";

        $startFont= $element::FontBig;
        $calssStartFont = "textFontBig";

        if ($this->startFont=="Large"){
            $startFont= $element::FontBig;
            $calssStartFont = "textFontBig";
        }
        if ($this->startFont=="Middle"){
            $startFont= $element::FontNormal;
            $calssStartFont = "textFontNormal";
        }
        if ( $this->floatLeft==$this::floatLeftOn){
            $this->style=$this->style."display: inline-block; float:left ; ";
        }

        $pass=$this->password?'type="password"':'type="text"';
        $func_onclick='onclick="$(\'#Title'.$this->NameId.'\').css(\'font-size\',\''.$element::FontSmall.'px\'); 
                                $(\'#Title'.$this->NameId.'\').css(\'margin-top\',\'0px\'); 
                                $(\'#'.$this->NameId.'\').focus();"';
        $func_onBlur='onBlur=" if (this.value==\'\'){
        $(\'#Title'.$this->NameId.'\').css(\'font-size\',\''.$startFont.'px\'); 
        $(\'#Title'.$this->NameId.'\').css(\'margin-top\',\''.$element::FontSmall.'px\');}"';
        $func_onfocus='onfocus="
        $(\'#Title'.$this->NameId.'\').css(\'font-size\',\''.$element::FontSmall.'px\') 
        $(\'#Title'.$this->NameId.'\').css(\'margin-top\',\'0px\'); "';

        $HTTPP=$element->tag("p")
            ->setId("Title".$this->NameId )
            ->setClass($calssStartFont)
            ->setCaption($this->caption)
            ->getHTTPTag();

        $element->tag("input")
            ->setId($this->NameId)
            ->setClass("MyInput");
            //->setFunction('pattern="'.$this->pattern.'"')
        if (mb_strlen($this->value)<=50) $element->setClass("textFontBig");
        elseif((mb_strlen($this->value)>50) and (mb_strlen($this->value)<=80)) $element->setClass("textFontNormal");
        elseif(mb_strlen($this->value)>80)$element->setClass("textFontSmall");

//        if ($this->functionOnKeyUp) $element->setFunction("onkeyup=\"$this->functionOnKeyUp\"");

        $element->setFunction('name="'.$this->NameId.'"')
            ->setClass($this->className)
            ->setFunction($pass)
            //->setStyle("width:".($this->width-20)."px;")
            ->setFunction("value = '$this->value'")
            ->setFunction($func_onBlur)
            ->setFunction($func_onfocus);
        $HTTPInput=$element->getHTTPTag();

        $HTMLCode=$element
            ->tag("div")
            ->setId("div".$this->NameId)
            ->setClass("MyInputDiv")
            ->setStyle($this->style.";margin:5px")
            ->setFunction($func_onclick)
            ->setCaption($HTTPP.$HTTPInput)
            ->getHTTPTag();

        return $HTMLCode;


    }
}
