<?php

namespace TomDyg\GusBundle\Tests\Service;


class GusWebServiceTest extends \PHPUnit_Framework_TestCase {

    protected $obj;

    public function setUp() {

        $this->obj = new \TomDyg\GusBundle\Service\GusWebService('abcde12345abcde12345');
    }

    public function testGetFullReportSuccess() {

        $this->assertTrue($this->obj->getFullReport('8831713942'));
        
    }
    
    public function testGetFullReportFalse() {

        $this->assertFalse($this->obj->getFullReport('8831713942a'));
        
    }
    
    
    public function testLogin() {

       // $this->assertNotNull($this->obj->login());
        $this->assertNotNull($this->obj->login());
        
    }
    
    
    
    

}
