<?php

namespace Izopi4a\EcontBundle\Service;

use Izopi4a\EcontBundle\Service\Http\Payload\ShippingLabel;

class EcontService
{
    private string $user;
    private string $password;
    private string $locale;
    private bool $dev;

    private string $url = '';

    public function __construct(string $user, string $password, string $locale, bool $dev)
    {
        $this->user = $user;
        $this->password = $password;
        $this->locale = $locale;
        $this->dev = $dev;
    }

    private function request(string $method, array $params ,$timeout = 30) {

        if ($this->dev) {
            $endpoint = 'https://demo.econt.com/ee/services';
            $auth = [
                'login' => 'iasp-dev',
                'password' => '1Asp-dev',
            ];
        } else {
            $endpoint = 'https://ee.econt.com/services';
            $auth = [
                'login' => $this->user,
                'password' => $this->password,
            ];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint . '/' . rtrim($method,'/'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($ch, CURLOPT_USERPWD, $auth['login'].':'.$auth['password']);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
        curl_setopt($ch, CURLOPT_TIMEOUT, !empty($timeout) && intval($timeout) ? $timeout : 4);
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

//        var_dump($response);

        try {
            $jsonResponse = json_decode($response,true);
        } catch (\JsonException $e) {
            dd($e);
        }

        return $jsonResponse;
    }

    /**
     * Retrieves all cities for a given country.
     * method is very slow and memory consuming. Probably the best to use in command.
     * youy can update the momory for the script in the file with ini_set('memory_limit', '1512M');
     *
     * @param string $countryCode The ISO country code. Defaults to "BGR".
     *
     * @return array An array of City objects corresponding to the cities in the specified country.
     */
    public function getAllCitiesInCountry(string $countryCode = "BGR") : array
    {

        $cities = [];
        $params = [
            "countryCode" => $countryCode
        ];

        $res = $this->request("Nomenclatures/NomenclaturesService.getCities.json", $params);

        foreach ($res['cities'] as $cityData) {
            $cities[] = new Http\Response\City($cityData);
        }

        return $cities;
    }

    /**
     * Retrieves a list of offices in a specific city.
     * An exception is thrown if an invalid city ID is provided.
     *
     * @param int $econtCityId The ID of the city whose offices are to be retrieved.
     * @param string $countryCode The country code, defaults to "BGR".
     *
     * @return Http\Response\Office[] List of office objects.
     * @throws \Exception If city ID is invalid (less than 0).
     *
     */

    public function getOfficesInCity(int $econtCityId, string $countryCode = "BGR") : array
    {
        $result = [];

        if ($econtCityId < 0) {
            throw new \Exception("City ID must be provided and valid");
        }

        $params = [
            "countryCode" => $countryCode,
            "cityID" => $econtCityId
        ];

        $res = $this->request("Nomenclatures/NomenclaturesService.getOffices.json", $params);

        foreach ($res['offices'] as $officeData) {
            $result[] = new Http\Response\Office($officeData);
        }

        return $result;
    }

    /**
     * Handles the calculation and creation of a delivery shipment label based on the provided sender and receiver details, package details, and contacts.
     *
     * @param Http\Payload\Party $senderParty Details of the sender's party.
     * @param Http\Payload\Party $recieverParty Details of the receiver's party.
     * @param Http\Payload\Package $package Details of the package to be shipped.
     * @param Http\Payload\Contact $senderContact Contact information of the sender.
     * @param Http\Payload\Contact $recieverContact Contact information of the receiver.
     *
     * @return Http\Response\Shipment Returns a shipment object containing the generated shipment label.
     *
     * @throws Exception If there is an error in the request or response during label creation.
     */
    public function calculateDelivery(
        Http\Payload\Party $senderParty,
        Http\Payload\Party $recieverParty,
        Http\Payload\Package $package,
        Http\Payload\Contact $senderContact,
        Http\Payload\Contact $recieverContact,
    ) : Http\Response\Shipment
    {
        $params = [
            "label" => [],
            "mode" => "validate",
        ];

        $params["label"] = array_merge($params["label"], $senderParty->getData(),  $recieverParty->getData(),$senderContact->getData(), $recieverContact->getData(), $package->getData());

        $res = $this->request("Shipments/LabelService.createLabel.json", $params);

        $cls = new Http\Response\Shipment($res['label']);

        return $cls;
    }
}