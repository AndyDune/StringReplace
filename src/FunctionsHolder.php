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
use AndyDune\StringReplace\Functions\Postfix;
use AndyDune\StringReplace\Functions\Prefix;
use AndyDune\StringReplace\Functions\SetCommaBefore;
use AndyDune\StringReplace\Functions\PluralStringEnglish;
use AndyDune\StringReplace\Functions\PrintFormatted;
use AndyDune\StringReplace\Functions\ShowIfOtherValueNotEmpty;
use AndyDune\StringReplace\Functions\ShowStringIfValueEqualTo;
use Exception;

class FunctionsHolder extends FunctionHolderAbstract
{
    protected $functions = [
        'escape' => EscapeHtmlSpecialChars::class,
        'prefix' => Prefix::class,
        'postfix' => Postfix::class,
        'addcomma' => SetCommaBefore::class,
        'maxlen' => LeaveStringWithLength::class,
        'pluralrus' => PluralStringRussian::class,
        'plural' => PluralStringEnglish::class,
        'printf' => PrintFormatted::class,
        'showifothernotempty' => ShowIfOtherValueNotEmpty::class,
        'showifequal' => ShowStringIfValueEqualTo::class
    ];

    public function executeFunction($name, $arguments)
    {
        $function = $this->getFunctionWithName($name);
        if ($function) {
            return call_user_func_array($this->getFunctionWithName($name), $arguments);
        }
        return $arguments[0];
        //throw new Exception(sprintf('Functions %s does not exist', $name));
    }

    protected function getFunctionWithName($name)
    {
        $name = strtolower($name);
        if (array_key_exists($name, $this->functions)) {
            $function = $this->functions[$name];
            if (is_string($function)) {
                $function = new $function;
                $this->functions[$name] = $function;
            }

            if ($this->stringContainer and $function instanceof FunctionAbstract) {
                $function->setStringContainer($this->stringContainer);
            }

            return $function;
        }
        return false;
        //throw new Exception(sprintf('Functions %s does not exist', $name));
    }


    /**
     * Clear accumulated values for all created function instances.
     */
    public function clear()
    {
        foreach ($this->functions as $function) {
            if (is_object($function) and ($function instanceof FunctionAbstract)) {
                $function->clear();
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