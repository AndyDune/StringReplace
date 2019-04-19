# StringReplace

[![Build Status](https://travis-ci.org/AndyDune/StringReplace.svg?branch=master)](https://travis-ci.org/AndyDune/StringReplace)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/andydune/string-replace.svg?style=flat-square)](https://packagist.org/packages/andydune/string-replace)
[![Total Downloads](https://img.shields.io/packagist/dt/andydune/string-replace.svg?style=flat-square)](https://packagist.org/packages/andydune/string-replace)


Replace in given string meta data with real data.

Requirements
------------

PHP version >= 5.6

Installation
------------

Installation using composer:

```
composer require andydune/string-replace
```
Or if composer was not installed globally:
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

Functions can get parameters: `#CODE:maxlen(10)#` or `#CODE:maxlen("10")#` 

Symbols: __:__ __(__ __)__ __,__ __"__ __'__ are reserved to use as parameters for function. 
So if you want to use it you mast encase it with quotes (or single quotes).


This is correct usage:
```php
$string = "Params: #weight:prefix(\"'\"):postfix('"')#";
$string = "Params: #weight:prefix(\":\"):postfix(':')#";
$string = "Params: #weight:prefix(\"(\"):postfix(')')#";
$string = "Params: #weight:prefix(\", \"):postfix(', ')#";
```

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

### plural

Pluralize the title for number.
```php
$string = 'I see #count# #count:plural(man, men)#';
$instance = new PowerReplace();
$instance->count = 1;
$instance->replace($string); // I see 1 man
$instance->count = 21;
$instance->replace($string); // I see 21 men
```


### pluralrus

Russian pluralize the title for number.
```php
$string = 'У меня есть #count# #count:pluralrus(яблоко, яблока, яблок)#';
$instance = new PowerReplace();

$instance->count = 1;
$instance->replace($string)); // У меня есть 1 яблоко

$instance->count = 21;
$instance->replace($string); // У меня есть 21 яблоко

$instance->count = 2;
$instance->replace($string); // У меня есть 2 яблока

$instance->count = 5;
$instance->replace($string); // У меня есть 5 яблок
```

### prefix

It shows given string as prefix only if value behind the key is not empty.
```php
$string = 'Vegetables I have: #apple_count:prefix("apples "):addcomma(1)##orange_count:prefix("oranges "):addcomma(1)#';
$instance = new PowerReplace();
$instance->apple_count = 1;
$instance->replace($string); // Vegetables I have: apples 1
```

### postfix

It shows given string as postfix only if value behind the key is not empty.
```php
$string = 'Params: #weight:prefix("weight: "):postfix(kg)##growth:prefix("growth: "):postfix(sm):addcomma#';
$instance = new PowerReplace();
$instance->weight = 80;
$instance->growth = 180;
$instance->replace($string); // Params: weight: 80kg, growth: 180sm

```

### showIfEqual

It shows string given in second param if first param is equal to value behind the placeholder.
```php
$string = 'Anton #weight:showIfEqual(80, "has normal weight")##weight:showIfEqual(180, "has obesity")#.';
$instance = new PowerReplace();
$instance->weight = 80;
$instance->replace($string); // Anton has normal weight.

$string = 'Anton #weight:showIfEqual(80, "has normal weight")##weight:showIfEqual(180, "has obesity")#.';
$instance = new PowerReplace();
$instance->weight = 180;
$instance->replace($string); // Anton has obesity.
```


### showIfOtherValueNotEmpty

It shows string a value behind the current placeholder if another is not empty.

```php
$string = 'Variants #type[name]:showIfOtherNotEmpty(type[value])##type[value]:prefix(": ")#';
$instance = new PowerReplace();
$instance->setArray(['type'=> ['name' => 'color', 'value' => 'green']]);
$instance->replace($string); // Variants color: green
```

## Custom Functions

Yup can add own function with replace rules. Markers and function are nor case sensitive.

```php

$string = 'Where is #word:leftAndRight(_)#?';
// or the same
$string = 'Where is #WORD:LEFTANDRIGHT(_)#?';

$functionHolder = new FunctionsHolder();

// add custom function with name leftAndRight
$functionHolder->addFunction('leftAndRight', function ($string, $symbol = '') {
    return $symbol . $string . $symbol;
});
$instance = new PowerReplace($functionHolder);
$instance->word = 'center';
$instance->replace($string); // Where is _center_?

```
