<?php

namespace AdminBundle\Controller;

use evenementsBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminEvenementController extends Controller
{
    public function evenementAction()
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Categorie');
        $categories = $repos->findAll();
        return $this->render('AdminBundle:Admin:evenement.html.twig', array("categories"=>$categories));
    }
    public function addCatAction(Request $req){
        if ($req->isMethod("post")){
            $orm=$this->getDoctrine()->getManager();
            $categorie = new Categorie();
            $categorie->setNom($req->get("name"));
            $categorie->setCouleur($req->get("color"));
            $orm->persist($categorie);
            $orm->flush();
            return $this->redirectToRoute("admin_evenement");
        }
        throw  new NotFoundHttpException();

    }
}
