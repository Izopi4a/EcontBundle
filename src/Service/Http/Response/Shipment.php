<?php

namespace Izopi4a\EcontBundle\Service\Http\Response;

class Shipment
{

    protected float $totalPrice = 0.00;
    protected float $cashOnDeliveryTax = 0.00;
    protected float $deliveryPrice = 0.00;

    protected array $data = [];

    protected array $errors = [];

    public function __construct(array $data)
    {
        if (isset($data['innerErrors'])) {
            $this->errors = $data['innerErrors'];
            return;
        } else {
            $this->totalPrice = $data['label']['totalPrice'];

            $this->setServiceTaxes($data);

            $this->data = $data;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    protected function setServiceTaxes(array $data)
    {
        if (!isset($data['label']['services'])) {
            return;
        }
        foreach ($data['label']['services'] as $key => $value) {

            if ($value['type'] === 'CD') {
                $this->cashOnDeliveryTax = $value['price'];
                continue;
            }

            if ($value['type'] === 'C') {
                $this->deliveryPrice = $value['price'];
                continue;
            }
        }
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getDeliveryPrice(): float
    {
        return $this->deliveryPrice;
    }

    public function getCashOnDeliveryTax(): float
    {
        return $this->cashOnDeliveryTax;
    }

    public function getUnparsetData(): array
    {
        return $this->data;
    }
}