<?php
namespace forms\Resource;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->setFormWidth(1050);
        $this->setTable("resource");
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(1050);
        $this->setTable("resource");
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }

    public function addResource_0()
    {
        $this->MODEL->addResource_0($_GET['name']);
    }

    public function editResource()
    {
        $_SESSION['id_Resource'] = $_GET['id_Resource'];
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditResource());
    }

    public function updatePropertyResource_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditResource());
    }

    public function deleteResource_1()
    {
        $_SESSION['id_Resource'] = $_GET['id_Resource'];
        $this->MODEL->deleteResource_1();
    }
}