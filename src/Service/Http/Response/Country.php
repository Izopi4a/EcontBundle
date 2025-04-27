<?php

namespace Izopi4a\EcontBundle\Service\Http\Response;

class Country implements \JsonSerializable
{

    protected ?int $id;
    protected string $code2;
    protected string $code3;
    protected string $name;
    protected string $nameEn;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->code2 = $data['code2'];
        $this->code3 = $data['code3'];
        $this->name = $data['name'];
        $this->nameEn = $data['nameEn'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode2(): string
    {
        return $this->code2;
    }

    public function getCode3(): string
    {
        return $this->code3;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "code_2" => $this->getCode2(),
            "code_3" => $this->getCode3(),
            "name" => $this->getName(),
            "name_en" => $this->getNameEn()
        ];
    }
}
