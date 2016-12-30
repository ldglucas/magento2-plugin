<?php
namespace Decidir;

class CustomSoap extends \SoapClient
{
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        var_dump($request);
        var_dump($location);
        var_dump($action);
        var_dump($version);
        var_dump($one_way);

        die;
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);

        return($response);
    }
}