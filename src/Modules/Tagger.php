<?php

namespace HotelBooking\Modules;

use \StanfordNLP\POSTagger;
use HotelBooking\Documents\City;
use HotelBooking\Documents\Country;
use Fuzzy\Fuzzy;

class Tagger {

    private $path;
    private $entity;
    private $fuzzy;
    private $cache;

    const FUZZY_MATCHING = 2;

    public function __construct($dm) {
        $this->path = __DIR__ . "/../../bin/stanford-postagger/";
        $this->dm = $dm;

        $this->fuzzy  = new Fuzzy;
        $this->entity = new POSTagger(
            $this->path . 'models/english-left3words-distsim.tagger',
            $this->path . 'stanford-postagger.jar'
        );
    }

    /**
     * Parse token entity, search for the city
     */
    public function run($sentence) {
        $parts = explode(' ', urldecode($sentence));

        $response = new \stdClass();
        $response->results = [];

        $data = (strlen($sentence)) ? $this->entity->tag($parts) : [];

        $data = $this->parseDuration($data);
        $data = $this->parseOffset($data);

        foreach ($data as $pair) {
            $obj = new \stdClass();

            if ( in_array($pair[1], ["NN", "NNP"]) ) {
                $pair = $this->parseCity($pair);
            }

            $obj->value = $pair[0];
            $obj->type  = $pair[1];
            $response->results[] = $obj; 
        }


        return json_encode($response);
    }


    /**
     * Try to convert alphabetic number to numeric
     */
    private function parseNumber($number) {
        $representation = array(
            "one"       => 1,
            "two"       => 2,
            "three"     => 3,
            "four"      => 4,
            "five"      => 5,
            "six"       => 6,
            "seven"     => 7,
            "eight"     => 8,
            "nine"      => 9,
            "ten"       => 10,
            "eleven"    => 11,
            "twelwe"    => 12
        );
        
        if (array_key_exists($number, $representation)) {
            $number = $representation[$number];
        }

        return $number;
    }


    /**
     * Parse token entity, search for the city
     * @param $array Token
     * @return $array Token
     */
    private function parseCity($array) {
        list($value, $type) = $array;

        if (!count($this->cache['cities'])) {
            $rows = [];

            foreach ($this->dm->getRepository("\HotelBooking\Documents\Country")->findAll() as $country) {
                $rows[] = $country->getCapital();
            }

            $this->cache['cities'] = $rows;
        }

        $results = $this->fuzzy->search(
            $this->cache['cities'], $value, self::FUZZY_MATCHING
        );

        if (count($results)) {
            $array[0] = $results[0];
            $array[1] = "LOC";
        }

        return $array;
    }


    /**
     * Parse token array, search for the duration
     * @param $array Token
     * @return $array Token
     */
    private function parseDuration($array) {
        $start  = ["for"];
        $finish = ["day", "days", "night", "nights", "week", "weeks", "month", "months"];

        for ($i = 0; $i < count($array); $i++) {
            @$a = strtolower($array[$i + 0][0]);
            @$b = strtolower($array[$i + 1][0]);
            @$c = strtolower($array[$i + 2][0]);
            
            if ($a && $c &&
                in_array($a, $start) && 
                in_array($c, $finish) && 
                $array[$i + 1][1] === "CD"
            ) {
                $b = $this->parseNumber($b);

                if (strpos($c, "week") !== false) $b *= 7;
                if (strpos($c, "night") !== false) $b += 1;
                if (strpos($c, "month") !== false) $b *= 30;

                $array[$i] = [
                    "$b", 'DUR', $c
                ];
                unset($array[$i + 1]);
                unset($array[$i + 2]);
            }
        }
        
        return $array;
    }

    /**
     * Parse token array, search for the offset
     * @param $array Token
     * @return $array Token
     */
    private function parseOffset($array) {
        $start  = ["in"];
        $finish = ["day", "days", "week", "weeks", "month", "months"];

        for ($i = 0; $i < count($array); $i++) {
            @$a = strtolower($array[$i + 0][0]);
            @$b = strtolower($array[$i + 1][0]);
            @$c = strtolower($array[$i + 2][0]);
            
            if ($a && $c &&
                in_array($a, $start) && 
                in_array($c, $finish) && 
                $array[$i + 1][1] === "CD"
            ) {
                $b = $this->parseNumber($b);

                if (strpos($c, "week") !== false) $b *= 7;
                if (strpos($c, "month") !== false) $b *= 30;

                $array[$i] = [
                    "$b", 'OFS', $c
                ];
                unset($array[$i + 1]);
                unset($array[$i + 2]);
            }
        }
        
        return $array;
    }
}