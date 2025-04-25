<?php

namespace Izopi4a\EcontBundle\Service\Http\Payload;

class Contact
{
    protected string $role;

    protected string $name;
    protected array $phone;

    CONST RECIEVER = 'RECIEVER';
    CONST SENDER = 'SENDER';

    public function __construct(string $name = "", array $phone = [])
    {
        if ($name !== "") {
            $this->setName($name);
        }

        if (count($phone) > 0) {
            foreach ($phone as $item) {
                $this->addPhone($item);
            }
        }
    }

    public function getData() : array
    {

        if ($this->role === self::SENDER) {
            $prefix = "senderClient";
        } else if ($this->role === self::RECIEVER) {
            $prefix = "receiverClient";
        } else {
            throw new \Exception('Invalid contact role');
        }

        return [
            $prefix => [
                "name" => $this->name,
                "phones" => $this->phone,
            ]
        ];

    }

    public function addPhone($phone)
    {
        if (is_string($phone)) {
            $this->phone[] = $phone;
        }
        if (is_array($phone)) {
            $this->phone = array_merge($this->phone, $phone);
        }
    }

    public function setName(string $name):self
    {
        $this->name = $name;
        return $this;
    }

    public function setTypeReciever() :self
    {
        $this->role = self::RECIEVER;
        return $this;
    }

    public function setTypeSender() :self
    {
        $this->role = self::SENDER;
        return $this;
    }
}