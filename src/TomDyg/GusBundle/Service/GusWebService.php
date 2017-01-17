<?php

namespace TomDyg\GusBundle\Service;

require_once '../vendor/autoload.php';

use GusApi\GusApi;
use GusApi\RegonConstantsInterface;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\ReportTypes;

class GusWebService {

    protected $key;
    protected $gus;

    public function __construct($key) {
       $this->key = $key;
       
       $this->gus = new GusApi(
                $this->key, // <--- your user key / twój klucz użytkownika
                new \GusApi\Adapter\Soap\SoapAdapter(
                RegonConstantsInterface::BASE_WSDL_URL, RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST //<--- production server / serwer produkcyjny
                //for test serwer use RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
                //w przypadku serwera testowego użyj: RegonConstantsInterface::BASE_WSDL_ADDRESS_TEST
                )
        );

       
    }

    public function login() {

        
        $this->checkServiceStatus();

        return $sid = $this->gus->login();
    }

    public function checkServiceStatus() {

        if ($this->gus->serviceStatus() === RegonConstantsInterface::SERVICE_AVAILABLE) {
            
        } else if ($this->gus->serviceStatus() === RegonConstantsInterface::SERVICE_UNAVAILABLE) {

            throw new \Exception('Server is unavailable now. Please try again later For more information read server message belowe: ' . $this->gus->serviceMessage());
        } else {

            throw new \Exception('Server technical break. Please try again later. For more information read server message belowe: ' . $this->gus->serviceMessage());
        }
    }

    public function getFullReport($nip) {

      
        $sid = $this->login();

        try {
            $gusReport = $this->gus->getByNip($sid, $nip);
           
           if (!empty($gusReport)) { 
            
            return $this->gus->getFullReport(
                           $sid, $gusReport[0], ReportTypes::REPORT_PUBLIC_LAW
            );
            
           } else {
              // throw new \Exception('emptyyyyyyy');
               return false;
           }

        } catch (\GusApi\Exception\NotFoundException $e) {
            return false;
        } 
        catch (\GusApi\Adapter\Soap\Exception\NoDataException $e) {
           
            return $this->gus->getFullReport(
                           $sid, $gusReport[0], ReportTypes::REPORT_LOCALS_PHYSIC_PUBLIC
            );
        
        }
    }
    
    
    
    

}
