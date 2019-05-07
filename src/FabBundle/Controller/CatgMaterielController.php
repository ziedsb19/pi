<?php
/**
 * Created by PhpStorm.
 * User: toshiba
 * Date: 28/03/2019
 * Time: 12:50
 */

namespace FabBundle\Controller;

use FabBundle\Entity\CatgMateriel;
use FabBundle\Form\CatgMaterielType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;


class CatgMaterielController extends Controller
{


    public function ajoutcatAction(Request $request)
    {
        $catgMateriel = new Catgmateriel();
        $form = $this->createForm(CatgMaterielType::class, $catgMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($catgMateriel);
            $em->flush();

            $this->addFlash("success", "Validation Faite ");
                
        }
        $em = $this->getDoctrine()->getManager();
        $allcatgMat = $em->getRepository('FabBundle:CatgMateriel')->findAll();
        
        $catgMateriels = $this->get('knp_paginator')->paginate(
            $allcatgMat,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            5/*nbre d'éléments par page*/
        );
        return $this->render('@Fab/back/catgMateriel/ajoutcatmat.html.twig', array(
            'catgMateriel' => $catgMateriel,'catgMateriels' => $catgMateriels,
            'form' => $form->createView(),
        ));
    }




    public function afficherCatgAction(CatgMateriel $catgMateriel)
    {
        return $this->render('@Fab/back/catgMateriel/affichcat.html.twig', array(
            'catgMateriel' => $catgMateriel,

        ));


    }



    public function modifierCatAction(Request $request, CatgMateriel $catgMateriel)
    {

        $editForm = $this->createForm(CatgMaterielType::class, $catgMateriel);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('catgmateriel_edit', array('id' => $catgMateriel->getId()));
        }

        return $this->render('@Fab/back/catgMateriel/modifcat.html.twig', array(
            'catgMateriel' => $catgMateriel,
            'edit_form' => $editForm->createView(),

        ));

    }


    public function supprimerAction(Request $request, $id)
    {
            $catgMateriel=$this->getDoctrine()->getRepository(CatgMateriel::class);
            $obj=$catgMateriel->find($id);
            $catgMateriel = $this->getDoctrine()->getManager();
            $catgMateriel->remove($obj);
            $catgMateriel->flush();


        return $this->redirectToRoute('catgmateriel_index');


    }


    public function listerCatgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $catM = $em->getRepository('FabBundle:CatgMateriel')->findAll();
        $catgMateriels  = $this->get('knp_paginator')->paginate(
            $catM,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            5/*nbre d'éléments par page*/
        );

        return $this->render('@Fab/back/catgMateriel/listcat.html.twig', array(
            'catgMateriels' => $catgMateriels,
        ));
    }


}
