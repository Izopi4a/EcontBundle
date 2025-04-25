<?php

namespace Izopi4a\EcontBundle\Service\Http\Payload;

class Package
{

    protected int $weight;
    protected string $description;
    protected string $type;
    protected int $count;

    CONST TYPE_DOCUMENT = 'document';
    CONST TYPE_PACKAGE = 'pack';

    protected float $cashOnDeliveryAmount = 0.00;

    public function __construct(int $weight = 0, string $description = "", string $type = self::TYPE_PACKAGE, int $count = 0)
    {
        if ($weight > 0) {
            $this->setWeight($weight);
        }

        if ($description !== "") {
            $this->setDescription($description);
        }

        if ($type !== "") {
            $this->setType($type);
        }

        if ($count > 0) {
            $this->setCount($count);
        }
    }

    public function getData() : array
    {

        $dt = new \DateTime();
        $dt->modify("next monday");

        $return = [
            "packCount" => $this->getCount(),
            "weight" => $this->getWeight(),
            "shipmentType" => $this->getType(),
            "shipmentDescription" => $this->getDescription(),
            "sendDate" => $dt->format("D.m.Y"),
            "holidayDeliveryDay" => "work_day"
//            "services" => [
//                "cdAmount" => 200
//            ]
        ];

        if ($this->cashOnDeliveryAmount > 0) {
            $return["services"] = [
                "cdAmount" => $this->cashOnDeliveryAmount,
                "cdType" => "give"
            ];
        }

        return $return;
    }

    public function addCashOnDelivery(float $amount): self
    {
        $this->cashOnDeliveryAmount = $amount;
        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCount(): int
    {
        return $this->count;
    }
    
    public function setWeight(int $weight) :self
    {
        $this->weight = $weight;
        return $this;
    }
    
    public function setDescription(string $description) :self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * https://demo.econt.com/ee/services/Shipments/#ShipmentType
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type) :self
    {

        if ($type !== self::TYPE_DOCUMENT && $type !== self::TYPE_PACKAGE) {
            throw new \InvalidArgumentException('Invalid type');
        }

        $this->type = $type;
        return $this;
    }
    
    public function setCount(int $count) :self
    {
        $this->count = $count;
        return $this;
    }
}