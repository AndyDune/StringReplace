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

namespace AndyDune\StringReplace\Functions;


class ShowIfOtherValueNotEmpty extends FunctionAbstract
{
    public function __invoke($string, $key = '')
    {
        if (!$key) {
            return $string;
        }

        if ($this->getStringContainer()->getValueFromDataToReplace($key)) {
            return $string;
        }

        return '';
    }
}