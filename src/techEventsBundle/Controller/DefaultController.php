<?php

namespace techEventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('techEventsBundle:Default:index.html.twig');
    }
}
