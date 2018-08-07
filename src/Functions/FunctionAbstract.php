<?php
/**
 * PHP version >= 5.6
 *
 * @package andydune/string-replace
 * @link  https://github.com/AndyDune/StringReplace for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\StringReplace\Functions;
use AndyDune\StringReplace\PowerReplace;

abstract class FunctionAbstract
{

    /**
     * @var PowerReplace
     */
    protected $stringContainer = null;

    /**
     * @return PowerReplace
     */
    public function getStringContainer()
    {
        return $this->stringContainer;
    }

    /**
     * @param PowerReplace $stringContainer
     * @return $this
     */
    public function setStringContainer(PowerReplace $stringContainer)
    {
        $this->stringContainer = $stringContainer;
        return $this;
    }


    abstract public function __invoke($string);

    /**
     * Functions can accumulate some values for single string for handle.
     * Useful for clear values in function objects.
     */
    public function clear()
    {

    }
}