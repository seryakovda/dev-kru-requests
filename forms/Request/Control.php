<?php
namespace forms\Request;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->setFormWidth(1050);
        $this->setTable("request_full");
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(1050);
        $this->setTable("request_full");
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }


    public function createNewRequest()
    {
        $newIdRequest = $this->MODEL->createNewRequest();
        $_SESSION['id_Request'] = $newIdRequest;
        print $newIdRequest;
        /*
        $dataHeadRequest = $this->MODEL->getHeadRequest();
        $dataTableRequest= $this->MODEL->getRequestResource();
        $this->VIEW->setDataHeadRequest($dataHeadRequest);
        $this->VIEW->setDataTableRequest($dataTableRequest);
        $this->VIEW->printElement($this->VIEW->createEditRequest());
        */
    }

    public function editRequest()
    {
        $_SESSION['id_Request'] = $_GET['id_Request'];
        $dataHeadRequest  = $this->MODEL->getHeadRequest();
        $dataTableRequest = $this->MODEL->getRequestResource();
        $dataTableNetDisk = $this->MODEL->getRequestNetDisk();
        $this->VIEW->setDataHeadRequest($dataHeadRequest);
        $this->VIEW->setDataTableRequest($dataTableRequest);
        $this->VIEW->setDataTableNetDisk($dataTableNetDisk);
        $this->VIEW->printElement($this->VIEW->createEditRequest());
    }

    public function deleteRequest()
    {
        $_SESSION['id_Request'] = $_GET['id_Request'];
        $this->MODEL->deleteRequest();
    }
    public function updatePropertyRequest_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $dataHeadRequest  = $this->MODEL->getHeadRequest();
        $dataTableRequest = $this->MODEL->getRequestResource();
        $dataTableNetDisk = $this->MODEL->getRequestNetDisk();
        $this->VIEW->setDataHeadRequest($dataHeadRequest);
        $this->VIEW->setDataTableRequest($dataTableRequest);
        $this->VIEW->setDataTableNetDisk($dataTableNetDisk);
        $this->VIEW->printElement($this->VIEW->createEditRequest());
    }

    public function deleteResourceInToTable()
    {
        $this->MODEL->deleteResourceInToTable(json_decode($_GET['value']));
        $dataTableRequest= $this->MODEL->getRequestResource();
        $this->VIEW->printElement($this->VIEW->greedResource($dataTableRequest));
    }

    public function deleteNetDiskInToTable()
    {
        $this->MODEL->deleteNetDiskInToTable(json_decode($_GET['value']));
        $dataTableNetDisk = $this->MODEL->getRequestNetDisk();
        $this->VIEW->setDataTableNetDisk($dataTableNetDisk);
        $this->VIEW->printElement($this->VIEW->greedNetDisk($dataTableNetDisk));
    }

    public function addRequestInToTable_0()
    {

        $this->MODEL->addRequestInToTable_0(json_decode($_GET['value']));
        $dataTableRequest= $this->MODEL->getRequestResource();
        $this->VIEW->printElement($this->VIEW->greedResource($dataTableRequest));
    }

    public function addNetDiskInToTable_0()
    {
        $this->MODEL->addNetDiskInToTable_0(json_decode($_GET['value']));
        $dataTableNetDisk= $this->MODEL->getRequestNetDisk();
        $this->VIEW->printElement($this->VIEW->greedNetDisk($dataTableNetDisk));
    }

    public function printRequest()
    {
        $_SESSION['id_Request'] = $_GET['id_Request'];
        $dataHeadRequest  = $this->MODEL->getHeadRequest();
        $dataTableRequest = $this->MODEL->getRequestResource();
        $dataTableNetDisk = $this->MODEL->getRequestNetDisk();

        $arrayTableInternetRequest = $this->MODEL->getRequestResourceInternet($dataHeadRequest);
        $arrayTableRequest = $this->MODEL->objectToArray($dataTableRequest);
        $arrayTableNetDisk = $this->MODEL->objectToArray($dataTableNetDisk);
        $report = new \models\ReportOnPattern();
        $report->setExcelPatternPath(__DIR__);
        $report->setResultFileName($_SESSION['id_Request']);
        $report->setH($dataHeadRequest);
        $report->setArray("t1",$arrayTableRequest);
        $report->setArray("t2",$arrayTableInternetRequest);
        $report->setArray("t3",$arrayTableNetDisk);
        $report->setProtection("PHP");
        $report->run();
    }

    public function updateClosed()
    {
        $view = new \views\Views();
        $view->MessageBlockDB("Ограничение!","Редактирование невозможно! Заявка находиться в работе");
    }

    public function replaceStatus_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $dataHeadRequest  = $this->MODEL->getHeadRequest();
        $dataTableRequest = $this->MODEL->getRequestResource();
        $dataTableNetDisk = $this->MODEL->getRequestNetDisk();
        $this->VIEW->setDataHeadRequest($dataHeadRequest);
        $this->VIEW->setDataTableRequest($dataTableRequest);
        $this->VIEW->setDataTableNetDisk($dataTableNetDisk);
        $this->VIEW->printElement($this->VIEW->createEditRequest());
   }

   public function upload()
   {
       $this->MODEL->uploadFile($_POST['idRequest']);
   }

   public function downloadImage()
   {
       header("content-type:image/jpeg");
       echo $this->MODEL->downloadImage($_GET['uuid']);
   }

    public function viewScan()
    {
        $data = $this->MODEL->getAllScanPage($_GET['idRequest']);
        $this->VIEW->printElement($this->VIEW->viewScan($data));
    }
}


