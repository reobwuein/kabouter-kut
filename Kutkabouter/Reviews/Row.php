<?php namespace Kutkabouter\Reviews;

/**
 * Class Row
 *
 * Simple data class to hold a row of review data
 */
class Row
{
    /** @var int */
    public $id;
    /** @var float */
    public $longtitude;
    /** @var float */
    public $latitude;
    /** @var string */
    public $name;
    /** @var array */
    public $reviews = array();

    /**
     * @param int $id
     * @param string $longtitude
     * @param string $latitude
     * @param string $name
     * @param array $reviews
     */
    public function __construct($id, $longtitude, $latitude, $name, array $reviews)
    {
        $this->id = $id;
        $this->longtitude = $longtitude;
        $this->latitude = $latitude;
        $this->name = $name;
        $this->reviews = $reviews;
    }
} 