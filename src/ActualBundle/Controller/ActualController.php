<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/04/2019
 * Time: 10:05
 */

namespace ActualBundle\Controller;


use ActualBundle\Entity\Actualite;

class ActualController extends Controller
{
    public function ActualCardAction()
    {
        return $this->render('@Actual/Actalite/ActualCard.html.twig', array(
            'Actual'=>$Actual
        ));
    }
}