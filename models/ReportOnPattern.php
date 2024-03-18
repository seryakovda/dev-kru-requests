<?php
namespace models;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReportOnPattern
{

    private $excelPatternPath;
    private $excelPatternName;

    private $resultFileName;
    private $resultFilePath;

    private $BoockPattern;
    private $SheetPattern;
    private $SheetResult;

    public $spreadsheet;

    private $widthReport;
    private $rowReport;
    private $rowReportRead,$nomCol;

    private $H;

    private $activTable;
    private $table;
    private $group;
    private $protection;

    function __construct()
    {
        $this->protection = false;
        $this->excelPatternName = 'pattern.xlsx';
        $path = $_SERVER['DOCUMENT_ROOT'];
        if ($_SERVER['SERVER_NAME'] == 'afina'){
            $path = $path."/KRU";
        }
        $this->resultFilePath = $path."/download";

        $this->H = Array();
        $this->table = Array();
        $this->rowReport = 1;

    }

    /**
     *
     */
    public function run()
    {
        $this->defineSheetResult();
        $this->openFile();
        $this->copyHeadFile();
        $this->copyStyleXFCollection($this->BoockPattern,$this->spreadsheet );
        $this->detectWidthReport();
        $this->detectCommand();
        $this->MainProcessing();

        if ($this->protection !== false){
            $this->SheetResult->getProtection()->setSheet(true);
//            $this->SheetResult->getSecurity()->setLockWindows(true);
//            $this->SheetResult->getSecurity()->setLockStructure(true);
            $this->SheetResult->getProtection()->setPassword($this->protection);
        }
        $this->saveFile();
    }
    /**
     * @param array $H
     */
    public function setH(array $H)
    {
        $this->H = $H;
    }

    /**
     * @param string $newVar
     * @param array $array
     */
    public function setArray(string $newVar,array $array)
    {
        $this->{$newVar} = Array();
        $this->{'t'.$newVar} = Array();
        $this->{'notEnd_'.$newVar} = true;
        $this->{'t'.$newVar} = $array;
    }

    /**
     * @param $excelPatternName
     */
    public function setExcelPatternName($excelPatternName)
    {
        $this->excelPatternName = $excelPatternName;
    }

    /**
     * @param $excelPatternPath
     */
    public function setExcelPatternPath($excelPatternPath)
    {
        $this->excelPatternPath = $excelPatternPath;
    }

    /**
     * @param mixed $resultFileName
     */
    public function setResultFileName($resultFileName)
    {
        $this->resultFileName = $resultFileName;
    }

    /**
     * @param mixed $resultFilePath
     */
    public function setResultFilePath($resultFilePath)
    {
        $this->resultFilePath = $resultFilePath;
    }

    /**
     * @param string $password
     */
    public function setProtection(string $password)
    {
        $this->protection = $password;
    }


    /**
     *
     */
    private function MainProcessing()
    {
        foreach ($this->table as $key => $newArray){
            if (is_array($newArray))
                $this->arrayProcessing($newArray);
        }
    }


    /**
     * @param $Array
     * @return bool
     */
    private function arrayProcessing($Array)
    {
        if ($this->checkArrayElements($Array)){
            if ($Array['type']=='Table'){
                $this->initStartReadFromTable($Array);
                $nameArray = $Array['nameBlock'];
                if (count($this->{'t'.$nameArray})<>0){ // Если масив хоть чтото имеет
                    $this->blockProcessing($Array);
                }
            }else{
                $this->blockProcessing($Array);
            }

        }else{
            foreach ($Array as $key => $newArray){
                if (is_array($newArray))
                    $this->arrayProcessing($newArray);
            }
        }
        return true;
    }


    /**
     * @param $Array
     */
    private function blockProcessing($Array)
    {
        $row = $this->rowReport;

        switch ($Array['type']){
            case 'Table' :
                $this->prepareData($Array['nameBlock']);
                if ($this->notEndOfArray($Array['nameBlock'])) {
                    $this->copyBlockPattern($Array);
                    $this->rowReport = $this->rowReport + $Array['rows'];
                }
                break;
            case 'Group' :
                if ($this->groupProcessing($Array)){
                    $this->copyBlockPattern($Array);
                    $this->rowReport = $this->rowReport + $Array['rows'];
                }
                break;
            case 'Block' :
                if ($this->conditionProcessing($Array['if'])){
                    $this->copyBlockPattern($Array);
                    $this->rowReport = $this->rowReport + $Array['rows'];
                }
                break;
        }

        foreach ($Array as $key => $newArray){
            if (is_array($newArray))
                $this->arrayProcessing($newArray);
        }

        if (array_key_exists("rowsInTable",$Array)){
            if ($Array['rowsInTable']!=0)
                $this->rowReport =  $this->rowReport + ($Array['rowsInTable']-($this->rowReport-$row));
        }

        if (array_key_exists("if",$Array) && $Array['type']=='Table'){
            if ($this->conditionProcessing($Array['if']) && $this->notEndOfArray($Array['nameBlock'])){
                $this->nextData($Array['nameBlock']);
                $this->blockProcessing($Array);
            }
        }
    }


    /**
     * @param $Array
     * @return bool
     */
    private function groupProcessing($Array)
    {
        $ret = false;
        $nameTable = $Array['if'];
        $nameBlock = $Array['nameBlock'];
        //$arr['t1']['groupName']['SUM']['name']='value';

        if ($this->{'tStart'.$nameTable} === true){
            $this->{'initGroup'.$nameTable.$nameBlock} = true;
        }


        if ($this->{'initGroup'.$nameTable.$nameBlock} === true){
            $this->initGroup($Array);
        }else{
            $this->fillingInGroupFields($Array);
        }

        if ($this->checkNextRecord($Array)){ // если следующая запись не равна текущей либо пустая
            $this->{'initGroup'.$nameTable.$nameBlock} = true;
            $ret = true;
        }

        if (!$this->notEndOfArray($nameTable)){
            $ret = true;
        }

        return $ret;
    }



    private function fillingInGroupFields($Array)
    {
        $nameTable = $Array['if'];
        $nameBlock = $Array['nameBlock'];
        $table = $this->{$nameTable};
        if (is_array($this->group[$nameTable][$nameBlock])===true) {
            foreach ($this->group[$nameTable][$nameBlock] as $aggregateFunction => $arrayVariable) {
                foreach ($arrayVariable as $variable => $value) {
                    if ($aggregateFunction == 'SUM') {
                        $value = $value + $table[$variable];
                        $this->group[$nameTable][$nameBlock][$aggregateFunction][$variable] = $value;
                    }
                    if ($aggregateFunction == 'MIN') {
                        if ($value > $table[$variable]) $value = $table[$variable];
                        $this->group[$nameTable][$nameBlock][$aggregateFunction][$variable] = $value;
                    }
                    if ($aggregateFunction == 'MAX') {
                        if ($value < $table[$variable]) $value = $table[$variable];
                        $this->group[$nameTable][$nameBlock][$aggregateFunction][$variable] = $value;
                    }
                }
            }
        }
    }


    private function checkNextRecord($Array)
    {
        $ret = False;
        $nameTable = $Array['if'];
        $nameBlock = $Array['nameBlock'];
        $dataThis = $this->{$nameTable};
        $dataNext = $this->{'next'.$nameTable};
        if ($dataNext === false){ // если следующая строка это конец массива
            $ret = true;
        }else{
            if ($dataNext[$nameBlock]!=$dataThis[$nameBlock]){
                $ret = true;
            }
        }

        return $ret;
    }


    /**
     * @param $Array
     */
    private function initGroup($Array)
    {
        $nameTable = $Array['if'];
        $nameBlock = $Array['nameBlock'];
        $rowData = $this->{$nameTable};

        for($row = $Array['row']; $row < $Array['row']+$Array['rows']; $row++){
            for($col = 1; $col<=$this->widthReport; $col++){
                $value = $this->getCellValue($col,$row);
                $aggregateFunction = mb_substr($value,1,3);
                $regexp = "/'[\w\s]+'/ui";
                preg_match($regexp,$value,$arr);
                $variable = str_replace("'","",$arr[0]);
                $variable = str_replace('"',"",$variable);
                switch ($aggregateFunction){
                    case 'SUM':
                    case 'MIN':
                    case 'MAX':
                        $this->group[$nameTable][$Array['nameBlock']][$aggregateFunction][$variable]=$rowData[$variable];
                        break;
                }
            }
        }
        $this->{'initGroup'.$nameTable.$nameBlock} = false;
    }

    /**
     * @param $Array
     */
    private function copyBlockPattern($Array)
    {
        $sheet = $this->SheetPattern;
        $srcRowStart = $Array['row'];
        $srcRowEnd = $Array['row'] + $Array['rows'] - 1;

        $srcColumnEnd = $this->widthReport;
        $destRowStart = $this->rowReport;
        $destSheet = $this->SheetResult;

        if ($Array['type'] == 'Group'){
            $nameTable = $Array['if'];
            $nameBlock = $Array['nameBlock'];
            if (is_array($this->group[$nameTable][$nameBlock])===true){
                foreach ($this->group[$nameTable][$nameBlock] as $aggregateFunction => $arrayVariable) {
                    foreach ($arrayVariable as $variable => $value){
                     //   print "\$this->\{$aggregateFunction.'_'.$nameTable\}[$variable] = $value </br>";
                        ${$aggregateFunction.'_'.$nameTable}[$variable] = $value;

                    }
                }
            }
        }
        ${$this->activTable} = $this->{$this->activTable};

        $srcColumnStart = 1;
        $destColumnStart = 1;

        $rowCount = 0;
        for ($row = $srcRowStart; $row <= $srcRowEnd; $row++) {
            $colCount = 0;
            for ($col = $srcColumnStart; $col <= $srcColumnEnd; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $style = $sheet->getStyleByColumnAndRow ($col, $row);
                $val='';
                $dstCell = Coordinate::stringFromColumnIndex($destColumnStart + $colCount) . (string)($destRowStart + $rowCount);
                $val1 = $cell->getValue();
                if (strpos($val1, '$')===false){
                    $val1 = "'".$val1."'";
                }
                $command = '$val = '.$val1.';';
                eval($command);
                $destSheet->setCellValue($dstCell, $val);
                $destSheet->duplicateStyle($style, $dstCell);


                $colCount++;
            }

            $h = $sheet->getRowDimension($row)->getRowHeight();
            $destSheet->getRowDimension($destRowStart + $rowCount)->setRowHeight($h);

            $rowCount++;
        }

        foreach ($sheet->getMergeCells() as $mergeCell) {
            $mc = explode(":", $mergeCell);
            $mergeColSrcStart = Coordinate::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[0]));
            $mergeColSrcEnd = Coordinate::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[1]));
            $mergeRowSrcStart = ((int)preg_replace("/[A-Z]*/", "", $mc[0]));
            $mergeRowSrcEnd = ((int)preg_replace("/[A-Z]*/", "", $mc[1]));

            $relativeColStart = $mergeColSrcStart - $srcColumnStart;
            $relativeColEnd = $mergeColSrcEnd - $srcColumnStart;
            $relativeRowStart = $mergeRowSrcStart - $srcRowStart;
            $relativeRowEnd = $mergeRowSrcEnd - $srcRowStart;

            if (0 <= $mergeRowSrcStart && $mergeRowSrcStart >= $srcRowStart && $mergeRowSrcEnd <= $srcRowEnd) {
                $targetColStart = Coordinate::stringFromColumnIndex($destColumnStart + $relativeColStart);
                $targetColEnd = Coordinate::stringFromColumnIndex($destColumnStart + $relativeColEnd);
                $targetRowStart = $destRowStart + $relativeRowStart;
                $targetRowEnd = $destRowStart + $relativeRowEnd;

                $merge = (string)$targetColStart . (string)($targetRowStart) . ":" . (string)$targetColEnd . (string)($targetRowEnd);
                //Merge target cells
                $destSheet->mergeCells($merge);
            }
        }
    }

    /**
     * @param $Array
     */
    private function initStartReadFromTable($Array)
    {
        //count($b)

        $this->{'tStart'.$Array['nameBlock']} = true;
    }

    /**
     * @param $nameArray
     */
    private function prepareData($nameArray)
    {
        $this->activTable = $nameArray;
        $this->{$nameArray} = current($this->{'t'.$nameArray});
        $this->{'next'.$nameArray} = next($this->{'t'.$nameArray});
        prev($this->{'t'.$nameArray});
    }

    /**
     * @param $nameArray
     */
    private function nextData($nameArray)
    {
        next($this->{'t'.$nameArray});
        $this->{'notEnd_'.$nameArray} = key($this->{'t'.$nameArray})!==Null ? true : false;
        $this->{'tStart'.$nameArray} = false;
    }

    /**
     * @param $nameArray
     * @return mixed
     */
    private function notEndOfArray($nameArray)
    {
        return $this->{'notEnd_'.$nameArray};
    }

    /**
     * @param $condition
     * @return bool
     */
    private function conditionProcessing($condition)
    {
        $ret = true;
        $command = '$ret = '.$condition.' ? true : false ;';
        eval($command);
        return $ret;
    }

    /**
     * @param $array
     * @return bool
     */
    private function checkArrayElements($array)
    {
        $res = true;
        if (!array_key_exists("nameBlock",$array)) $res = false;
        if (!array_key_exists("type",$array)) {
            $res = false;
        }else{
            if ($array['type']=='Table')
                if (!array_key_exists("rowsInTable",$array)){
                    $res = false;
                }
        }
        if (!array_key_exists("row",$array)) $res = false;
        if (!array_key_exists("rows",$array)) $res = false;
        if (!array_key_exists("if",$array)) $res = false;

        return $res;
    }


    /**
     * @return bool
     */
    private function detectCommand()
    {
        $this->rowReportRead = 1;
        $this->nomCol = 1;        $arrayNomCol = Array(1=>$this->widthReport+2,2=>$this->widthReport+2+4,3=>$this->widthReport+2+7);
        $str = '{';
        $exit = false;
        while (!$exit){
                $cell = $this->getCellValue($arrayNomCol[$this->nomCol],$this->rowReportRead);
                if ($cell!=null){
                    $nameNew = $this->getCellValue($arrayNomCol[$this->nomCol]+1,$this->rowReportRead);
                    $rows = $this->getCellValue($arrayNomCol[$this->nomCol]+2,$this->rowReportRead);
                    $if = $this->getCellValue($arrayNomCol[$this->nomCol]+3,$this->rowReportRead);
                    switch ($cell) {
                        case "Table":
                        case "Group":
                        case "Block":
                            $table = Array();
                            $str1 = '';
                            $str1 = $str1.'"nameBlock":"'.$nameNew.'",';
                            $str1 = $str1.'"type":"'.$cell.'",';
                            $str1 = $str1.'"row":'.$this->rowReportRead.',';
                            $str1 = $str1.'"rows":'.$rows.',';
                            $str1 = $str1.'"if":"'.$if.'",';
                            $str1 = '"'.$nameNew.'":{'.$str1;
                            $str = $str.$str1;
                            break;
                    }

                    switch ($cell) {
                        case "EndTable":
                        case "EndGroup":
                        case "EndBlock":
                            $rowsInTable = $this->getCellValue($arrayNomCol[$this->nomCol]+2,$this->rowReportRead);
                            $str1 = '';
                            if ($cell=='EndTable'){
                                $str1 = $str1.'"rowsInTable":"'.$rowsInTable.'",';
                            }
                            $str = $str.$str1;
                            if (substr($str,-1)==','){
                                $str = substr($str,0,strlen($str)-1);
                            }
                            $str = $str.'},';
                            break;
                    }
                }
                $this->nextCol();
                if ($this->getCellValue($this->widthReport+2,$this->rowReportRead)=='EndReport') $exit = true;
                if ($this->rowReportRead>100) $exit = true;
            }
        if (substr($str,-1)==','){
            $str = substr($str,0,strlen($str)-1);
        }

        $str = $str.'}';
        $this->table = json_decode($str,true);

        if ( is_Array($this->table)){
            return true;
        }else {
            return false;
        }
    }


    /**
     *  Смещение колонки Для чениея из шаблона в массив
     *  для detectCommand()
     */
    private function nextCol()
    {
        $this->nomCol = $this->nomCol + 1;
        if ($this->nomCol>3) {
            $this->rowReportRead = $this->rowReportRead + 1;
            $this->nomCol = 1;
        }
    }


    /**
     *
     */
    public function defineSheetResult()
    {
        $this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->SheetResult = $this->spreadsheet->getActiveSheet();
    }


    /**
     *
     */
    public function openFile()
    {
        $BoockPattern = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $BoockPattern->setReadDataOnly(false);
        $loadFile = "$this->excelPatternPath/$this->excelPatternName";
        $this->BoockPattern = $BoockPattern->load($loadFile);
        $this->BoockPattern->setActiveSheetIndex(0);
        $this->SheetPattern = $this->BoockPattern->getActiveSheet();
        //print_r($this->SheetPattern->getStyle("A2")->getFont()->setSize(11));
        //print "</br>";
    }


    /**
     *
     */
    public function saveFile()
    {
        $fileName = "$this->resultFilePath/$this->resultFileName.xlsx";
        unlink ($fileName);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, "Xlsx");
//        $writer->setPreCalculateFormulas(false);
        $writer->save($fileName);
/*
        $fileName = "$this->resultFilePath/$this->resultFileName.pdf";
        unlink ($fileName);
        $spreadsheet = $this->spreadsheet;
//        $writer1 = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf($spreadsheet);
        //$writer1 = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
        $writer1= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Mpdf');
        //$writer1->writeAllSheets();
        //$writer1->setPreCalculateFormulas(false);
        \models\ErrorLog::saveError("0001");
        $writer1->save($fileName);
        \models\ErrorLog::saveError("0002");
*/
    }


    /**
     *
     */
    private function copyHeadFile()
    {
        $this->SheetResult->getPageSetup()->setFitToPage(           $this->SheetPattern->getPageSetup()->getFitToPage()) ;
        $this->SheetResult->getPageSetup()->setScale(               $this->SheetPattern->getPageSetup()->getScale()) ;
        $this->SheetResult->getPageSetup()->setFitToWidth(          $this->SheetPattern->getPageSetup()->getFitToWidth()) ;
        $this->SheetResult->getPageSetup()->setFitToHeight(         $this->SheetPattern->getPageSetup()->getFitToHeight()) ;
        $this->SheetResult->getPageSetup()->setOrientation(         $this->SheetPattern->getPageSetup()->getOrientation()) ;
        $this->SheetResult->getPageSetup()->setHorizontalCentered(  $this->SheetPattern->getPageSetup()->getHorizontalCentered()) ;
        $this->SheetResult->getPageSetup()->setVerticalCentered(    $this->SheetPattern->getPageSetup()->getVerticalCentered()) ;


        $this->SheetResult->getPageMargins()->setTop(       $this->SheetPattern->getPageMargins()->getTop()) ;
        $this->SheetResult->getPageMargins()->setBottom(    $this->SheetPattern->getPageMargins()->getBottom()) ;
        $this->SheetResult->getPageMargins()->setFooter(    $this->SheetPattern->getPageMargins()->getFooter()) ;
        $this->SheetResult->getPageMargins()->setHeader(    $this->SheetPattern->getPageMargins()->getHeader()) ;
        $this->SheetResult->getPageMargins()->setLeft(      $this->SheetPattern->getPageMargins()->getLeft()) ;
        $this->SheetResult->getPageMargins()->setRight(     $this->SheetPattern->getPageMargins()->getRight()) ;
    }


    /**
     *
     */
    private function detectWidthReport()
    {

        $col = 1;
        while (($col<1024)&&($this->getCellValue($col,1)<>"Width")){
            $this->SheetResult->getColumnDimension($this->IntColumnToStr($col))->setWidth(
                $this->SheetPattern->getColumnDimension($this->IntColumnToStr($col))->getWidth());
            $col  = $col + 1;
        }
        $this->widthReport = $col-1;
    }


    /**
     * @param $cellOrCol
     * @param null $row
     * @return mixed
     */
    private function getCellValue($cellOrCol, $row = null)
    {
        //column set by index
        if(is_numeric($cellOrCol)) {
            $cell = $this->SheetPattern->getCellByColumnAndRow($cellOrCol, $row);
        } else {
            $lastChar = substr($cellOrCol, -1, 1);
            if(!is_numeric($lastChar)) { //column contains only letter, e.g. "A"
                $cellOrCol .= $row;
            }

            $cell = $this->SheetPattern->getCell($cellOrCol);
        }
        $val = $cell->getValue();
        return $val;
    }


    /**
     * @param $cellOrCol
     * @param null $row
     * @param string $value
     *
     */
    private function setCellValue($cellOrCol, $row = null,$value = '')
    {
        //column set by index
        if(is_numeric($cellOrCol)) {
            $cell = $this->SheetResult->getCellByColumnAndRow($cellOrCol, $row);
        } else {
            $lastChar = substr($cellOrCol, -1, 1);
            if(!is_numeric($lastChar)) { //column contains only letter, e.g. "A"
                $cellOrCol .= $row;
            }

            $cell = $this->SheetResult->getCell($cellOrCol);
        }
        $cell->setValue($value);
    }

    /**
     * @param $columnInt
     * @return mixed|string
     */
    private function IntColumnToStr($columnInt)
    {
        $char = Array(0=>'',1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
        if ($columnInt<=26){
            $res = $char[$columnInt];
        }else{
            $del = $columnInt/26;
            $del =  intval (floor($del));
            $ostDel = ($columnInt%26);
            $res = $char[$del].$char[$ostDel];
        }

        return $res;
    }

    /**
     * @param Worksheet $sheet
     * @param $srcRowStart
     * @param $srcRowEnd
     * @param $width
     * @param $destRowStart
     * @param Worksheet|null $destSheet
     */
    public function copyRows( Worksheet $sheet, $srcRowStart, $srcRowEnd,$width,$destRowStart, Worksheet $destSheet = null) {


    }

    /**
     * @param Spreadsheet $sourceSheet
     * @param Spreadsheet $destSheet
     */
    public function copyStyleXFCollection(Spreadsheet $sourceSheet, Spreadsheet $destSheet) {
        $collection = $sourceSheet->getCellXfCollection();

        foreach ($collection as $key => $item) {
            $destSheet->addCellXf($item);
        }
    }
}