<?php

namespace Izopi4a\EcontBundle\Service\Http\Response;

class Shipment
{

    protected float $totalPrice = 0.00;

    protected array $data = [];

    public function __construct(array $data)
    {

        $this->totalPrice = $data['totalPrice'];
        $this->data = $data;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }
}