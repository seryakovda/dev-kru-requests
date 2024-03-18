<?php
namespace forms\Resource;

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
        $this->conn = \backend\Connection::get();
        $this->conn->table($this->table);
        if (count($this->filter)>0){
            foreach ($this->filter as $field => $rrow){
                $this->conn->where($rrow['field'],$rrow['value'], $rrow['znak']);
            }
        }
        $this->conn->orderBy('name');
        return $this->conn->select("*");
    }

    public function addResource_0($name)
    {
        $this->conn->table('resource')
            ->set('name',$name)
            ->set('id_worker',0)
            ->insert();

    }

    public function getDataOneRow()
    {
        $this->conn->table("view_resource_full");
        return $this->conn->where ('id',$_SESSION['id_Resource'])
            ->select()
            ->fetch();
    }

    public function updateHead($property,$value)
    {
        \models\ErrorLog::saveError($property.'-'.$value);
        $this->conn->table("resource")
            ->set($property,$value)
            ->where ('id',$_SESSION['id_Resource'])
            ->update();
    }

    public function deleteResource_1()
    {
        $this->conn->table("resource")
            ->where ('id',$_SESSION['id_Resource'])
            ->delete();
    }
}