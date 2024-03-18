<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 06.05.2020
 * Time: 20:52
 */

namespace forms;


abstract class FormsModel
{
    public $filter;
    public $fields;
    public $table;

    /**
     * @return Object PDO содержаший данные выборки
     */
    abstract protected function getData();

}