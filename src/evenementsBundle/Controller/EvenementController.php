<?php

namespace evenementsBundle\Controller;


use evenementsBundle\Entity\Evenement;
use evenementsBundle\Entity\EventSignales;
use evenementsBundle\Form\EvenementEditType;
use evenementsBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EvenementController extends Controller
{
    public function indexAction(Request $request)
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository("evenementsBundle:Evenement");

        if ($request->query->get("sortBy")==2){
            $query = $repos->allEventsByDateModif();
        }

        elseif ($request->query->get("sortBy")==3){
            $query = $repos->allEventsByDate();
        }
        else {
            $query = $repos->allEventsByViews();
        }

        $paginator  = $this->get('knp_paginator');
        $evenements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('evenementsBundle:Evenement:evenements.html.twig', array("evenements"=>$evenements));
    }

    public function showAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event){
            return $this->render('evenementsBundle:Evenement:evenement.html.twig', array("event"=>$event));
        }
        throw new NotFoundHttpException();
    }

    public function updateAction($id, Request $req){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event)
            if($event->getUser()==$this->getUser()){
                $form= $this->createForm(EvenementEditType::class, $event);
                if ($req->isMethod('post')){
                    $event->setUpdatedAt(new \DateTime());
                    if ($req->query->get('lat') != null){
                        $event->setLatLng($req->query->get('lat')."/".$req->query->get('lng')."");
                    }
                    $form->handleRequest($req);
                    $orm->flush($event);
                    return $this->redirectToRoute('evenements_show_event', array('id'=>$id));
                }
                else {
                    return $this->render('evenementsBundle:Evenement:update.html.twig', array("form" => $form->createView()));
                }
            }

        throw new NotFoundHttpException();
    }

    public function addAction(Request $req){
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        if ($req->isMethod('post')){

            $orm=$this->getDoctrine()->getManager();

            if($form->handleRequest($req)->isValid()) {
                $evenement->setUser($this->getUser());
                if ($req->query->get('lat') != null){
                    $evenement->setLatLng($req->query->get('lat')."/".$req->query->get('lng')."");
                }
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
            array("totalEvent"=>$totalEvent,
                "myEventNbr"=>$myEventNbr,
                "subscribedEvents"=> $subscribedEventNbr,
                "categories"=>$categories));
    }

    public function lastEventsAction(){
        $orm=$this->getDoctrine()->getManager();
        $repos=$orm->getRepository('evenementsBundle:Evenement');
        //$events=$repos->findBy(array(),array('dateModification'=>'desc'),3);
        $events=$repos->lastEvents(3);
        return $this->render("evenementsBundle:Evenement:lastEvents.html.twig", array("events"=>$events));
    }

    public function searchAction(Request $req){
        if ($req->isXmlHttpRequest()){
            $orm= $this->getDoctrine()->getManager();
            $repos = $orm->getRepository("evenementsBundle:Evenement");
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            $events = $repos->findBy(array("disponibilite"=>1));
            $jsonContent = $serializer->serialize($events, 'json', array("attributes"=>["id","titre","urlImage","date","adresse"]));
            return new response($jsonContent);
        }
        throw new NotFoundHttpException();
    }

    public function bookmarkAction (Request $req){
        if ($req->isXmlHttpRequest()){
            $orm=$this->getDoctrine()->getManager();
            $repos= $orm->getRepository("evenementsBundle:Evenement");
            $event= $repos->find($req->get("id"));
            if ($event != null){
                $arrayEs = $event->getEvenementSauvegardes()->toArray();
                if (!in_array($this->getUser(),$arrayEs)) {
                    $event->addEvenementSauvegarde($this->getUser());
                    $orm->flush();
                    return new Response("saved");
                }
                else{
                    $event->removeEvenementSauvegarde($this->getUser());
                    $orm->flush();
                    return new Response("deleted");
                }
            }
            return new Response("no");
        }
        throw new NotFoundHttpException();
    }

    public function reportAction(Request $req, $id){
        if ($req->isXmlHttpRequest()){
            $orm= $this->getDoctrine()->getManager();
            $repos= $orm->getRepository('evenementsBundle:Evenement');
            $event = $repos->find($id);
            if ($event != null){
                $eventSig = new EventSignales();
                $eventSig->setUser($this->getUser());
                $eventSig->setEvenement($event);
                $eventSig->setSujet($req->get('sujet'));
                $eventSig->setDescription($req->get('description'));
                $orm->persist($eventSig);
                $orm->flush();
                return new Response("yes");
            }
            else
                return new Response("no");
        }

        throw new NotFoundHttpException();
    }
}
