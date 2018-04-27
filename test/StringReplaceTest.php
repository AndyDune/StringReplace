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


namespace AndyDuneTest\StringReplace;
use AndyDune\StringReplace\SimpleReplace;
use PHPUnit\Framework\TestCase;


class StringReplaceTest extends TestCase
{
    public function testSimple()
    {
        $instance = new SimpleReplace();
        $instance->one = 'one_ok';
        $instance->two = 'two_ok';
        $this->assertEquals(['one' => 'one_ok', 'two' => 'two_ok'], $instance->getArrayCopy());

        $string = 'Gogoriki go #one# and #two#';
        $this->assertEquals('Gogoriki go one_ok and two_ok', $instance->replace($string));

        $string = 'Gogoriki go #ONE# and #two#';
        $this->assertEquals('Gogoriki go #ONE# and two_ok', $instance->replace($string));

    }
}