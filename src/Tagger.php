<?php

namespace HotelBooking;

use \StanfordNLP\POSTagger;

class Tagger {

    private $path;
    private $entity;

    public function __construct() {
        $this->path = __DIR__ . "/../bin/stanford-postagger/";

        $this->entity = new POSTagger(
            $this->path . 'models/english-left3words-distsim.tagger',
            $this->path . 'stanford-postagger.jar'
        );
    }

    public function run($sentence) {
        $parts = explode(' ', urldecode($sentence));

        $response = new \stdClass();
        $response->results = [];

        $data = (strlen($sentence)) ? $this->entity->tag($parts) : [];

        foreach ($data as $pair) {
            $obj = new \stdClass();
            $obj->value = $pair[0];
            $obj->type  = $pair[1];
            $response->results[] = $obj; 
        }

        return json_encode($response);
    }
}