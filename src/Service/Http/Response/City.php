<?php

namespace Izopi4a\EcontBundle\Service\Http\Response;

class City implements \JsonSerializable {

    protected int $id;
    protected string $name;
    protected string $nameEn;
    protected Country $country;
    protected string $postCode;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->country = new Country($data['country']);
        $this->name = $data['name'];
        $this->nameEn = $data['nameEn'];
        $this->postCode = $data['postCode'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function jsonSerialize(): array
    {
        return [
          "id" => $this->getId(),
          "name" => $this->getName(),
          "name_en" => $this->getNameEn(),
          "country" => $this->getCountry(),
          "post_code" => $this->getPostCode()
        ];
    }
}
