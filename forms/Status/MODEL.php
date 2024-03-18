<?php 
namespace forms\Status;

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
            $rrow['field'] =  $field;
            $rrow['value'] =  "%".$value."%";
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

    public function addStatus_0($name)
    {
        $this->conn->table('status')
            ->set('name',$name)
            ->insert();
    }

    public function getDataOneRow()
    {
        $this->conn->table("status");
        return $this->conn->where ('id',$_SESSION['id_Status'])
            ->select()
            ->fetch();
    }

    public function updateHead($property,$value)
    {
        $this->conn->table("status")
            ->set($property,$value)
            ->where ('id',$_SESSION['id_Status'])
            ->update();
    }
}