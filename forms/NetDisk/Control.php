<?php
namespace forms\NetDisk;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->setFormWidth(1050);
        $this->setTable("view_netDisk_full");
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(1050);
        $this->setTable("view_netDisk_full");
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }

    public function addNetDisk_0()
    {
        $this->MODEL->addNetDisk_0($_GET['name']);
    }

    public function editNetDisk()
    {
        $_SESSION['id_NetDisk'] = $_GET['id_NetDisk'];
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditNetDisk());
    }

    public function updatePropertyNetDisk_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditNetDisk());
    }
}