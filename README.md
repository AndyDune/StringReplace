# StringReplace

[![Build Status](https://travis-ci.org/AndyDune/StringReplace.svg?branch=master)](https://travis-ci.org/AndyDune/StringReplace)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/andydune/string-replace.svg?style=flat-square)](https://packagist.org/packages/andydune/string-replace)
[![Total Downloads](https://img.shields.io/packagist/dt/andydune/string-replace.svg?style=flat-square)](https://packagist.org/packages/andydune/string-replace)


Replace in given string meta data with real data.


Installation
------------

Installation using composer:

```
composer require andydune/string-replace
```
Or if composer didn't install globally:
```
php composer.phar require andydune/string-replace
```
Or edit your `composer.json`:
```
"require" : {
     "andydune/string-replace": "^1"
}

```
And execute command:
```
php composer.phar update
```


## SimpleReplace

It's very simple and lightweight replace. It uses `str_replace` function.

```php
use AndyDune\StringReplace\SimpleReplace;

$instance = new SimpleReplace();
$instance->one = 'one_ok';
$instance->two = 'two_ok';

$string = 'Gogoriki go #one# and #two#';
$instance->replace($string); // equals to 'Gogoriki go one_ok and two_ok' 

```

It is very simple and quick. The is no any logic in it and it will no replace statement if no data to replace.

## PowerReplace

It powerful replace class with string analytics with regular. 
There are many functions built-in lib and you may add custom easily.

### No case sensitive
```php
use AndyDune\StringReplace\PowerReplace;

$instance = new PowerReplace();
$instance->one = 'one_ok';
$instance->TWO = 'two_ok'; // upper key

$string = 'Gogoriki go #ONE# and #two#';
$instance->replace($string); // equals to 'Gogoriki go one_ok and two_ok' 

```

## Functions

Functions are described next to marker after `:` (you can change separator). 

Functions can get parameters: `#CODE:maxlen(10)#`

More then one function : `#CODE:maxlen(10):escape#`

### escape

Apply `htmlspecialchars` with inserted value.

```php
use AndyDune\StringReplace\PowerReplace;

$string = 'Gogoriki go #ONE:escape#';
$instance = new PowerReplace();
$instance->one = '<b>one_ok</b>';
$instance->replace($string);  // equals to 'Gogoriki go &lt;b&gt;one_ok&lt;/b&gt;'
```

### addcomma

It adds comma before inserted value if it is not empty.

```php
use AndyDune\StringReplace\PowerReplace;

$string = 'Gogoriki go #one##two:comma#';
$instance = new PowerReplace();
$instance->one = 'swim';
$instance->one = 'play';
$instance->replace($string);  // equals to 'Gogoriki go swim, play'


$string = 'Gogoriki go #one##two:comma#';
$instance = new PowerReplace();
$instance->one = 'swim';
$instance->replace($string);  // equals to 'Gogoriki go swim
```

`comma` function may get params: `comma(param1, param2)`

- *param1* set to `1` if you want to miss first comma appearance in string
- *param2* set to `1` if you want to begin new group of words for next missing  of first comma appearance in string

```php
$string = 'I know words: #it:addcomma(1)##and_it:addcomma(1)# and #and_it_2:addcomma(1, 1)#';
$instance = new PowerReplace();
$instance->setArray( 
    'it' => 'eat',
    'and_it' = 'play',
    'and_it_2' = 'sleep'
    );
$instance->replace($string);  // equals to 'I know words: eat, play and sleep'

```

### maxlen

Replace marker with value if string behind this one is less then poined in parameter.

```php
use AndyDune\StringReplace\PowerReplace;

$string = 'Gogoriki go #one##two:masxlen(5):addcomma#';
$instance = new PowerReplace();

$instance->one = 'swim';
$instance->one = 'play';
$instance->replace($string);  // equals to 'Gogoriki go swim, play'

$instance->one = 'swim';
$instance->one = 'play games';
$instance->replace($string);  // equals to 'Gogoriki go swim'
```

### printf

Print formatted string if it is not empty.

```php

$string = 'I know words: #it:printf(«%s»):addcomma(1)##and_it:printf(«%s»):addcomma(1)# and #and_it_2:printf(«%s»):addcomma(1, 1)#';
$instance = new PowerReplace();
$instance->it = 'eat';
$instance->and_it_2 = 'sleep';
$instance->replace($string); // equals to  I know words: «eat» and «sleep»


```
