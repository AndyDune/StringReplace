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
use Exception;

class FunctionsHolder
{
    protected $functions = [
        '_' => 'htmlSpecialChars',
        'addcomma' => 'setCommaBefore'
    ];

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->functions)) {
            return call_user_func_array([$this, $this->functions[$name]], $arguments);
        }
        throw new Exception(sprintf('Functions %s does not exist', $name));
    }

    public function htmlSpecialChars($string)
    {
        return htmlspecialchars($string);
    }

    public function setCommaBefore($string)
    {
        return ', ' . $string;
    }

}