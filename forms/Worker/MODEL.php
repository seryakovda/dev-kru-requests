<?php
namespace forms\Worker;

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

    public function addWorker_0($name)
    {
        $this->conn->table('worker')
            ->set('name',$name)
            ->insert();
    }

    public function getDataOneRow()
    {
        $this->conn->table("worker");
        return $this->conn->where ('id',$_SESSION['id_Worker'])
            ->select()
            ->fetch();
    }

    public function updateHead($property,$value)
    {
        $this->conn->table("worker")
            ->set($property,$value)
            ->where ('id',$_SESSION['id_Worker'])
            ->update();
    }
}