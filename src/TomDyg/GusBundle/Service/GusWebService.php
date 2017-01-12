<?php

namespace TomDyg\GusBundle\Service;

require_once '../vendor/autoload.php';

use GusApi\GusApi;
use GusApi\RegonConstantsInterface;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\ReportTypes;

class GusWebService {

    public function __construct() {
        //die('ok');
    }

    public function test() {

        return ['result' => 1];
    }

    public function connect() {

        // $client = new \nusoap_client('https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl.xsd', true);
        // dump($client->call('Zaloguj', ['pKluczUzytkownika'=>'abcde12345abcde12345']));
        // dump($client); 
        // $client = new \SoapClient('http://example.com/url/to/some/valid.wsdl', [], true);
//$response = $client->call('someSOAPMethodee', array('param1'=>'foo', 'param2'=>'bar'));
        // dump($client);
        //$response = $client->call('someSOAPMethod', array('param1'=>'foo', 'param2'=>'bar'));
        //return ['result' => 1];

        $gus = new GusApi(
                'abcde12345abcde12345', // <--- your user key / twój klucz użytkownika
                new \GusApi\Adapter\Soap\SoapAdapter(
                RegonConstantsInterface::BASE_WSDL_URL, RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST //<--- production server / serwer produkcyjny
                //for test serwer use RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
                //w przypadku serwera testowego użyj: RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
                )
        );

        try {
            $gus->login();
        } catch (InvalidUserKeyException $e) {
            echo 'Bad user key';
        }
        
        $_POST['nip'] = '8831713942';
        
        if ($gus->serviceStatus() === RegonConstantsInterface::SERVICE_AVAILABLE) {

    try {

        if (!isset($_SESSION['sid']) || !$gus->isLogged($_SESSION['sid'])) {
            $_SESSION['sid'] = $gus->login();
        }

        //printNipForm();

        if (isset($_POST['nip'])) {

            $nip = $_POST['nip'];
            try {
                $gusReport = $gus->getByNip($_SESSION['sid'], $nip);
                var_dump($gusReport);
                var_dump(
                    $gus->getFullReport(
                        $_SESSION['sid'],
                        $gusReport[0],
                        ReportTypes::REPORT_ACTIVITY_LAW_PUBLIC
                    )
                );
                echo $gusReport[0]->getName();

            } catch (\GusApi\Exception\NotFoundException $e) {
                echo 'No data found <br>';
                echo 'For more information read server message belowe: <br>';
                echo $gus->getResultSearchMessage($_SESSION['sid']);

            }
        }

    } catch (InvalidUserKeyException $e) {
        echo 'Bad user key!';
    }

} else if ($gus->serviceStatus() === RegonConstantsInterface::SERVICE_UNAVAILABLE) {

    echo 'Server is unavailable now. Please try again later <br>';
    echo 'For more information read server message belowe: <br>';
    echo $gus->serviceMessage();

} else {

    echo 'Server technical break. Please try again later <br>';
    echo 'For more information read server message belowe: <br>';
    echo $gus->serviceMessage();

}
        
    }

}
