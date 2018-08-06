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

abstract class FunctionHolderAbstract
{
    /**
     * @var PowerReplace
     */
    protected $stringContainer = null;

    /**
     * @return null
     */
    public function getStringContainer()
    {
        return $this->stringContainer;
    }

    /**
     * @param null $stringContainer
     */
    public function setStringContainer(PowerReplace $stringContainer)
    {
        $this->stringContainer = $stringContainer;
        return $this;
    }

}