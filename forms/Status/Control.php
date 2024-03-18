<?php
namespace forms\Status;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->setFormWidth(1050);
        $this->setTable("status");
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(1050);
        $this->setTable("status");
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }

    public function addStatus_0()
    {
        $this->MODEL->addStatus_0($_GET['name']);
    }

    public function editStatus()
    {
        $_SESSION['id_Status'] = $_GET['id_Status'];
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditStatus());
    }

    public function updatePropertyStatus_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditStatus());
    }
}