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


class SetCommaBefore extends FunctionAbstract
{
    protected $count = 0;

    public function __invoke($string, $notForFirst = false, $clean = false)
    {
        if ($clean) {
            $this->clean();
        }
        if ($string) {
            $this->count++;
            if ($notForFirst and $this->count == 1) {
                return $string;
            }
            return ', ' . $string;
        }
        return '';
    }

    public function clean()
    {
        $this->count = 0;
    }

}