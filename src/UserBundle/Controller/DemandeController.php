<?php


namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserBundle\Entity\Demandes;
use UserBundle\Entity\Pack;
use UserBundle\Form\DemandesType;
use Symfony\Component\HttpFoundation\Request;

class DemandeController extends Controller
{

    public function createAction()
    {
        return $this->render('@User/Portfolio/detailDemande.html.twig');
    }

    public function ajouterAction(Request $request){

        $demande = new Demandes();
        $form = $this->createForm(DemandesType::class,$demande);
        $form = $form->handleRequest($request);
        if($form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $demande->setIdUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
            $demande->setLieu($request->get('lieu'));
            $em->persist($demande);
            $em->flush();
            $this->addFlash('success','Demande créée avec succés !');
            return $this->redirectToRoute('ajoutPack_page',array('id'=>$demande->getId()));
        }
        return $this->render('@User/Demande/ajout.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    public  function afficheAction()
    {
        $demandes= $this->getDoctrine()->getRepository(Demandes::class)->findAll();
        return $this->render("@User/Default/tousDemandes.html.twig",array('demandes'=>$demandes));
    }

    public  function adAfficheAction()
    {
        $demandes= $this->getDoctrine()->getRepository(Demandes::class)->findAll();
        return $this->render("@User/Admin/demandesAdmin.html.twig",array('demandes'=>$demandes));
    }
    public function uneDemandeAction($id) {

        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demandes::class)->find($id);

        if (!$demande)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Demande/uneDemande.html.twig', array('demande' => $demande ));
    }

    public function uneAction($id) {

        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demandes::class)->find($id);
        $pack = $em->getRepository(Pack::class)->findOneBy(array('idDemande' => $demande));

        if (!$demande)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Portfolio/detailDemande.html.twig', array(
            'pack' => $pack,
            'demande' => $demande,

        ));
    }
    public function OneAction($id) {

        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demandes::class)->find($id);
        $pack = $em->getRepository(Pack::class)->findOneBy(array('idDemande' => $demande));

        if (!$demande)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Default/detailD.html.twig', array(
            'pack' => $pack,
            'demande' => $demande,

        ));
    }

    public function dAction($id) {

        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demandes::class)->find($id);
        $pack = $em->getRepository(Pack::class)->findOneBy(array('idDemande' => $demande));

        if (!$demande)
            throw $this->createNotFoundException('La page n\'existe pas.');

        return $this->render('@User/Demande/uneDemande.html.twig', array(
            'pack' => $pack,
            'demande' => $demande,

        ));
    }

    public function modifAction(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $demande= $em->getRepository(Demandes::class)->find($id);
        $form= $this->createForm(DemandesType::class,$demande);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($demande);
            $em->flush();
            $this->addFlash('success','Demande modifiée avec succés !');
            return $this->redirectToRoute('mesDemandes_homepage');
        }
        return $this->render('@User/Portfolio/modifDemande.html.twig', array("form"=>$form->createView()));

    }
    public function moAction(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $demande= $em->getRepository(Demandes::class)->find($id);
        $form= $this->createForm(DemandesType::class,$demande);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($demande);
            $em->flush();
            $this->addFlash('success','Demande modifiée avec succés !');
            return $this->redirectToRoute('adminDemandes_homepage');
        }
        return $this->render('@User/Demande/modifDemande.html.twig', array("form"=>$form->createView()));

    }



    public function supprimAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demandes::class)->find($id);
        $pack = $em->getRepository("UserBundle:Pack")->findOneBy(array("idDemande"=>$demande));
        $em->remove($pack);
        $em->remove($demande);
        $em->flush();
        $this->addFlash('success','Demande supprimée avec succés !');
        return $this->redirectToRoute('mesDemandes_homepage');
    }

    public function rechercherParNomAction(Request $request)
    {
        $demande= new Demandes();
        $form= $this->createForm(RecherchDemandeType::class,$demande);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $demandes= $this->getDoctrine()->getRepository(Demandes::class)
                ->findBy(array('titre'=>$demande->getTitre()));
        }
        else{
            $demandes= $this->getDoctrine()->getRepository(Demandes::class)->findAll();
        }
        return $this->render("@User/Demande/recherche.html.twig",array("form"=>$form->createView(),'demandes'=>$demandes));
    }
    public function findByUser(Request $request)
    {
        $demande= new Demandes();
        $form= $this->createForm(RechercheDemandeType::class,$demande);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $demandes= $this->getDoctrine()->getRepository(Demandes::class)
                ->findBy(array('idUtilisateur'=>$demande->getIdUtilisateur()));
        }
        else{
            $demandes= $this->getDoctrine()->getRepository(Demandes::class)->findAll();
        }
        return $this->render("@User/Demande/recherche.html.twig",array("form"=>$form->createView(),'demandes'=>$demandes));
    }

    public function findSponsorDQLAction(Request $request){
        $demande = new Demandes();
        $em=$this->getDoctrine()->getManager();
        $v = $em->getRepository(Demandes::class)->findAll();
        $form = $this->createForm(RechercheDemandeType::class,$demande);
        $form = $form->handleRequest($request);
        if($form->isValid()){

            $v =$em->getRepository(Demandes::class)->findSerieDQL($demande->getTitre());
        }
        return $this->render('@User/Demande/rechercheDemande.html.twig', array(
            'form'=>$form->createView(),'demande'=>$v
        ));
    }
    public function findbyUserDQLAction(Request $request){
        $demande = new Demandes();
        $em=$this->getDoctrine()->getManager();
        $d = $em->getRepository(Demandes::class)->findAll();
        $form = $this->createForm(RechercheDemandeType::class,$demande);
        $form = $form->handleRequest($request);
        if($form->isValid()){

            $d =$em->getRepository(Demandes::class)->findbyUserDQL($demande->getIdUtilisateur());
        }
        return $this->render('@User/Demande/userDemandes.html.twig', array(
            'form'=>$form->createView(),'demande'=>$d
        ));
    }

    public function userDemandesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $demandes = $em->getRepository(Demandes::class)->findby(array('idUtilisateur' => $user));

        return $this->render('@User/Portfolio/mesDemandes.html.twig', array(
            'demandes' => $demandes,
        ));
    }

    public function filterAction(Request $request){

        if ($request->isMethod('post')) {
            if (empty($request->get('titre')) && empty($request->get('lieu')) && empty($request->get('eMail'))) {
                return $this->redirectToRoute('Demandes_page');
            }
            else {
                $post_array = array("titre"=>"", "lieu"=>"", "eMail"=>"");
                $orm = $this->getDoctrine()->getManager();
                $repos = $orm->getRepository(Demandes::class);
                $query = $orm->createQueryBuilder();
                $query->select('d')
                    ->from('UserBundle:Demandes', 'd')
                    ->where('d.date> CURRENT_TIMESTAMP()');

                if (!empty($request->get('lieu'))){
                    $query->andWhere("d.lieu = :lieu");
                    $query->setParameter('lieu',$request->get('lieu'));
                    $post_array["lieu"]=$request->get('lieu');
                }
                if (!empty($request->get('titre'))){
                    $query->andWhere("d.titre = :titre");
                    $query->setParameter('titre',$request->get('titre'));
                    $post_array["titre"]=$request->get('titre');
                }
                if (!empty($request->get('eMail'))){
                    $query->andWhere("d.eMail = :eMail");
                    $query->setParameter('eMail',$request->get('eMail'));
                    $post_array["eMail"]=$request->get('eMail');
                }
                $query->add('orderBy', 'd.date ASC');
                $demandes =$query->getQuery()->getResult();

                return $this->render('UserBundle:Default:tousDemandes.html.twig',
                    array("demandes" => $demandes, "post_array"=>$post_array));

            }
        }
        throw new NotFoundHttpException();
    }

    public function rechercheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $titre = $request->get('titre');
        $lieu = $request->get('lieu');
        $date = $request->get('date');
        $eMail = $request->get('eMail');

        $demandes = $this->getDoctrine()->getManager()->getRepository(Demandes::class)->advancedSearch($titre, $lieu, $date, $eMail);

        return $this->render('@User/Portfolio/mesDemandes.html.twig', array(
            'demandes'=>$demandes
        ));
    }

    public function triAction(Request $request)
    {
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository(Demandes::class);

        if ($request->query->get("sortBy")==2){
            $query = $repos->allByDateModif();
        }

        elseif ($request->query->get("sortBy")==3){
            $query = $repos->allByDate();
        }
        else {
            $query = $repos->findAll();
        }
        $demandes =$query;

        return $this->render('@User/Default/tousDemandes.html.twig',
            array("demandes"=>$demandes));
    }



}