<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 25/03/2019
 * Time: 14:23
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\Demandes;
use UserBundle\Entity\Pack;
use UserBundle\Form\PackType;

class PackController extends Controller
{
    public function ajouterAction(Request $request,$id){

        $pack = new Pack();
        $form = $this->createForm(PackType::class,$pack);
        $form = $form->handleRequest($request);
        if($form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $d=$em->getRepository(Demandes::class)->find($id);
            $pack->setIdDemande($d);
            $pack->setIdUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
            $em->persist($pack);
            $em->flush();
            $this->addFlash('success','Pack créé avec succés !');
            return $this->redirectToRoute('mesDemandes_homepage');
        }
        return $this->render('@User/Pack/ajout.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function unPackAction($id) {

        $em = $this->getDoctrine()->getManager();
        $pack = $em->getRepository(Pack::class)->find($id);

        if (!$pack)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Pack/unpack.html.twig', array('pack' => $pack ));
    }

    public function unAction($id) {

        $em = $this->getDoctrine()->getManager();
        $pack = $em->getRepository(Pack::class)->find($id);

        if (!$pack)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Portfolio/detailPack.html.twig', array('pack' => $pack ));
    }


    public function modifAction(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $pack= $em->getRepository(Pack::class)->find($id);
        $form= $this->createForm(PackType::class,$pack);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($pack);
            $em->flush();
            $this->addFlash('success','pack modifié avec succés !');
            return $this->redirectToRoute('mesDemandes_homepage');
        }
        return $this->render('@User/Portfolio/modifPack.html.twig', array("form"=>$form->createView()));

    }

    public function supprimAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $pack = $em->getRepository(Pack::class)->find($id);
        $em->remove($pack);
        $em->flush();
        $this->addFlash('success','Pack supprimé avec succés !');
        return $this->redirectToRoute('mesDemandes_homepage');
    }

    public function packDemandeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demandes::class)->find($id);
        $pack = $em->getRepository(Pack::class)->findby(array('idDemande' => $demande));

        return $this->render('@User/Portfolio/mesDemandes.html.twig', array(
            'pack' => $pack,
        ));
    }

    public function userPackAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $pack = $em->getRepository(Pack::class)->findby(array('idUtilisateur' => $user));

        return $this->render('@User/Portfolio/mesPack.html.twig', array(
            'pack' => $pack,
        ));
    }

}