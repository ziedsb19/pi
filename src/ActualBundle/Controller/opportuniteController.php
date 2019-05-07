<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/04/2019
 * Time: 13:26
 */

namespace ActualBundle\Controller;


use ActualBundle\Entity\Actualite;
use ActualBundle\Entity\Commentaires;
use ActualBundle\Entity\Opportunite;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;

class opportuniteController extends Controller
{
    public function ajoutOppAction(Request $request)
    {

        $opportunite =new Opportunite();
        $form=$this->createForm('ActualBundle\Form\OpportuniteType',$opportunite);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($opportunite);
            $em->flush($opportunite);
            $this->addFlash('success','Ajout avec succes');
        }

            return $this->render('@Actual/opportunite/ajoutOpp.html.twig',array(

                'form'=>$form->CreateView(),)) ;

}

    public function AfficheOppAction(Request $request)
    {
        $opp=$this->getDoctrine()->getRepository(Opportunite::class);
        $opp=$opp->findAll();

        /**
         * @var $paginator |Knp|Component|Pager|Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $opp,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)

        );


        return $this->render('@Actual/opportunite/afficheOpp.html.twig', array(
            'opp'=>$result
        ));
    }

    public function deleteAction()
    {
        $modele=$this->getDoctrine()->getRepository(Opportunite::class);
        $obj=$modele->find($_GET['id']);
        $modele=$this->getDoctrine()->getManager();
        $modele->remove($obj);
        $modele->flush();
        return $this->redirect("admin");
    }

    public function update2Action(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $modele=$em->getRepository(Opportunite::class)->find($id);
        if($request->isMethod('POST')){

            $modele->setOffre($request->get('offre'));
            $modele->setDescription($request->get('desc'));
           // $modele->setDate($request->get('date')|date('Y-m-d'));
            //$em->persist($modele);
            $em->flush();

            return $this->redirectToRoute('admin');
        }
        $this->addFlash('success','Modifier avec success !!');
        return $this->render('@Actual/opportunite/update.html.twig', array(
            'modele'=>$modele
        ));

        //}

    }

    public function ActualCardAction(Request $request)
    {
        $Actual=$this->getDoctrine()->getRepository(Actualite::class);
        $Actual=$Actual->findAll();


        /**
         * @var $paginator |Knp|Component|Pager|Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $Actual,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6)

        );


        return $this->render('@Actual/Actualite/ActualCard.html.twig', array(
            'Actual'=>$result
        ));
    }


    public function detailActualAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $Actual=$em->getRepository(Actualite::class)->find($id);
        if($request->isMethod('POST')){



            $em->flush();

            return $this->redirectToRoute('detailActual');
        }

        return $this->render('@Actual/Actualite/detailActual.html.twig', array(
            'Actual'=>$Actual
        ));

        //}
    }

    public function exportAction(){
        $opp=$this->getDoctrine()->getRepository(Opportunite::class);
        $opp=$opp->findAll();

        #Writer
        $writer = $this->container->get('egyg33k.csv.writer');
        $csv = $writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Offre', 'Description']);

        foreach ($opp as $oppp){
            $csv->insertOne([$oppp->getOffre(), $oppp->getDescription()]);
        }
        $csv->output('ListOpp.csv');
        die('export');

    }


    public function AjouterComAction(Request $request){
        $com =new Commentaires();
        $formC=$this->createForm('ActualBundle\Form\CommentairesType',$com);
        $formC->handleRequest($request);
        if($formC->isSubmitted() && $formC->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($com);
            $em->flush($com);
            $this->addFlash('success','Ajout avec succes');
        }

        return $this->render('@Actual/Actualite/detailActual.html.twig',array(

            'formC'=>$formC->CreateView(),)) ;

    }


    public function adminAction(Request $request)
    {
        $opp=$this->getDoctrine()->getRepository(Opportunite::class);
        $opp=$opp->findAll();

        /**
         * @var $paginator |Knp|Component|Pager|Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $opp,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)

        );


        return $this->render('@Actual/opportunite/admin.html.twig', array(
            'opp'=>$result
        ));
    }




}