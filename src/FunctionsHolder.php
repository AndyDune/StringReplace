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
use Exception;

class FunctionsHolder
{
    protected $functions = [
        'escape' => 'htmlSpecialChars',
        'addcomma' => 'setCommaBefore',
        'maxlen' => 'leaveStringWithLength',
        'pluralrus' => 'showPluralStringRussian'
    ];

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->functions)) {
            return call_user_func_array([$this, $this->functions[$name]], $arguments);
        }
        throw new Exception(sprintf('Functions %s does not exist', $name));
    }

    public function htmlSpecialChars($string)
    {
        return htmlspecialchars($string);
    }

    public function setCommaBefore($string)
    {
        if ($string) {
            return ', ' . $string;
        }
        return '';
    }

    public function showPluralStringRussian($string, $form1 = '', $form2 = '', $form3 = '')
    {
        $n = (int)$string;
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form3;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form3;
    }

    public function leaveStringWithLength($string, $length = 100)
    {
        if (mb_strlen ($string, 'UTF-8') > $length) {
            return '';
        }
        return $string;
    }

}