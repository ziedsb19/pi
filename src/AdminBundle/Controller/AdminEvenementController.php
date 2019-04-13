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

    public function indexCategoriesAction(){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $reposCat = $orm->getRepository('evenementsBundle:Categorie');
        $categoriesCount = $repos->getCategories();
        $categories = $reposCat->findAll();
        return $this->render('AdminBundle:Evenement:categories.html.twig',
            array("categories"=>$categories, "categoriesCount"=>$categoriesCount));
    }

    public function chartAction(){
        $orm= $this->getDoctrine()->getManager();
        $query = "select month(date_modification) month, count(e.id) count from evenements e group by month(date_modification)";
        $statement = $orm->getConnection()->prepare($query);
        $statement->execute();
        $result = [];
            foreach($statement->fetchAll() as $r) {
                $resulSet = array("month"=>$r["month"], "count"=>$r["count"]);
                $result [] = $resulSet ;
            }
        return new Response(json_encode($result));
    }
}
