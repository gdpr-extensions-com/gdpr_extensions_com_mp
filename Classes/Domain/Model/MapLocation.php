<?php

namespace GdprExtensionsCom\GdprExtensionsComMp\Domain\Model;

class MapLocation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = null;

    /**
     * address
     *
     * @var string
     */
    protected $address = null;

    /**
     * lat
     *
     * @var int
     */
    protected $lat = null;

    /**
     * lon
     *
     * @var int
     */
    protected $lon = null;

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Returns the address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * Returns the lat
     *
     * @return int
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Sets the lat
     *
     * @param int $lat
     * @return void
     */
    public function setLat(int $lat)
    {
        $this->lat = $lat;
    }

    /**
     * Returns the lon
     *
     * @return int
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Sets the lon
     *
     * @param int $lon
     * @return void
     */
    public function setLon(int $lon)
    {
        $this->lon = $lon;
    }
}

