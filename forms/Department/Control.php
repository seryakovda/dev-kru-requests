<?php
namespace forms\Department;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
        parent::__construct();
        $this->setFormWidth(1050);
        $this->setTable("view_department_full");
    }

    public function defaultMethod()
    {
        parent::defaultMethod();
        $this->setFormWidth(1050);
        $this->setTable("view_department_full");
        $data = $this->MODEL->getData();
        $this->VIEW->setDataGridObject($data);
        $this->VIEW->createHeadWindow();
        $this->VIEW->createBottomWindowEdit();
        $this->VIEW->mainWindow();
        $this->VIEW->printMainWindow();
    }

    public function addDepartment_0()
    {
        $this->MODEL->addDepartment_0($_GET['name']);
    }

    public function editDepartment()
    {
        $_SESSION['id_Department'] = $_GET['id_Department'];
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditDepartment());
    }

    public function updatePropertyDepartment_0()
    {
        $this->MODEL->updateHead($_GET['property'],$_GET['value']);
        $data = $this->MODEL->getDataOneRow();
        $this->VIEW->setDataOneRow($data);
        $this->VIEW->printElement($this->VIEW->createEditDepartment());
    }

    public function deleteDepartment_1()
    {
        $_SESSION['id_Department'] = $_GET['id_Department'];
        $this->MODEL->deleteDepartment_1();

    }
}