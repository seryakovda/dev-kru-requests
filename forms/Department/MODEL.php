<?php
namespace forms\Department;

class MODEL extends \forms\FormsModel
{
    private $conn;
    function __construct()
    {
        $this->conn = \backend\Connection::get();
        $this->filter = Array();
    }

    public function setFilter($valArray)
    {
        foreach ($valArray as $field => $value) {
            $rrow = Array();
            $rrow['field'] =  "lower ( $field )";
            $rrow['value'] =  "%".strtolower($value)."%";
            $rrow['znak'] = 'like';
            if ($value!=='') $this->filter[] = $rrow;
        }
    }

    public function getData()
    {
        $this->conn->table($this->table);
        if (count($this->filter)>0){
            foreach ($this->filter as $field => $rrow){
                $this->conn->where($rrow['field'],$rrow['value'], $rrow['znak']);
            }
        }
        return $this->conn->select("*");
    }

    public function addDepartment_0($name)
    {
        $this->conn->table('department')
            ->set('name',$name)
            ->insert();
    }

    public function getDataOneRow()
    {
        $this->conn->table("view_department_full");
        return $this->conn->where ('id',$_SESSION['id_Department'])
            ->select()
            ->fetch();
    }

    public function updateHead($property,$value)
    {
        $this->conn->table("department")
            ->set($property,$value)
            ->where ('id',$_SESSION['id_Department'])
            ->update();
    }

    public function deleteDepartment_1()
    {
        $this->conn->table("department")
            ->where ('id',$_SESSION['id_Department'])
            ->delete();

    }
}