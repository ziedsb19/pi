<?php

namespace evenementsBundle\Controller;


use evenementsBundle\Entity\Evenement;
use evenementsBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class EvenementController extends Controller
{
    public function indexAction()
    {
        return $this->render('evenementsBundle::evenement.html.twig');
    }

    public function addAction(Request $req){
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        if ($req->isMethod('post')){

            $orm=$this->getDoctrine()->getManager();

            if($form->handleRequest($req)->isValid()) {
                $evenement->setUser($this->getUser());
                $orm->persist($evenement);
                $orm->flush();
                return $this->redirectToRoute("evenements_homepage");
            }
            else{
                $req->getSession()->getFlashBag()->add('form_error', "erreur lors de la validation du formulaire (captcha erreur) !");
            }
        }
        return $this->render('evenementsBundle:Evenement:add.html.twig', array("form"=>$form->createView()));
    }

    public function agregateAction(){
        $orm= $this->getDoctrine()->getManager();
        $repos= $orm->getRepository("evenementsBundle:Evenement");
        $catRepos= $orm->getRepository('evenementsBundle:Categorie');
        $categories = $catRepos->findAll();
        $totalEvent = $repos->evenementsNbr();
        $myEventNbr = $repos->myEvents($this->getUser()->getId());
        $subscribedEventNbr = $repos->subscribedEvents($this->getUser()->getId());
        return $this->render("evenementsBundle:Evenement:agregate.html.twig",
            array("totalEvent"=>$totalEvent, "myEventNbr"=>$myEventNbr, "subscribedEvents"=> $subscribedEventNbr, "categories"=>$categories));
    }

}
