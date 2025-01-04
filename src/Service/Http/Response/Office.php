<?php

namespace Izopi4a\EcontBundle\Service\Http\Response;

class Office {

    protected ?int $id;
    protected string $code;
    protected string $name;
    protected string $nameEn;
    protected \Izopi4a\EcontBundle\Service\Http\Response\Adress $address;
    protected string $hubName;
    protected string $hubCode;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->code = $data['code'];
        $this->name = $data['name'];
        $this->nameEn = $data['nameEn'];

        $this->address = new Adress($data['address']);
        $this->hubName = $data['hubName'];
        $this->hubCode = $data['hubCode'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    public function getAddress(): \Izopi4a\EcontBundle\Service\Http\Response\Adress
    {
        return $this->address;
    }

    public function getHubName(): string
    {
        return $this->hubName;
    }

    public function getHubCode(): string
    {
        return $this->hubCode;
    }

}