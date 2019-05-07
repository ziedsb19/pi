<?php
/**
 * Created by PhpStorm.
 * User: toshiba
 * Date: 28/03/2019
 * Time: 12:53
 */

namespace FabBundle\Controller;


use FabBundle\Entity\TypeLog;
use FabBundle\Form\TypeLogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;




class TypeLogController extends Controller
{
    public function listerTypelogAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $typl = $em->getRepository('FabBundle:TypeLog')->findAll();
        $typeLogs  = $this->get('knp_paginator')->paginate(
            $typl,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );

        return $this->render('@Fab/back/typelog/listTypeL.html.twig', array(
            'typeLogs' => $typeLogs,
        ));
    }



    public function ajouterTypelogAction(Request $request)
    {
        $typeLog = new Typelog();
        $form = $this->createForm(TypeLogType::class, $typeLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeLog);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add("success", "This is a success message");

            return $this->redirectToRoute('typelog_show', array('id' => $typeLog->getId()));
        }

        return $this->render('@Fab/back/typelog/ajoutTypeL.html.twig', array(
            'typeLog' => $typeLog,
            'form' => $form->createView(),
        ));
    }


    public function afficherTypeLogAction(TypeLog $typeLog)
    {
       return $this->render('@Fab/back/typelog/affichTypeL.html.twig', array(
            'typeLog' => $typeLog,

        ));
    }



    public function editAction(Request $request, TypeLog $typeLog)
    {

        $editForm = $this->createForm(TypeLogType::class, $typeLog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typelog_edit', array('id' => $typeLog->getId()));
        }

        return $this->render('@Fab/back/typelog/modifTypeL.html.twig', array(
            'typeLog' => $typeLog,
            'edit_form' => $editForm->createView(),

        ));
    }


    public function supprimerTypelogAction(Request $request, $id)
    {
        $typeLog=$this->getDoctrine()->getRepository(TypeLog::class);
        $obj=$typeLog->find($id);
        $typeLog=$this->getDoctrine()->getManager();
        $typeLog->remove($obj);
        $typeLog->flush();

        return $this->redirectToRoute('typelog_index');
    }


}
