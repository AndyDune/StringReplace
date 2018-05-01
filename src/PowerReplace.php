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

    protected $functionsHolder = null;

    public function __construct($functionsHolder = null)
    {
        if ($functionsHolder instanceof FunctionsHolder) {
            $this->functionsHolder = $functionsHolder;
        } else if ($functionsHolder === null) {
            $this->functionsHolder = new FunctionsHolder();
        }
    }

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
        $self = $this;
        return preg_replace_callback($this->markerTemplate, function ($marches) use ($self) {
            return $self->callbackReplace($marches[1]);
        }, $string);
    }


    public function callbackReplace($key)
    {
        $key = strtolower($key);

        $functionsHolder = $this->functionsHolder;
        $functions = [];
        if ($functionsHolder) {
            do {
                $fount = preg_match('|([_a-z0-9]+)\(([_a-z0-9]+)\)|ui', $key, $marches);
                if (!$fount) {
                    break;
                }
                $functions[] = $marches[1];
                $key = $marches[2];
            } while (true);
        }

        if (array_key_exists($key, $this->dataToReplace)) {
            $value = $this->dataToReplace[$key];
            if ($functions) {
                array_walk($functions, function ($function, $key) use (&$value, $functionsHolder) {
                    $value = call_user_func([$functionsHolder, $function], $value);
                });
            }

            return $value;
        }
        return '';
    }

    /**
     * Set whole data array.
     *
     * @param $array
     * @return SimpleReplace
     */
    public function setArray($array)
    {
        $self = $this;
        array_walk($array, function ($value, $key) use ($self) {
            $self->set($key, $value);
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