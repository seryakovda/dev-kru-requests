<?php
namespace forms\Request;

class MODEL extends \forms\FormsModel
{
    private $conn;
    public $Content;
    function __construct()
    {
        $this->filter = Array();
        $this->conn = \backend\Connection::get();
        $this->Content = 50;
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
        if ( $_SESSION['userAdmin'] == 0 ){
            $rrow = Array();
            $rrow['field'] =  'id_user';
            $rrow['value'] =  $_SESSION['id_user'];
            $rrow['znak'] = '=';
            $this->filter[] = $rrow;
        }

        $this->conn->table($this->table);
        if (count($this->filter)>0){
            foreach ($this->filter as $field => $rrow){
                $this->conn->where($rrow['field'],$rrow['value'], $rrow['znak']);
            }
        }
        $this->conn->orderBy("id DESC");

        return $this->conn->select();
    }

    public function createNewRequest()
    {
        return $this->conn->table("request")
            ->set('id_user',$_SESSION['id_user'])
            ->set('date_start',date ("Y.m.d"))
            ->insert();
    }

    /**
     * @param $idRequest
     * @return array
     */
    public function getHeadRequest()
    {
        $this->conn->table("request_full");
        if ($_SESSION['userAdmin']==0){
            $this->conn->where ('id_user',$_SESSION['id_user']);
              }
        return $this->conn->where ('id',$_SESSION['id_Request'])
            ->select()
            ->fetch();
    }

    public function updateHead($property,$value)
    {
        if (($property == 'date_start') || ($property == 'date_end')){
            $value = date("Y.m.d", strtotime($value));
        }
        $this->conn->table("request")
            ->set($property,$value)
            ->where ('id',$_SESSION['id_Request'])
            ->update();
    }

    public function getRequestResource()
    {
        $conn = new \backend\Connection();
        return $conn->table("view_request_resource")
            ->where ('id_request',$_SESSION['id_Request'])
            ->select();
    }


    public function getRequestNetDisk()
    {
        $conn = new \backend\Connection();
        return $conn->table("view_request_netDisk")
            ->where ('id_request',$_SESSION['id_Request'])
            ->select();
    }


    public function getRequestResourceInternet($arrayHeadData)
    {
        $ret = Array();
        if ($arrayHeadData['internet_f'] == 1){
            $ret[] = Array('internet_remark'=>'Доступ в интернет к ресурсам '.$arrayHeadData['internet_remark']);
        }else{
            $ret[] = Array('internet_remark'=>'Доступ в интернет запрещён');

        }
        return $ret;
    }


    public function addRequestInToTable_0($id_resource_IsArray)
    {
        foreach ($id_resource_IsArray as $key => $value){
            $this->conn->table('request_resource')
                ->set('id_request',$_SESSION['id_Request'])
                ->set('id_resource',$value)
                ->insert();
        }
    }

    public function addNetDiskInToTable_0($id_resource_IsArray)
    {
        foreach ($id_resource_IsArray as $key => $value){
            $this->conn->table('request_netdisk')
                ->set('id_request',$_SESSION['id_Request'])
                ->set('id_netDisk',$value)
                ->insert();
        }
    }

    public function deleteResourceInToTable($id_resource_IsArray)
    {
        foreach ($id_resource_IsArray as $key => $value){
            $this->conn->table('request_resource')
                ->where('id_resource',$value)
                ->where('id_request',$_SESSION['id_Request'])//удаляет запись из заявка-ресурсов
                ->delete();
        }
    }

    public function deleteNetDiskInToTable($id_netDisk_IsArray)
    {
        foreach ($id_netDisk_IsArray as $key => $value){
            $this->conn->table('request_netdisk')
                ->where('id_netDisk',$value)
                ->delete();
        }
    }
    public function objectToArray($data)
    {
        $ret = Array();
        while ($res = $data->fetch()){
            $ret[] = $res;
        }
        return $ret;
    }


    private function GUID()
    {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }


    public function uploadFile($idRequest)
    {
        $fileName= false;
        $files      = $_FILES; // полученные файлы

        $this->deleteImage($idRequest);

        foreach( $files as $key => $file ){

            $fileName=$file['name'];
            $fileName = strtolower($fileName);
            $extention=explode('.',$fileName);
            $ext=end($extention);
            $imageTmp=file_get_contents($file['tmp_name']);

            if ($ext == 'pdf'){
                $im = new \Imagick();
                $im->setResolution(200, 200);
                $im->readImageBlob($imageTmp);
                $im->setImageUnits(1); //Declare the units for resolution.
                $im->setImageFormat('jpeg');
                $im->setImageCompression(8);// \Imagick::COMPRESSION_JPEG
                $im->setImageCompressionQuality(80);
                foreach($im as $i=>$imagick) {
                    $imagick->setImageUnits(1); //Declare the units for resolution.
                    $imagick->setImageFormat('jpeg');
                    $imagick->setImageCompression(8);// \Imagick::COMPRESSION_JPEG
                    $imagick->setImageCompressionQuality(80);
                    $imageTmp = $imagick->getImageBlob();
                    $this->saveImage($idRequest,$imageTmp);
                }
                $im->destroy();
            }else{
                $this->saveImage($idRequest,$imageTmp);
            }

        }
    }

    private function deleteImage($idRequest)
    {
        $this->conn->table('image')
            ->where("idRequest",$idRequest)
            ->delete();
    }

    private function saveImage($idRequest,$data)
    {
        $this->conn->table('image')
            ->set("idRequest",$idRequest)
            ->set("uuid",$this->GUID())
            ->set("body",$data)
            ->insert();
    }
    public function getAllScanPage($idRequest)
    {
        return $this->conn->table('image')
            ->where("idRequest",$idRequest)
            ->select('uuid');
    }
    public function downloadImage($uuid)
    {
        return $this->conn->table('image')
            ->where("uuid",$uuid)
            ->select('body')->fetchField('body');


    }

    public function deleteRequest()
    {
        $this->conn->table('image')
            ->where("idRequest",$_SESSION['id_Request'])
            ->delete();
        $this->conn->table('request_netdisk')
            ->where("idRequest",$_SESSION['id_Request'])
            ->delete();
        $this->conn->table('request_resource')
            ->where("idRequest",$_SESSION['id_Request'])
            ->delete();
        $this->conn->table('request')
            ->where("id",$_SESSION['id_Request'])
            ->delete();
    }

}