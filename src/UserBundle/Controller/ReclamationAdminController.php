<?php


namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Reclamations;
use UserBundle\Form\ReclamationsType;


class ReclamationAdminController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository(Reclamations::class)->findAll();

        return $this->render('@User/Admin/reclamationAdmin.html.twig', array(
            'entities' => $entities,
        ));
    }

    public  function afficheAction()
    {
        $entities= $this->getDoctrine()->getRepository(Reclamations::class)->findAll();
        return $this->render("@User/Admin/reclamationAdmin.html.twig",array('entities'=>$entities));
    }

    public function createAction(Request $request)
    {
        $entity = new Reclamations();
        $form = $this->createForm(ReclamationsType::class,$entity);
        $form = $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->addFlash('success','Reclamation créée avec succés !');

            return $this->redirectToRoute('adminReclamations_homepage');

        }

        return $this->render('@User/Reclamation/new.html.twig', array(
            'form'   => $form->createView()
        ));
    }

    public function editAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $entity= $em->getRepository(Reclamations::class)->find($id);
        $form= $this->createForm(ReclamationsType::class,$entity);
        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $em= $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->addFlash('success','Reclamation modifiée avec succés !');
            return $this->redirectToRoute('adminReclamations_homepage');
        }
        return $this->render('@User/Admin/edit.html.twig', array("form"=>$form->createView()));

    }

    public function deleteAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Reclamations::class)->find($id);
        $em->remove($entity);
        $em->flush();
        $this->addFlash('success','Reclamation supprimée avec succés !');
        return $this->redirectToRoute('adminReclamations_homepage');

    }

}