<?php

namespace evenementsBundle\Controller;


use evenementsBundle\Entity\Evenement;
use evenementsBundle\Entity\EventSignales;
use evenementsBundle\Entity\ImageEvenement;
use evenementsBundle\Entity\Inscription;
use evenementsBundle\Form\EvenementEditType;
use evenementsBundle\Form\EvenementType;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

class EvenementController extends Controller
{
    public function indexAction(Request $request)
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository("evenementsBundle:Evenement");
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));

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
        return $this->render('evenementsBundle:Evenement:evenements.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));
    }
    
    public function filterAction(Request $request){
        if ($request->isMethod('post')) {
            if (empty($request->get('organisateur')) && empty($request->get('region')) && empty($request->get('date')) && empty($request->get('prix'))) {
                return $this->redirectToRoute('evenements_homepage');
            }
            else {
                $post_array = array("organisateur"=>"", "region"=>"", "prix"=> null, "date"=>"");
                $orm = $this->getDoctrine()->getManager();
                $repos = $orm->getRepository('evenementsBundle:Evenement');
                $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
                $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
                $query = $orm->createQueryBuilder();
                $query->select('E')
                    ->from('evenementsBundle:Evenement', 'E')
                    ->join('E.user','U')
                    ->where('E.date> CURRENT_TIMESTAMP()')
                    ->andWhere('E.disponibilite = 1');

                if (!empty($request->get('prix'))){
                    if ($request->get('prix')==2) {
                        $query->andWhere('E.prix is null');
                        $post_array["prix"]=2;
                    }
                    else {
                        $query->andWhere('E.prix is not null');
                        $post_array["prix"]=1;
                    }
                }
                if (!empty($request->get('region'))){
                    $query->andWhere("E.adresse = :region");
                    $query->setParameter('region',$request->get('region'));
                    $post_array["region"]=$request->get('region');
                }
                if (!empty($request->get('organisateur'))){
                    $query->andWhere("U.username = '".$request->get('organisateur')."'");
                    $post_array["organisateur"]=$request->get('organisateur');
                }
                if (!empty($request->get('date'))){
                    $query->andWhere("date(E.date) = '".$request->get('date')."'");
                    $post_array["date"]=$request->get('date');
                }
                $query->add('orderBy', 'E.vues DESC ')->add('orderBy', 'E.date ASC');
                $evenements =$query->getQuery()->getResult();
                $count= sizeof($evenements);
            //    $evenements=array_slice($evenements,0,6);
                return $this->render('evenementsBundle:Evenement:evenements.html.twig',
                    array("evenements" => $evenements, "post_array"=>$post_array, "count"=>$count, "eventSig"=>$eventSig));

            }
        }
        throw new NotFoundHttpException();
    }

    public function showAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $esRepos= $orm->getRepository('evenementsBundle:EventSignales');
        $inscriRepos= $orm->getRepository('evenementsBundle:Inscription');
        $imageRepos = $orm->getRepository('evenementsBundle:ImageEvenement');
        $event = $repos->find($id);
        if ($event->getDisponibilite()==1){
            $eventSig= null;
            $inscription=$inscriRepos->findBy(array('evenement'=>$event));
            $userInscri = null;
            $images = $imageRepos->findBy(array("evenement"=>$event));
            if ($this->getUser()!=$event->getUser()){
                $event->setVues($event->getVues()+1);
                $eventSig= $esRepos->findOneBy(array('evenement'=>$event, 'user'=>$this->getUser()));
                $userInscri =$inscriRepos->findOneBy(array('evenement'=>$event, "user"=>$this->getUser()));
                $orm->persist($event);
                $orm->flush();
            }
            return $this->render('evenementsBundle:Evenement:evenement.html.twig',
                array("event"=>$event, "eventSig"=>$eventSig, "inscription"=>$inscription, "userIns"=>$userInscri,"images"=>$images));
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
                return $this->redirectToRoute("evenements_show_event", array("id"=>$evenement->getId()));
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
        $savedEventsNbr = sizeof( array_filter($repos->EventsEnregistreNbr(), function($e){
            $es = $e->getEvenementSauvegardes()->toArray();
            return in_array($this->getUser(),$es );
        }));
        return $this->render("evenementsBundle:Evenement:agregate.html.twig",
            array("totalEvent"=>$totalEvent,
                "myEventNbr"=>$myEventNbr,
                "subscribedEvents"=> $subscribedEventNbr,
                "savedEvents"=>$savedEventsNbr,
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
    
    public function addImageAction(Request $req, $id){
        $dossier= $this->getParameter('kernel.project_dir')."/web/images/evenements_dossier/";
        if ($req->isXmlHttpRequest()) {
            $orm= $this->getDoctrine()->getManager();
            $imageRepos=$orm->getRepository('evenementsBundle:ImageEvenement');
            $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
            for ($i=0; $i<sizeof($_FILES['file']['name']);$i++){
                $ext = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
                $file_name= uniqid().".".$ext;
                $file_tmp= $_FILES['file']['tmp_name'][$i];
                if (move_uploaded_file($file_tmp, $dossier.$file_name)){
                    $image= new ImageEvenement();
                    $image->setEvenement($eventRepos->find($id));
                    $image->setUrl($file_name);
                    $orm->persist($image);
                }
                else{
                    throw new Exception();
                }
            }
            $orm->flush();
            return new Response("yes");
        }
        throw new NotFoundHttpException();
    }

    public function deleteImageAction(Request $req, $id){
        $dossier= $this->getParameter('kernel.project_dir')."/web/images/evenements_dossier/";
        if ($req->isXmlHttpRequest()){
            $orm=$this->getDoctrine()->getManager();
            $repos= $orm->getRepository('evenementsBundle:ImageEvenement');
            $image = $repos->find($id);
            if ($image) {
                unlink($dossier.$image->getUrl());
                $orm->remove($image);
                $orm->flush();
            }
            return new Response("yes");
        }
        throw new NotFoundHttpException();
    }

    public function inscriAction(Request $req, $id){
        if ($req->isXmlHttpRequest()){
            $orm= $this->getDoctrine()->getManager();
            $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
            $event = $eventRepos->find($id);
            if ($event) {
                $repos = $orm->getRepository('evenementsBundle:Inscription');
                $inscri = $repos->findOneBy(array("evenement" => $event, "user" => $this->getUser()));
                if ($inscri){
                    $orm->remove($inscri);
                }
                else{
                    $inscri= new Inscription();
                    $inscri->setEvenement($event);
                    $inscri->setUser($this->getUser());
                    $orm->persist($inscri);
                }
                $orm->flush();
                return new Response("yes");
            }
        }
        throw new NotFoundHttpException();
    }

    public function deleteAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos= $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event and $event->getUser() == $this->getUser()){
            $imagesRepos = $orm->getRepository('evenementsBundle:ImageEvenement');
            $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
            $insciRepos = $orm->getRepository('evenementsBundle:Inscription');
            $images=$imagesRepos->findBy(array("evenement"=>$event));
            foreach ($images as $im){
                $orm->remove($im);
            }
            $esig = $esRepos->findBy(array("evenement"=>$event));
            foreach ($esig as $es){
                $orm->remove($es);
            }
            $inscri = $insciRepos->findBy(array("evenement"=>$event));
            foreach ($inscri as $ins){
                $orm->remove($ins);
            }
            $orm->remove($event);
            $orm->flush();
            return $this->redirectToRoute("evenements_homepage");
        }
        throw  new NotFoundHttpException();
    }

    public function eventsInscriAction(){
        return $this->render('evenementsBundle:Evenement:eventsInscri.html.twig');
    }

    public function eventsOrganiseAction(){
        return $this->render('evenementsBundle:Evenement:eventsOrganise.html.twig');
    }

    public function eventsEnregistreAction(){
        $orm= $this->getDoctrine()->getManager();
        $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
        $evenements = $eventRepos->custom();
        $evenements = array_filter($evenements, function($e) {
            $es = $e->getEvenementSauvegardes()->toArray();
            return in_array($this->getUser(), $es);
        });
        return $this->render('evenementsBundle:Evenement:listEvents2.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));
    }

    public function eventsInscriConAction(Request $request){
        $orm= $this->getDoctrine()->getManager();
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $inscriRepos = $orm->getRepository('evenementsBundle:Inscription');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
        $inscriptions = $inscriRepos->inscriptionRecent($this->getUser()->getId());
        $evenements = array();
        foreach ($inscriptions as $i){
            array_push($evenements, $i->getEvenement());
        }
        return $this->render('evenementsBundle:Evenement:listEvenements.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));

    }

    public function eventsInscriConPasseAction(Request $request){
        $orm= $this->getDoctrine()->getManager();
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $inscriRepos = $orm->getRepository('evenementsBundle:Inscription');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
        $inscriptions = $inscriRepos->inscriptionPasses($this->getUser()->getId());
        $evenements = array();
        foreach ($inscriptions as $i){
            array_push($evenements, $i->getEvenement());
        }
        return $this->render('evenementsBundle:Evenement:listEvenements.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));

    }

    public function eventsOrganiseConAction(Request $request){
        $orm= $this->getDoctrine()->getManager();
        $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
        $evenements = $eventRepos->EventsOrganiseRecents($this->getUser()->getId());
        return $this->render('evenementsBundle:Evenement:listEvenements.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));

    }

    public function eventsOrganiseConPasseAction(Request $request){
        $orm= $this->getDoctrine()->getManager();
        $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
        $evenements = $eventRepos->EventsOrganisePasses($this->getUser()->getId());
        return $this->render('evenementsBundle:Evenement:listEvenements.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));

    }
    /*
    public function savedAction(Request $request){
        $orm= $this->getDoctrine()->getManager();
        $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
        $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
        $eventSig = $esRepos->findBy(array("user"=>$this->getUser()));
        $evenements = $eventRepos->custom();
        $evenements = array_filter($evenements, function($e) {
            $es = $e->getEvenementSauvegardes()->toArray();
            return in_array($this->getUser(), $es);
        });
        return $this->render('evenementsBundle:Evenement:listEvenements.html.twig',
            array("evenements"=>$evenements, "eventSig"=>$eventSig));
    }
    */
    public function renderPdfAction($id){
        $orm = $this->getDoctrine()->getManager();
        $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
        $inscriRepos = $orm->getRepository('evenementsBundle:Inscription');
        $event = $eventRepos->find($id);
        if ($event->getUser()==$this->getUser()){
            $inscription= $inscriRepos->findBy(array("evenement"=>$event));
            $html = $this->renderView('evenementsBundle:Evenement:pdf.html.twig', array("event"=>$event, "inscription"=>$inscription));
            return new PdfResponse(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
                'file.pdf'
            );
        }
        throw new NotFoundHttpException();
    }

}
