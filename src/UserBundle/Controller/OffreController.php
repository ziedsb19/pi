<?php


namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\Offres;
use UserBundle\Form\OffresType;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\RechercheOffreType;
use UserBundle\Repository\OffresRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OffreController extends Controller
{
    public function createAction()
    {
        return $this->render('@User/Offre/creationOffre.html.twig');
    }
    public function ajouterAction(Request $request){

        $offre = new Offres();
        $form = $this->createForm(OffresType::class,$offre);
        $form = $form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $offre->setIdUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
            $em->persist($offre);
            $em->flush();
            $this->addFlash('success','Offre créée avec succés !');
            return $this->redirectToRoute('profil_homepage');
        }
        return $this->render('@User/Offre/ajout.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    public  function afficheAction()
    {
        $offres= $this->getDoctrine()->getRepository(Offres::class)->findAll();
        return $this->render("@User/Default/tousOffres.html.twig",array('offres'=>$offres));
    }
    public  function adAfficheAction()
    {
        $offres= $this->getDoctrine()->getRepository(Offres::class)->findAll();
        return $this->render("@User/Admin/offresAdmin.html.twig",array('offres'=>$offres));
    }

    public function uneOffreAction($id) {

        $em = $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offres::class)->find($id);

        if (!$offre)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Offre/uneOffre.html.twig', array('offre' => $offre ));
    }

    public function uneAction($id) {

        $em = $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offres::class)->find($id);

        if (!$offre)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Portfolio/detailOffre.html.twig', array('offre' => $offre ));
    }
    public function oneAction($id) {

        $em = $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offres::class)->find($id);

        if (!$offre)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Default/detailO.html.twig', array('offre' => $offre ));
    }

    public function modifyAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $offre= $em->getRepository(Offres::class)->find($id);
        $form= $this->createForm(OffresType::class,$offre);
        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $em= $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();
            $this->addFlash('success','Offre modifiée avec succés !');
            return $this->redirectToRoute('adminOffres_homepage');
        }
        return $this->render('@User/Offre/modifOffres.html.twig', array("form"=>$form->createView()));

    }

    public function modifAction(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $offre= $em->getRepository(Offres::class)->find($id);
        $form= $this->createForm(OffresType::class,$offre);
        $form->handleRequest($request);
        if ($form->isSubmitted()){

            $em= $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();
            $this->addFlash('success','Offre modifiée avec succés !');
            return $this->redirectToRoute('profil_homepage');
        }
        return $this->render('@User/Portfolio/modifOffre.html.twig', array("form"=>$form->createView()));

    }

    public function supprimAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offres::class)->find($id);
        $em->remove($offre);
        $em->flush();
        $this->addFlash('success','Offre supprimée avec succés !');
        return $this->redirectToRoute('adminOffres_homepage');
    }

    public function suppriAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $offre = $em->getRepository(Offres::class)->find($id);
        $em->remove($offre);
        $em->flush();
        $this->addFlash('success','Offre supprimée avec succés !');
        return $this->redirectToRoute('profil_homepage');
    }

    public function rechercherParNomAction(Request $request)
    {
        $offre= new Offres();
        $form= $this->createForm(RechercheOffreType::class,$offre);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $offres= $this->getDoctrine()->getRepository(Offres::class)
                ->findBy(array('nomSponsors'=>$offre->getNomSponsors()));
        }
        else{
            $offres= $this->getDoctrine()->getRepository(Offres::class)->findAll();
        }
        return $this->render("@User/Offre/recherche.html.twig",array("form"=>$form->createView(),'offres'=>$offres));
    }
    public function findByUser(Request $request)
    {
        $offre= new Offres();
        $form= $this->createForm(RechercheOffreType::class,$offre);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $offres= $this->getDoctrine()->getRepository(Offres::class)
                ->findBy(array('idUtilisateur'=>$offre->getIdUtilisateur()));
        }
        else{
            $offres= $this->getDoctrine()->getRepository(Offres::class)->findAll();
        }
        return $this->render("@User/Offre/recherche.html.twig",array("form"=>$form->createView(),'offres'=>$offres));
    }

    public function findSponsorAction(Request $request){

        $offre = new Offres();
        $em=$this->getDoctrine()->getManager();
        $v = $em->getRepository(Offres::class)->findAll();
        $formm = $this->createForm(RechercheOffreType::class,$offre);
        $formm = $formm->handleRequest($request);
        if($formm->isValid()){

            $v =$em->getRepository(Offres::class)->findNameDQL($offre->getNomSponsors());
        }
        return $this->render('@User/Default/tousOffres.html.twig', array(
            'formm'=>$formm->createView(),'offres'=>$v
        ));
    }

    public function findbyUserDQLAction(Request $request){
        $offre = new Offres();
        $em=$this->getDoctrine()->getManager();
        $o = $em->getRepository(Offres::class)->findAll();
        $form = $this->createForm(RechercheOffreType::class,$offre);
        $form = $form->handleRequest($request);
        if($form->isValid()){

            $o =$em->getRepository(Offres::class)->findbyUserDQL($offre->getIdUtilisateur());
        }
        return $this->render('@User/Offre/userOffres.html.twig', array(
            'form'=>$form->createView(),'offre'=>$o
        ));
    }
    public function userOffresAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $offres = $em->getRepository(Offres::class)->findby(array('idUtilisateur' => $user));

        return $this->render('@User/Portfolio/profil.html.twig', array(
            'offres' => $offres,
        ));
    }
    public function findDQLAction(Request $request){

        $offre = new Offres();
        $em=$this->getDoctrine()->getManager();
        $offres = $em->getRepository(Offres::class)->findAll();
        $form = $this->createForm(RechercheOffreType::class,$offre);
        $form = $form->handleRequest($request);
        if($form->isValid()){

            $offres =$em->getRepository(Offres::class)->findSponsorDQL($offre->getNomSponsors());
        }
        return $this->render('@User/Default/searchOffres.html.twig', array(
            'form'=>$form->createView(),'offres'=>$offres
        ));
    }

    public function filterAction(Request $request){

        if ($request->isMethod('post')) {
            if (empty($request->get('nomSponsors')) && empty($request->get('adresse')) && empty($request->get('eMail'))) {
                return $this->redirectToRoute('Offres_page');
            }
            else {
                $post_array = array("nomSponsors"=>"", "adresse"=>"", "eMail"=>"");
                $orm = $this->getDoctrine()->getManager();
                $repos = $orm->getRepository(Offres::class);
                $query = $orm->createQueryBuilder();
                $query->select('o')
                    ->from('UserBundle:Offres', 'o');

                if (!empty($request->get('adresse'))){
                    $query->andWhere("o.adresse = :adresse");
                    $query->setParameter('adresse',$request->get('adresse'));
                    $post_array["adresse"]=$request->get('adresse');
                }
                if (!empty($request->get('nomSponsors'))){
                    $query->andWhere("o.nomSponsors = :nomSponsors");
                    $query->setParameter('nomSponsors',$request->get('nomSponsors'));
                    $post_array["nomSponsors"]=$request->get('nomSponsors');
                }
                if (!empty($request->get('eMail'))){
                    $query->andWhere("o.eMail = :eMail");
                    $query->setParameter('eMail',$request->get('eMail'));
                    $post_array["eMail"]=$request->get('eMail');
                }
                $query->add('orderBy', 'o.dateModif ASC');
                $offres =$query->getQuery()->getResult();

                return $this->render('UserBundle:Default:tousOffres.html.twig',
                    array("offres" => $offres, "post_array"=>$post_array));

            }
        }
        throw new NotFoundHttpException();
    }

    public function rechercheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nomSponsors = $request->get('nomSponsors');
        $adresse = $request->get('adresse');
        $dateModif = $request->get('dateModif');
        $eMail = $request->get('eMail');

        $offres = $this->getDoctrine()->getManager()->getRepository(Offres::class)->advancedSearch($nomSponsors, $adresse, $dateModif, $eMail);

        return $this->render('@User/Portfolio/profil.html.twig', array(
            'offres'=>$offres
        ));
    }

    public function triAction(Request $request)
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository(Offres::class);

        if ($request->query->get("sortBy")==2){
            $query = $repos->allByDateModif();
        }

        elseif ($request->query->get("sortBy")==3){
            $query = $repos->allByName();
        }
        else {
            $query = $repos->findAll();
        }
        $offres =$query;

        return $this->render('@User/Default/tousOffres.html.twig',
            array("offres"=>$offres));
    }



}