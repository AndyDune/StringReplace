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


class PowerReplace
{
    protected $dataToReplace = [];

    protected $markerTemplate = '|#([^#]+)#|ui';

    protected $markerFunctionsSeparator = ':';

    protected $functionsHolder = null;

    /**
     * PowerReplace constructor.
     * Set customized function holder.
     *
     *
     * @param null|bool|FunctionsHolder $functionsHolder
     */
    public function __construct($functionsHolder = null)
    {
        if ($functionsHolder instanceof FunctionsHolder) {
            $this->functionsHolder = $functionsHolder;
        } else if ($functionsHolder === null) {
            $this->functionsHolder = new FunctionsHolder();
        }
    }

    /**
     * Set marker search template.
     * Example:
     *   #([^#]+)#
     *   %([^%]+)%
     *
     * @param $string template for regular between statements '|' and '|ui'
     * @return $this
     */
    public function setMarkerTemplate($string)
    {
        $this->markerTemplate = '|' .$string . '|ui';
        return $this;
    }


    /**
     * Execute replace procedure.
     *
     * @param string $string
     * @return string
     */
    public function replace($string)
    {
        $this->functionsHolder->clear();
        return preg_replace_callback($this->markerTemplate, function ($marches) {
            return $this->callbackReplace($marches[1]);
        }, $string);
    }


    protected function callbackReplace($key)
    {
        $key = strtolower($key);

        $functionsHolder = $this->functionsHolder;

        if ($functionsHolder) {
            $functions = $this->explode($key, $this->markerFunctionsSeparator);
            $key = array_shift($functions);
        } else {
            $functions = false;
        }

        if (array_key_exists($key, $this->dataToReplace)) {
            $value = $this->dataToReplace[$key];
            if ($functions) {
                array_walk($functions, function ($function, $key) use (&$value, $functionsHolder) {
                    $count = preg_match('|([_a-z0-9]+)\(([^\)]*)\)|ui', $function, $marches);
                    if ($count) {
                        $function = $marches[1];
                        $params = $this->explode($marches[2]);
                    } else {
                        $params = [];
                    }
                    array_unshift($params, $value);
                    $value = $functionsHolder->executeFunction($function, $params);
                });
            }

            return $value;
        }
        return '';
    }

    protected function explode($string, $separator = ',')
    {
        $result = [];
        $array = explode($separator, $string);
        array_walk($array, function ($value) use (&$result) {
            $value = trim($value);
            if (substr($value, 0, 1) == '"') {
                $value = trim($value, '"');
            } else {
                $value = trim($value, '\'');
            }

            if ($value) {
                $result[] = $value;
            }
        });
        return $result;
    }

    /**
     * Set whole data array.
     *
     * @param $array
     * @return SimpleReplace
     */
    public function setArray($array)
    {
        array_walk($array, function ($value, $key) {
            $this->set($key, $value);
        });
        return $this;
    }

    public function getArrayCopy()
    {
        return $this->dataToReplace;
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function set($name, $value)
    {
        $name = strtolower($name);
        $this->dataToReplace[$name] = $value;
        return $this;
    }

}