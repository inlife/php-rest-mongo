# PHP MongoDB REST API

Simple PHP REST API, written for small university project. May be considered as example of using MongoDB with PHP.   

### Used technologies:
* PHP 5.4
* Composer 1.0
* MongoDB 3.0.6
* REST

### Buzzwords/Keywords:
PHP REST API, ODM via Docrine/MongoDB, Language parsing via Standford-NLP, Fuzzy, Time-Parser.

### Installation:

Clone:
```sh
$ git clone https://github.com/Inlife/php-rest-mongo.git
```

Install dependencies:
```sh
$ php composer.phar install
```

Start server:
```sh
$ php -S localhost:8080
```

### Example Code:
```php
<?php

namespace HotelBooking\Modules;

use wapmorgan\TimeParser\TimeParser;

class DateParser {

    public function __construct() {}

    public function run($sentence) {
        $sentence = urldecode($sentence);

        $response = new \stdClass();
        $response->results = [];

        if (strlen($sentence)) {
            $response->results = [
                TimeParser::parse($sentence, 'english', true)
            ];
        }
        
        return json_encode($response);
    }
}
```

### Used libraries and frameworks:
* [doctrine/mongodb-odm](https://github.com/doctrine/mongodb-odm)
* [Respect/Rest](https://github.com/Respect/Rest)
* [ilya/fuzzy](https://github.com/ilya-dev/fuzzy)
* [wapmorgan/time-parser](https://github.com/wapmorgan/TimeParser)
* [nlp-tools/nlp-tools](https://github.com/angeloskath/php-nlp-tools/)
* [agentile/php-stanford-nlp](https://github.com/agentile/PHP-Stanford-NLP)
