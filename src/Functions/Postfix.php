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


class Postfix
{
    public function __invoke($string, $postfix = '')
    {
        if ($string) {
            return $string . $postfix;
        }
        return $string;
    }

}