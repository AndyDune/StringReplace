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
use AndyDune\StringReplace\Functions\EscapeHtmlSpecialChars;
use AndyDune\StringReplace\Functions\FunctionAbstract;
use AndyDune\StringReplace\Functions\LeaveStringWithLength;
use AndyDune\StringReplace\Functions\PluralStringRussian;
use AndyDune\StringReplace\Functions\SetCommaBefore;
use Exception;

class FunctionsHolder
{
    protected $functions = [
        'escape' => EscapeHtmlSpecialChars::class,
        'addcomma' => SetCommaBefore::class,
        'maxlen' => LeaveStringWithLength::class,
        'pluralrus' => PluralStringRussian::class
    ];

    public function executeFunction($name, $arguments)
    {
        return call_user_func_array($this->getFunctionWithName($name), $arguments);
    }

    protected function getFunctionWithName($name)
    {
        if (array_key_exists($name, $this->functions)) {
            $function = $this->functions[$name];
            if (is_string($function)) {
                $function = new $function;
                $this->functions[$name] = $function;
            }
            return $function;
        }
        throw new Exception(sprintf('Functions %s does not exist', $name));
    }


    public function clean()
    {
        foreach ($this->functions as $function) {
            if (is_object($function) and ($function instanceof FunctionAbstract)) {
                $function->clean();
            }
        }
    }

    /**
     * Add custom function.
     *
     * @param string $name function statement in template
     * @param $function callable class name or closure
     * @return $this
     */
    public function addFunction($name , $function)
    {
        $this->functions[strtolower($name)] = $function;
        return $this;
    }

}