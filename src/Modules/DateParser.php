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