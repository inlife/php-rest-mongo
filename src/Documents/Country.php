<?php

namespace HotelBooking\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Country
{
    /** @ODM\Id */
    private $id;

    /** @ODM\String */
    private $name;

    /** @ODM\ReferenceOne(targetDocument="HotelBooking\Documents\Country") */
    private $cities;

    /** @ODM\String */
    private $capital;

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

    public function getCapital() {
        return $this->capital;
    }

    public function getCities() {
        return $this->cities;
    }
}