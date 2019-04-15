<?php

namespace AdminBundle\Controller;

use evenementsBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminEvenementController extends Controller
{
    public function evenementAction()
    {
        return $this->render('AdminBundle::adminEvent.html.twig');
    }

    public function indexEvenementsSignalesAction()
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:EventSignales');
        $events = $repos->findAll();
        return $this->render('AdminBundle:Evenement:eventSignales.html.twig',array("events"=>$events));
    }

    public function eventsBloquesAction()
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $events = $repos->findBy(array("disponibilite"=>0));
        return $this->render('AdminBundle:Evenement:eventBloques.html.twig',array("events"=>$events));
    }

    public function indexCategoriesAction(){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $reposCat = $orm->getRepository('evenementsBundle:Categorie');
        $categoriesCount = $repos->getCategories();
        $categories = $reposCat->findAll();
        return $this->render('AdminBundle:Evenement:categories.html.twig',
            array("categories"=>$categories, "categoriesCount"=>$categoriesCount));
    }

    public function chartAction(Request $req){
        $orm = $this->getDoctrine()->getManager();
        $result = [];

        if ($req->isMethod('post')){
            $month = $req->get('month');
            $year = $req->get('year');
            $query = "select day(date_modification) day, count(e.id) count from evenements e
                      where year(date_modification) = ".$year." and month(date_modification) = ".$month." and disponibilite = 1 
                      group by day(date_modification) ";
            $statement = $orm->getConnection()->prepare($query);
            $statement->execute();
            foreach ($statement->fetchAll() as $r) {
                $resulSet = array("day" => $r["day"], "count" => $r["count"]);
                $result [] = $resulSet;
            }
        }
        else {
            $query = "select month(date_modification) month, count(e.id) count from evenements e
                      where year(date_modification) = year(now()) and disponibilite = 1 
                      group by month(date_modification) ";
            $statement = $orm->getConnection()->prepare($query);
            $statement->execute();
            foreach ($statement->fetchAll() as $r) {
                $resulSet = array("month" => $r["month"], "count" => $r["count"]);
                $result [] = $resulSet;
            }
        }
        return new Response(json_encode($result));
    }

    public function updateCatAction(Request $req, $id){
        if ($req->isXmlHttpRequest()){
            if ($req->isMethod('post')){
                $orm = $this->getDoctrine()->getManager();
                $repos = $orm->getRepository('evenementsBundle:Categorie');
                $cat = $repos->find($id);
                $cat->setNom($req->get('nom'));
                $orm->flush();
                return new Response("yes");
            }
        }
        throw new NotFoundHttpException();
    }

    public function addCatAction(Request $req){
        if ($req->isMethod('post')) {
            $orm = $this->getDoctrine()->getManager();
            $cat = new Categorie();
            $cat->setNom($req->get('nom'));
            $orm->persist($cat);
            $orm->flush();
            return new Response("yes");
        }
        throw new NotFoundHttpException();
    }

    public function deleteCatAction($id){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Categorie');
        $cat = $repos->find($id);
        if ($cat) {
            $orm->remove($cat);
            $orm->flush();
            return $this->redirectToRoute('admin_evenement_categories');
        }
        throw new NotFoundHttpException();
    }

    public function blockEventAction($id){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event){
            $reposEs = $orm->getRepository('evenementsBundle:EventSignales');
            $eventsEs = $reposEs->findBy(array("evenement"=>$event));
            foreach ($eventsEs as $e){
                $orm->remove($e);
            }
            $event->setDisponibilite(0);
            $orm->flush();
            return $this->redirectToRoute('admin_evenement_events_signales');
        }
        throw new NotFoundHttpException();
    }

    public function debloquerAction($id){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event){
            $event->setDisponibilite(1);
            $orm->flush();
            return $this->redirectToRoute('admin_evenement_events_bloques');
        }
        throw new NotFoundHttpException();
    }
}
