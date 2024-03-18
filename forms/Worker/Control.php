<?php
namespace forms\Worker;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->setFormWidth(1050);
        $this->setTable("worker");
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(1050);
        $this->setTable("worker");
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }

    public function addWorker_0()
    {
        $this->MODEL->addWorker_0($_GET['name']);
    }

    public function editWorker()
    {
        $_SESSION['id_Worker'] = $_GET['id_Worker'];
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditWorker());
    }

    public function updatePropertyWorker_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditWorker());
    }
}