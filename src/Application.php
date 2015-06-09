<?php

namespace HotelBooking;

use Respect\Rest\Router;

class Application {
    private $router;
    private $tagger;
    private $dateParser;
    private $dm;

    public function __construct($dm) {
        $this->dm = $dm;
        $this->router = new Router;
        $this->tagger = new Modules\Tagger($dm);
        $this->dateParser = new Modules\DateParser;
    }

    public function start() {

        $this->router->get('/', function() {
            return 'HotelBooking Server v0.1';
        });

        $this->router->get('/tag/*', function($sentence = "") {
            header('Content-Type: application/json');
            return $this->tagger->run($sentence);
        });

        $this->router->get('/date/*', function($datestring = "") {
            header('Content-Type: application/json');
            return $this->dateParser->run($datestring);
        });
    }
}