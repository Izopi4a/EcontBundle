<?php

namespace Izopi4a\EcontBundle\Service\Http\Response;

class Adress {

    protected ?int $id;
    protected City $city;
    protected string $fullAddress;
    protected string|null $quarter;
    protected string|null $street;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->city = new City($data['city']);
        $this->fullAddress = $data['fullAddress'];
        $this->quarter = $data['quarter'];
        $this->street = $data['street'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function getFullAddress(): string
    {
        return $this->fullAddress;
    }

    public function getQuarter(): ?string
    {
        return $this->quarter;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }
}
