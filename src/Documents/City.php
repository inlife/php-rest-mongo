<?php

namespace HotelBooking\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class City
{
    /** @ODM\Id */
    private $id;

    /** @ODM\String */
    private $name;

    /** @ODM\ReferenceOne(targetDocument="HotelBooking\Documents\Country") */
    private $country;


    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getCountry() {
        return $this->name;
    }

    public function setCountry(\HotelBooking\Documents\Country $country) {
        $this->country = $country;
    }
}