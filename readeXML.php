<?php
/**
 * Created by PhpStorm.
 * User: seryakovda
 * Date: 03.03.2021
 * Time: 15:42
 */
$reader = new XMLReader();
$reader->open('analys.XML');

$xmlarr = array();
$idx = 0;
while ($reader->read()) {

    print_r($reader->value);
    if (($reader->nodeType == XMLReader::ELEMENT) && ($reader->name == 'Element1')) {
        // считываем атрибуты
        $xmlarr[$idx]['Attr1'] = $reader->getAttribute('Attr1');
        while ($reader->read()) {
            // разбираем вложенные элементы
            if (($reader->nodeType == XMLReader::ELEMENT) && ($reader->name == 'Element11')) {
                while ($reader->read()) {
                    if ($reader->nodeType == XMLReader::TEXT) {
                        // получаем значение из свойства $reader->value;
                        $xmlarr[$idx][$reader->name] = $reader->value;
                    }
                    elseif (($reader->nodeType == XMLReader::ELEMENT) && ($reader->name == 'Element111')) {
                        // еще один вложенный элемент
                        while ($reader->read()) {
                            if ($reader->nodeType == XMLReader::TEXT) /* и т.д. */ {
                                $xmlarr[$idx]['Element11'][$reader->name] = $reader->value;
                            }
                            elseif (($reader->nodeType == XMLReader::END_ELEMENT) && ($reader->name == 'Element111')) {
                                break;
                            }
                        }
                    }
                    elseif (($reader->nodeType == XMLReader::END_ELEMENT) && ($reader->name == 'Element11')) {
                        break;
                    }
                }
            }
            elseif (($reader->nodeType == XMLReader::ELEMENT) && ($reader->name == 'Element12')) {
                while ($reader->read()) {
                    if ($reader->nodeType == XMLReader::TEXT) {
                        // ... = $reader->value;
                    }
                    elseif (($reader->nodeType == XMLReader::END_ELEMENT) && ($reader->name == 'Element12')) {
                        break;
                    }
                }
            }
            elseif (($reader->nodeType == XMLReader::END_ELEMENT) && ($reader->name == 'Element1')) {
                $idx += 1;
                break;
            }
        }
    }
}
print_r($xmlarr);