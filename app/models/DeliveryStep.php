<?php

namespace App\Models;
class DeliveryStep
{
    /** @var string */
    private $startLocation;
    /** @var string */
    private $endLocation;
    /** @var string */
    private $transportMethod;
    /** @var string */
    private $deliveryCompany;

    /**
     * DeliveryStep constructor.
     * @param $note array
     */
    function __construct($note)
    {
        $this->startLocation = $note['startLocation'];
        $this->endLocation = $note['endLocation'];
        $this->transportMethod = $note['transportMethod'];
        $this->deliveryCompany = $note['deliveryCompany'];
    }

    /**
     * @return string
     */
    public function getStartLocation()
    {
        return $this->startLocation;
    }

    /**
     * @return string
     */
    public function getEndLocation()
    {
        return $this->endLocation;
    }

    /**
     * @return string
     */
    public function getTransportMethod()
    {
        return $this->transportMethod;
    }

    /**
     * @return string
     */
    public function getDeliveryCompany()
    {
        return $this->deliveryCompany;
    }
}