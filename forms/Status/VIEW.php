<?php
namespace forms\Status;

class VIEW extends \forms\FormView
{

    public $windowContent;
    private $dataGrid_Object;
    private $dataOneRow;

    public function initClass()
    {
        $this->TXT_headSmallTitle="Статус выполнениея заявки";

        $this->BTN = new \views\elements\Button\Button();
        $this->WND = new \views\elements\Window\Window();
    }

    public function printMainWindow()
    {
        print "<code>";
        print $this->windowContent;
        print "</code>";
        print $this->includeHtmlFilter();
        include "HTML.php";
    }

    public function includeHtmlFilter()
    {
        ob_start();
        include "HTML_filter.php";
        $output=ob_get_contents();
        ob_end_clean();
        return $output;
    }


    public function setDataGridObject($dataGrid_Object)
    {
        $this->dataGrid_Object = $dataGrid_Object;
    }


    /**
     * @param mixed $dataOneRow
     */
    public function setDataOneRow($dataOneRow)
    {
        $this->dataOneRow = $dataOneRow;
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


    /**
     * @return string возвращает HTML таблицы завернутую в окно
     */
    public function divGreed()
    {

        $HTML = $this->greed($this->dataGrid_Object);
        return $this->WND->set()->nameId($this->nameGreedDIV)->headSizeNone()->floatLeft()->shadowNone()->content($HTML)->get();
    }

    public function createHeadWindow()
    {
        $windowContent = '';
        $windowContent = $windowContent. $this->filterBlock();
        $this->windowContent = $windowContent. $this->divGreed();
    }

    public function createBottomWindowEdit()
    {
        $HTML = '';
        $width = 330;
        $height = 25;

        $HTML = $HTML.
            $this->BTN->set("Добавить")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("addStatus()")
                ->get();
        $HTML = $HTML.
            $this->BTN->set("Редактировать")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("editStatus()")
                ->get();
        $HTML = $HTML.
            $this->BTN->set("Удалить(Лучше не делать)")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("deleteStatus()")
                ->get();

        $windowContent = '';
        $windowContent = $windowContent.
            $this->WND->set()->nameId('manageElement')->headSizeNone()->floatLeft()->shadowNone()->content($HTML)->get();
        $windowContent = $windowContent.
            $this->WND->set()->nameId($this->nameEditDIV)->headSizeNone()->floatLeft()->shadowNone()->get();

        $this->windowContent = $this->windowContent.$windowContent;
    }


    public function createBottomWindowSelect()
    {
        $windowContent = '';
        $windowContent = $windowContent . $this->selectBlock();
        $this->windowContent = $this->windowContent . $windowContent;
    }

    public function greed($data_Object)
    {
        $greed_Object = new \views\elements\Grid\Grid();
        $greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName,__METHOD__))
            ->checked()
            ->width($this->formWidth)
            ->row(20)->ColumnID("id")
            ->Column("name")   ->Column_Width(1000)->Column_Caption("Название");

        if ($this->allInsertOff){
            $greed_Object->allInsertOff();
        }
        $greed_Object->setOnClickFunctionForAllTable('clearEditWindow("'.$this->nameEditDIV.'")');

        $HTML = $greed_Object->GetTable($data_Object);

        return $HTML;
    }
    public function filterBlock()
    {

        $INP = new \views\elements\Input\Input();

        $HTML = "";
        $HTML = $HTML.
            $INP->set("Название")
                ->height(50)->width(500)
                ->position("relative")
                ->startFont("Large")
                ->NameId("name_$this->objectFullName")
                ->floatLeft()
                ->get();


        $HTML = $HTML.
            $this->BTN->set("Отфильтровать")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("refresh_$this->objectFullName()")
                ->get();

        return $HTML;
    }

    public function selectBlock()
    {

        /**
         * HTML = $greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName,__METHOD__))
         * формирование id добавляем через подчер имя метода.
         */
        $id_greed = $this->objectFullName."_greed";

        $HTML = "";


        $HTML = $HTML.
            $this->BTN->set("Отмена")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("closeBlockAPP()")
                ->get();

        $HTML = $HTML.
            $this->BTN->set("Выбрать")
                ->height(40)->width(150)
                ->floateLeft()
                ->func("_G_buttonSelect('$this->nameGreed','$this->callFunction_txt')")
                ->get();

        return $HTML;
    }

    public function createEditStatus()
    {
        $HTML = '';
        $width = 700;
        $height = 20;

        $HTML = '';

        $property = 'name';
        $valProperty =  $this->dataOneRow[$property];
        $HTML = $HTML.
            $this->BTN->set("Фамилия инициалы  - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("updatePropertyStatus('$property','$valProperty')")
                ->get();

        $property = 'post';
        $valProperty =  $this->dataOneRow[$property];
        $HTML = $HTML.
            $this->BTN->set("Должность  - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("updatePropertyStatus('$property','$valProperty')")
                ->get();


        $property = 'address';
        $valProperty =  $this->dataOneRow[$property];
        $HTML = $HTML.
            $this->BTN->set("Кабинет (расположение)  - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("updatePropertyStatus('$property','$valProperty')")
                ->get();


        $HTML = $this->WND->set()->nameId('id_editStatus')
            ->headSizeSmall()
            ->titleSmall("Редактирование ресурса")
            ->floatLeft()
            ->shadowSmall()
            ->content($HTML)
            ->get();

        return $HTML ;
    }
}