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

use AndyDune\StringReplace\PowerReplace;
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


        $instance = new SimpleReplace();
        $instance->setMarkerTemplate('_%s_');
        $instance->one = 'one_ok';
        $instance->two = 'two_ok';

        $string = 'Gogoriki go _one_ and _two_';
        $this->assertEquals('Gogoriki go one_ok and two_ok', $instance->replace($string));
    }

    public function testPower()
    {

        $instance = new PowerReplace();
        $instance->one = 'one_ok';
        $instance->two = 'two_ok';
        $this->assertEquals(['one' => 'one_ok', 'two' => 'two_ok'], $instance->getArrayCopy());


        $string = 'Gogoriki go #ONE# and #two#';
        $this->assertEquals('Gogoriki go one_ok and two_ok', $instance->replace($string));

        $string = 'Gogoriki go #ONE# and #two# and #three#';
        $this->assertEquals('Gogoriki go one_ok and two_ok and ', $instance->replace($string));

        $instance->setMarkerTemplate('_\(([^)]+)\)');
        $string = 'Gogoriki go _(ONE) and _(two)';
        $this->assertEquals('Gogoriki go one_ok and two_ok', $instance->replace($string));


        $string = 'Gogoriki go #ONE:escape#';
        $instance = new PowerReplace();
        $instance->one = '<b>one_ok</b>';
        $this->assertEquals('Gogoriki go &lt;b&gt;one_ok&lt;/b&gt;', $instance->replace($string));

        $string = 'I know #it##and_it:addcomma#';
        $instance = new PowerReplace();
        $instance->it = 'eat';
        $instance->and_it = 'sleep';
        $this->assertEquals('I know eat, sleep', $instance->replace($string));


        $instance = new PowerReplace();
        $instance->it = 'eat';
        $this->assertEquals('I know eat', $instance->replace($string));


        $string = 'I know #it##and_it:maxlen():addcomma#';
        $instance = new PowerReplace();
        $instance->it = 'eat';
        $instance->and_it = 'sleep sleep sleep sleep';
        $this->assertEquals('I know eat, sleep sleep sleep sleep', $instance->replace($string));

        $string = 'I know #it##and_it:maxlen(5):addcomma#';
        $instance = new PowerReplace();
        $instance->it = 'eat';
        $instance->and_it = 'sleep sleep sleep sleep';
        $this->assertEquals('I know eat', $instance->replace($string));

    }
}