<?php

namespace TomDyg\GusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $test = $this->get('tom_dyg_gus.gus_web_service');
        $test->connect();
        die('000');
        
        return $this->render('TomDygGusBundle:Default:index.html.twig');
    }
}
