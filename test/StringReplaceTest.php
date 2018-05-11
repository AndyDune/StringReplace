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

use AndyDune\StringReplace\FunctionsHolder;
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


        $string = 'I know words: #it:addcomma(1)##and_it:addcomma(1)##and_it_2:addcomma(1)#';
        $instance = new PowerReplace();
        $instance->it = 'eat';
        $instance->and_it = 'sleep';
        $this->assertEquals('I know words: eat, sleep', $instance->replace($string));

        $instance = new PowerReplace();
        $instance->and_it_2 = 'drink';
        $instance->and_it = 'sleep';
        $this->assertEquals('I know words: sleep, drink', $instance->replace($string));


        $string = 'I know #it##and_it:addcomma#';
        $instance = new PowerReplace();
        $instance->it = 'eat';
        $this->assertEquals('I know eat', $instance->replace($string));


        $string = 'I know words: #it:addcomma(1)##and_it:addcomma(1)# and #and_it_2:addcomma(1, 1)#';
        $instance = new PowerReplace();
        $instance->it = 'eat';
        $instance->and_it_2 = 'sleep';
        $this->assertEquals('I know words: eat and sleep', $instance->replace($string));



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


        $string = 'У меня есть #count# #count:pluralrus(яблоко, яблока, яблок)#';
        $instance = new PowerReplace();
        $instance->count = 1;
        $this->assertEquals('У меня есть 1 яблоко', $instance->replace($string));
        $instance->count = 21;
        $this->assertEquals('У меня есть 21 яблоко', $instance->replace($string));

        $instance->count = 2;
        $this->assertEquals('У меня есть 2 яблока', $instance->replace($string));
        $instance->count = 23;
        $this->assertEquals('У меня есть 23 яблока', $instance->replace($string));

        $instance->count = 5;
        $this->assertEquals('У меня есть 5 яблок', $instance->replace($string));
        $instance->count = 11;
        $this->assertEquals('У меня есть 11 яблок', $instance->replace($string));


        // Can use " or ' with saving white spaces inside
        $string = 'У меня есть #count##count:pluralrus(" яблоко ", " яблока ", " яблок ")#';
        $instance = new PowerReplace();
        $instance->count = 1;
        $this->assertEquals('У меня есть 1 яблоко ', $instance->replace($string));

        $string = 'У меня есть #count##count:pluralrus(\' яблоко \', \' яблока \', \' яблок \')#';
        $instance = new PowerReplace();
        $instance->count = 21;
        $this->assertEquals('У меня есть 21 яблоко ', $instance->replace($string));

    }

    public function testPowerAddCustomFunction()
    {
        $string = 'Where is #word:leftAndRight(_)#?';

        $functionHolder = new FunctionsHolder();
        $functionHolder->addFunction('leftAndRight', function ($string, $symbol = '') {
            return $symbol . $string . $symbol;
        });
        $instance = new PowerReplace($functionHolder);
        $instance->word = 'center';
        $this->assertEquals('Where is _center_?', $instance->replace($string));

    }
}