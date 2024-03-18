<?php
namespace forms\Request;

class VIEW extends \forms\FormView
{

    public $windowContent;
    private $dataGrid_Object;
    private $dataHeadRequest;
    private $dataTableRequest;
    private $dataTableNetDisk;
    private $element;

    function __construct()
    {

        $this->BTN = new \views\elements\Button\Button();
        $this->WND = new \views\elements\Window\Window();
        $this->element = new \views\elements\VElements();
        $this->initClass();

    }

    /**
     * @param mixed $dataTableNetDisk
     */
    public function setDataTableNetDisk($dataTableNetDisk)
    {
        $this->dataTableNetDisk = $dataTableNetDisk;
    }

    /**
     * @param mixed $dataTableRequest
     */
    public function setDataTableRequest($dataTableRequest)
    {
        $this->dataTableRequest = $dataTableRequest;
    }


    /**
     * @param mixed $dataHeadRequest
     */
    public function setDataHeadRequest($dataHeadRequest)
    {
        $this->dataHeadRequest = $dataHeadRequest;
    }

    public function initClass()
    {
        $this->TXT_headSmallTitle="Перечень Заявок";

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


    public function mainWindow()
    {
        $window = new \views\elements\Window\Window();
        $this->windowContent = $window->set()
            ->nameId($this->objectFullName)
            ->titleSmall($this->TXT_headSmallTitle)
            ->width($this->formWidth)
            ->content($this->windowContent)
            ->floatLeft()
            ->headSizeSmall()
            ->setBtnCloseWindowFunction("closeWindowRequest()")
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


    public function createBottomWindowSelect()
    {
        $windowContent = '';
        $windowContent = $windowContent . $this->selectBlock();
        $this->windowContent = $this->windowContent . $windowContent;
    }


    public function createBottomWindowEdit()
    {
        $windowContent = '';
        $HTML = "";

        $width = 503;
        if ($_SESSION['userAdmin'] == 1){
            $width = 335;
        }

        $height = 25;

        $HTML = $HTML.
            $this->BTN->set("Войти в заявку")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("editRequest()")
                ->get();

        $HTML = $HTML.
            $this->BTN->set("Создать новую заявку на доступ")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("createNewRequest()")
                ->get();

        if ($_SESSION['userAdmin'] == 1){
            $HTML = $HTML.
                $this->BTN->set("Удалить заявку")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->func("deleteRequest()")
                    ->get();
        }
        $HTML = $this->WND->set()->nameId("manageElement")->headSizeNone()->floatLeft()->shadowNone()->content($HTML )->get();

        $this->windowContent = $this->windowContent.$HTML;

        $elements = new \views\elements\VElements();
        $this->windowContent = $elements->tag("div")
            ->setId("allManageElement_Request")
            ->setCaption($this->windowContent)
            ->getHTTPTag();


        $windowContent = $windowContent.
            $this->WND->set()->nameId($this->nameEditDIV)->headSizeNone()->floatLeft()->shadowNone()->get();

        $this->windowContent = $this->windowContent.$windowContent;
    }


    public function greed($data_Object)
    {
        $greed_Object = new \views\elements\Grid\Grid();
        $greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName,__METHOD__))
            ->width($this->formWidth)
            ->row(27)->ColumnID("id")
            ->Column("id")   ->Column_Width(75)->Column_Caption("Номер");

            $greed_Object
            ->Column("surname")   ->Column_Width(200)->Column_Caption("Фамилия")
            ->Column("name")   ->Column_Width(200)->Column_Caption("Имя")
            ->Column("patronymic")   ->Column_Width(200)->Column_Caption("Отчество");

        if ($_SESSION['userAdmin'] == 1){
            $greed_Object
                ->Column("comment")   ->Column_Width(300)->Column_Caption("Коментарий")
                ->Column("name_department")   ->Column_Width(300)->Column_Caption("Подразделение")
                ->Column("login")   ->Column_Width(180)->Column_Caption("Компьютер");
//                ->Column("name_worker")   ->Column_Width(200)->Column_Caption("Начальник подразделения");
        }
            $greed_Object
                ->Column("name_status")   ->Column_Width(150)->Column_Caption("Статус")
                        ->dynamicBackgroundColor('$ROW["status"]==1','#f9cbcb')
                        ->dynamicBackgroundColor('$ROW["status"]==2','#f7eb9a')
                        ->dynamicBackgroundColor('$ROW["status"]==3','#9ae6f7')
                        ->dynamicBackgroundColor('$ROW["status"]==4','#b1f79a');

        if ($this->allInsertOff){
            $greed_Object->allInsertOff();
        }
        $greed_Object->setonDblClickFunctionForAllTable("editRequest()");
        $HTML = $greed_Object->GetTable($data_Object);

        return $HTML;
    }

//
    public function greedNetDisk($data_Object)
    {
        $greed_Object = new \views\elements\Grid\Grid();
        $greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName,__METHOD__))
            ->width($this->formWidth)
            ->row(6)->ColumnID("id")
            ->Column("name_netDisk")    ->Column_Width(450)->Column_Caption("Путь сетевого диска")
            ->Column("caption") ->Column_Width(450)->Column_Caption("Владелец диска");
        $HTML = $greed_Object->GetTable($data_Object);
        return $HTML;
    }

    public function greedResource($data_Object)
    {
        $greed_Object = new \views\elements\Grid\Grid();
        $greed_Object->GNew(\models\ControlElements::get()->getNameMethod($this->objectFullName,__METHOD__))
            ->width($this->formWidth)
            ->row(6)->ColumnID("id")
            ->Column("name")   ->Column_Width(900)->Column_Caption("Название ресурса (программы)");
        $HTML = $greed_Object->GetTable($data_Object);
        return $HTML;
    }


    public function filterBlock()
    {

        $INP = new \views\elements\Input\Input();
        $width = 240;
        $height = 25;

        $HTML = "";
        $HTML = $HTML.
            $INP->set("Фамилия")
                ->width($width)->height($height)
                ->position("relative")
                ->startFont("Large")
                ->NameId("surname_$this->objectFullName")
                ->floatLeft()
                ->get();

        $HTML = $HTML.
            $INP->set("Имя")
                ->width($width)->height($height)
                ->position("relative")
                ->startFont("Large")
                ->NameId("name_$this->objectFullName")
                ->floatLeft()
                ->get();
        $HTML = $HTML.
            $INP->set("Отчество")
                ->width($width)->height($height)
                ->position("relative")
                ->startFont("Large")
                ->NameId("patronymic_$this->objectFullName")
                ->floatLeft()
                ->get();
        $HTML = $HTML.
            $INP->set("Статус")
                ->width($width/2)->height($height)
                ->position("relative")
                ->startFont("Large")
                ->NameId("name_status_$this->objectFullName")
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


    public function createEditHeadRequest()
    {
        $check = new \views\elements\Check\Check();
        $width = 970;
        $height = 20;

        $HTML = '';

        $property = 'status';
        if ($this->dataHeadRequest[$property] == 1){
            $function = "updatePropertyRequest";
            $function_Catalog = "updatePropertyRequest_Catalog";
            $internetClick = "internetClick";
            $netDiskClick = "netDiskClick";

        }else{
            $function = "updateClosed";
            $function_Catalog = "updateClosed";
            $internetClick = "updateClosed";
            $netDiskClick = "updateClosed";

        }
        $HTML = $HTML.
            $this->BTN->set("Вернуться к списку заявок (ВЫХОД)")
                ->width(150)->height(40)
                ->floateLeft()
                ->func("editExitRequest()")
                ->get();


        $property = 'surname';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Фамилия - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();
        if ($_SESSION['userAdmin'] == 1){
            $fio = $this->dataHeadRequest['surname'].' '.$this->dataHeadRequest['name'].' '.$this->dataHeadRequest['patronymic'];
            $HTML = $HTML.$this->element->tag('div')
                    ->setId('CopyClipText')
                    ->setCaption($fio)
                    ->setStyle('display:none')
                    ->getHTTPTag();
            $HTML = $HTML.
                $this->BTN->set("")
                    ->width(23)->height($height)
                    ->floateLeft()
                    ->horizontalPosLeft()
                    ->marginBottomOff()
                    ->fontSmall()
                    ->title('Скопировать ФИО полностью в  буфер обмена')
                    ->backgroundColorClass('buttonCopyClipboard')
                    ->func("CopyToClipboard('CopyClipText')")
                    ->get();

        }

        $property = 'name';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Имя - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();

        $property = 'patronymic';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Отчество - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();

        $property = 'name_department';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Подразделение (В которое вступает) - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function_Catalog('Department','id_department','$valProperty1')")
                ->get();

        $property = 'post';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Должность (В которую вступает) - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();

        $property = 'worckstation';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Имя компьютера <b>существующего</b> рабочего места. (PIPKIN-AA)- <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();

        $property = 'cabinet_n';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Кабинет - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();


        $property = 'telephone';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Телефон адрес- <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1')")
                ->get();

        $property = 'date_start';
        $valProperty =  date("d.m.Y", strtotime($this->dataHeadRequest[$property]));
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Заявка действует c - <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->nameId($property)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1','99.99.9999')")
                ->get();

        $property = 'date_end';
        $valProperty = date("d.m.Y", strtotime($this->dataHeadRequest[$property])) == date("d.m.Y", strtotime("01.01.1970"))?
             "":
            date("d.m.Y", strtotime($this->dataHeadRequest[$property]));
        $valProperty1 = $this->prepareForHtml ($valProperty);
        $HTML = $HTML.
            $this->BTN->set("Заявка действует по (заполнять не обязательно)- <b>".$valProperty."</b>")
                ->width($width)->height($height)
                ->nameId($property)
                ->floateLeft()
                ->horizontalPosLeft()
                ->marginBottomOff()
                ->func("$function('$property','$valProperty1','99.99.9999')")
                ->get();

        $HTML = $this->WND->set()
                ->headSizeNone()
                ->width($this->formWidth-20)
                ->content($HTML)
                ->style('background-color: #00ffcf38')
                ->style('padding-bottom: 5px')
                ->get();

        $property = 'internet_f';
        $valPropertyCheck =  $this->dataHeadRequest[$property];

        $check->set("Доступ в интернет")
            ->width($width-3)->height($height)
            ->floatLeft()
            ->nameId($property)
            ->func("$internetClick()");
        if ($valPropertyCheck == 0){
            $check->checkedOff();
        }
        $HTML_internet = $check->get();

        $property = 'internet_remark';
        $valProperty =  $this->dataHeadRequest[$property];
        $valProperty1 = $this->prepareForHtml ($valProperty);

        $this->BTN->set("Обоснование доступа в интернет - <b>".$valProperty."</b>")
            ->width($width)->height($height*2)
            ->nameId($property)
            ->floateLeft()
            ->horizontalPosLeft()
            ->marginBottomOff()
            ->func("$function('$property','$valProperty1')");
        if ($valPropertyCheck == 0){
            $this->BTN->displayNone();
        }

        $HTML_internet = $HTML_internet.
            $this->BTN->get();

        $HTML = $HTML . $this->WND->set()
            ->headSizeNone()
            ->width($this->formWidth-20)
            ->content($HTML_internet)
            ->style('background-color: #ffb30038')
                ->style('padding-bottom: 5px')
            ->get();

        //netDisk_f
        $property = 'netDisk_f';
        $valPropertyCheck =  $this->dataHeadRequest[$property];

        $check->set("Сетевые диски")
            ->width($width-3)->height($height)
            ->floatLeft()
            ->nameId($property)
            ->func("$netDiskClick()");
        if ($valPropertyCheck == 0){
            $check->checkedOff();
        }
        $HTML_netDisk =
            $check->get();

        if ($valPropertyCheck != 0){
            $WND = new \views\elements\Window\Window();
            $HTML_netDisk = $HTML_netDisk.
                $WND->set()
                    ->nameId('id_editTableNetDisk')
                    ->headSizeNone()->floatLeft()->shadowNone()
                    ->content($this->greedNetDisk($this->dataTableNetDisk))
                    ->get();

            $HTML_netDisk = $HTML_netDisk.
                $this->BTN->set("Выбрать сетевые диски")
                    ->width(($width/2)-5)->height($height)
                    ->nameId($property)
                    ->floateLeft()
                    ->horizontalPosCenter()
                    ->marginBottomOff()
                    ->func($this->dataHeadRequest[$property]==1?"addNetDiskInToTable()":"updateClosed()")
                    ->get();

            $HTML_netDisk = $HTML_netDisk.
                $this->BTN->set("Убрать сетевые диски")
                    ->width(($width/2)-5)->height($height)
                    ->nameId($property)
                    ->floateLeft()
                    ->horizontalPosCenter()
                    ->marginBottomOff()
                    ->func($this->dataHeadRequest[$property]==1?"deleteNetDiskInToTable()":"updateClosed()")
                    ->get();
        }
        $HTML = $HTML . $this->WND->set()
                ->headSizeNone()
                ->width($this->formWidth-20)
                ->content($HTML_netDisk)
                ->style('background-color: #14ff0038')
                ->style('padding-bottom: 5px')
                ->get();

        $HTML_res =
            $this->WND->set()->nameId('id_editTableRequest')
                ->width($width-20)
                ->headSizeNone()->floatLeft()->shadowNone()->content($this->greedResource($this->dataTableRequest))->get();

        $property = 'status';
        $HTML_res = $HTML_res.
            $this->BTN->set("Добавить ресурс, программу")
                ->floateLeft()
                ->width($width/2-5)->height($height)
                ->func($this->dataHeadRequest[$property]==1?"addRequestInToTable()":"updateClosed()")
                ->get();

        $HTML_res = $HTML_res.
            $this->BTN->set("Удалить ресурс ")
                ->width($width/2-5)->height($height)
                ->floateLeft()
                ->func($this->dataHeadRequest[$property]==1?"deleteResourceInToTable()":"updateClosed()")
                ->get();

        $HTML = $HTML . $this->WND->set()
                ->headSizeNone()
                ->width($this->formWidth-20)
                ->content($HTML_res)
                ->style('background-color: #2000ff38')
                ->style('padding-bottom: 5px')
                ->get();

        if ($_SESSION['userAdmin'] == 1){
            $property = 'name_status';
            $valProperty =  $this->dataHeadRequest[$property];
            $HTML = $HTML.
                $this->BTN->set("Статус заявки - <b>".$valProperty."</b>")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->horizontalPosLeft()
                    ->marginBottomOff()
                    ->func("replaceStatus()")
                    ->get();
            $property = 'comment';
            $valProperty =  $this->dataHeadRequest[$property];
            $valProperty1 = $this->prepareForHtml ($valProperty);
            $HTML = $HTML.
                $this->BTN->set("Коментарий (видно только админу) - <b>".$valProperty."</b>")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->horizontalPosLeft()
                    ->marginBottomOff()
                    ->func("$function('$property','$valProperty1')")
                    ->get();
        }
        return $HTML ;
    }


    public function elementEditTableRequest()
    {
        $HTML = '';
        $width = 325;
        $height = 25;
        $property = 'status';
        if ($this->dataHeadRequest[$property] == 1){
            $function = "updatePropertyRequest";
            $function_Catalog = "updatePropertyRequest_Catalog";
            $internetClick = "internetClick";
        }else{
            $function = "updateClosed";
            $function_Catalog = "updateClosed";
            $internetClick = "updateClosed";

        }

        $HTML = $HTML.
            $this->element
                ->tag('input')
                ->setId("upload_button")
                ->setStyle("display: none")
                ->setStyle('background-color: rgba(0, 0, 0, 0);')
                ->setFunction('type="file"')
                ->setFunction('accept="*.*"')
                ->getHTTPTag();


        $HTML = $HTML.
            $this->BTN->set("Сформировать для печати")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("printRequest()")
                ->get();

        if ($_SESSION['userAdmin'] == 1){
            $HTML = $HTML.
                $this->BTN->set("Загрузить скан")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->func("uploadFile_0()")
                    ->get();
            $HTML = $HTML.
                $this->BTN->set("Посмотреть скан")
                    ->width($width)->height($height)
                    ->floateLeft()
                    ->func("viewScan()")
                    ->get();
        }

        $HTML = $HTML.
            $this->BTN->set("Вернуться к списку (ВЫХОД)")
                ->width($width)->height($height)
                ->floateLeft()
                ->func("editExitRequest()")
                ->get();

        return $HTML ;
    }


    public function createEditRequest()
    {
        $WND = new \views\elements\Window\Window();
        $HTML = '';
        $HTML = $HTML.
            $WND->set()->nameId('id_editHeadRequest')->headSizeNone()->floatLeft()->shadowNone()->content($this->createEditHeadRequest())->get();


        $HTML = $HTML.$this->elementEditTableRequest();

        $HTML = $this->WND->set()->nameId('editRequest')
            ->width(1010)
            ->headSizeSmall()
            ->titleSmall("Заполнение заявки (Все изменения сохраняются автоматичеси)")
            ->floatLeft()
            ->shadowSmall()
            ->content($HTML)
            ->setBtnCloseWindowFunction("editExitRequest()")
            ->get();
        return $HTML ;
    }


    public function viewScan($data)
    {
        $HTML = '';
        $Media= new \views\elements\Media\Media();
        $elements = new \views\elements\VElements();

        $heightImage = $_SESSION['heightBrowse']-75;

        $HTML = $HTML.
            $this->BTN->set("Закрыть")
                ->width(890)->height(25)
                ->floateLeft()
                ->func("closeBlockAPP()")
                ->get();
        $HTML_Media = '';
        while ($res = $data->fetch()){
            $uuid = $res['uuid'];
            $HTML_Media = $HTML_Media.$Media
                ->width(880)->height(1200)
                ->image("/index_ajax.php?r0=Request&r1=downloadImage&uuid=$uuid");
        }


        $HTML = $HTML. $elements->tag("div")
            ->setStyle("height:{$heightImage}px;width:900px;overflow-y: scroll")
            ->setCaption($HTML_Media)
            ->getHTTPTag();

        $HTML = $this->WND->set()
            ->nameId('viewScan')
            ->width(900)->height($_SESSION['heightBrowse']-70)
            ->headSizeNone()
            ->floatLeft()
            ->shadowNone()
            ->content($HTML)->get();

        $HTML = $elements->tag("div")->setClass("MsgBlockAPP")
            ->setStyle("width:1050px ")// для прорисовки сообщения по центру экрана
//            ->setStyle("top:0px;left:0px;width:1050px;height:950px ")// для прорисовки сообщения по центру экрана
            ->setCaption($HTML)->getHTTPTag();

        return $HTML;
    }

    private function prepareForHtml($txt)
    {
        return htmlspecialchars(addslashes($txt));
    }
}