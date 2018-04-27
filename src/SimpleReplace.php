<?php
/**
 *
 * PHP version >= 5.6
 *
 * @package andydune/string-replace
 * @link  https://github.com/AndyDune/StringReplace for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\StringReplace;


class SimpleReplace
{
    protected $dataToReplace = [];

    protected $markerTemplate = '#%s#';

    /**
     * Execute replace procedure.
     *
     * @param string $string
     * @return string
     */
    public function replace($string)
    {
        $dataArray = $this->getPreparedArray();
        return str_replace(array_keys($dataArray), $dataArray, $string);
    }


    public function setMarkerTemplate($string)
    {
        $this->markerTemplate = $string;
        return $this;
    }

    /**
     * Set whole data array.
     *
     * @param $array
     * @return SimpleReplace
     */
    public function setArray($array)
    {
        $this->dataToReplace = $array;
        return $this;
    }

    public function getArrayCopy()
    {
        return $this->dataToReplace;
    }

    public function __set($name, $value)
    {
        $this->dataToReplace[$name] = $value;
    }

    protected function getPreparedArray()
    {
        $preparedArray = [];
        $markerTemplate = $this->markerTemplate;
        array_walk($this->dataToReplace, function ($value, $key) use (&$preparedArray, $markerTemplate) {
            $key = sprintf($markerTemplate, $key);
            $preparedArray[$key] = $value;
        });
        return $preparedArray;
    }
}