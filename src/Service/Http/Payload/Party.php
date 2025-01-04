<?php

namespace Izopi4a\EcontBundle\Service\Http\Payload;

class Party
{
    protected string $role;

    CONST RECIEVER = 'RECIEVER';
    CONST SENDER = 'SENDER';

    protected string $office = "";

    protected string $street = "";
    protected string $street_number = "";
    protected string $other = "";
    protected string $econt_city_id = "";

    public function __construct(array $data = [])
    {

        if (isset($data['office'])) {
            $this->office = $data['office'];
        } else if (isset($data['econt_city_id'])) {
            $this->setAddress($data['econt_city_id'], $data['street'], $data['street_number'], $data['other']);
        } else {
            if (count($data) > 0) {
                throw new \Exception('Invalid party data');
            }
        }
    }

    public function getData() : array
    {

        if ($this->role === self::SENDER) {
            $prefix_office = "senderOfficeCode";
            $prefix_address = "senderAddress";
        } else if ($this->role === self::RECIEVER) {
            $prefix_office = "receiverOfficeCode";
            $prefix_address = "receiverAddress";
        } else {
            throw new \Exception('Invalid party role');
        }

        if ($this->office !== "") {
            return [
                $prefix_office => $this->office,
            ];
        }

        $address = [$prefix_address => [
            "city" => [
                "id" => $this->econt_city_id
            ]
        ]];

        if ($this->street !== "") {
            $address[$prefix_address]["street"] = $this->street;
        }

        if ($this->street_number !== "") {
            $address[$prefix_address]["num"] = $this->street_number;
        }

        if ($this->other !== "") {
            $address[$prefix_address]["other"] = $this->other;
        }

        if (!isset($address[$prefix_address]["other"]) === false && empty($address[$prefix_address]["other"])) {

            if (
                !isset($address[$prefix_address]["street"]) === false &&
                empty($address[$prefix_address]["street"])
            ) {
                throw new \Exception('incorect data provided for '.$this->role.' party');
            }

        }

        return $address;
    }

    public function setAddress(string $econt_city_id, string $street, string $street_number,string $other)
    {
        $this->econt_city_id = $econt_city_id;
        $this->street = $street;
        $this->street_number = $street_number;
        $this->other = $other;
    }

    public function setOffice(string $econt_hub) : self
    {
        $this->office = $econt_hub;
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