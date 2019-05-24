<?php

namespace FabBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use FabBundle\Entity\FabLab;
use FabBundle\Entity\Logiciel;
use FabBundle\Entity\Materiels;
use FabBundle\Entity\Panier;
use FabBundle\ImageUpload;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FabBundle\Form\FabLabType;
use FabBundle\Form\MaterielsType;
use FabBundle\Form\LogicielType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
class mobileController extends Controller
{


    public function afficherPanierAction($id)
    {
        $total=0;

        $panier = $this->getDoctrine()->getRepository('FabBundle:Panier')->findBy(array("iduser"=>$id));
        foreach ($panier as $p){
            $total += $p->getMateriel()->getPrix()*$p->getQuantite();
        }

        $serializer = new Serializer([new ObjectNormalizer()]);
       // $formatted1 = $serializer->normalize($total);
        $formatted2 = $serializer->normalize($panier);
       // return new JsonResponse([$formatted1,$formatted2]);
        return new JsonResponse($formatted2);
    }

    public function totalAction($id)
    {
        $total=0;

        $panier = $this->getDoctrine()->getRepository('FabBundle:Panier')->findBy(array("iduser"=>$id));
        foreach ($panier as $p){
            $total += $p->getMateriel()->getPrix()*$p->getQuantite();
        }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted1 = $serializer->normalize($total);
       // $formatted2 = $serializer->normalize($panier);
        //return new JsonResponse([$formatted1,$formatted2]);
        return new JsonResponse($formatted1);
    }





    public function afficherfrontfablabAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fabs = $em->getRepository('FabBundle:FabLab')->findAll();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($fabs);
        return new JsonResponse($formatted);
    }



    public function ajouterPanierAction($id, $idUs)
    {
        $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);
        $p = $this->getDoctrine()->getRepository('FabBundle:Panier')->findOneBy(array('materiel'=>$mat, 'iduser'=>$idUs));
        if($p == null)
        {
            $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);

            $panier = new Panier();
            $panier->setMateriel($mat);
            $panier->setIduser($idUs);
            $panier->setQuantite(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($panier);
            $em->flush();
        }
        else{
            if($p->getQuantite()<$p->getMateriel()->getStock()){
                $p->setQuantite($p->getQuantite()+1);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
            }
        }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($panier);
        //return new JsonResponse($formatted);
        return new Response ("yes");

    }


    public function ajoutPanAction(Request $request)
    {
        $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($request->get('id'));
        $us = $this->getDoctrine()->getRepository('techEventsBundle:User')->find($request->get('id'));
        $p = $this->getDoctrine()->getRepository('FabBundle:Panier')->findOneBy(array('materiel'=>$mat, 'iduser'=>$us));
        if($p == null)
        {
            $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($request->get('id'));

            $panier = new Panier();
            $panier->setMateriel($mat);
            $panier->setIduser($us);
            $panier->setQuantite(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($panier);
            $em->flush();
        }
        else{
            if($p->getQuantite()<$p->getMateriel()->getStock()){
                $p->setQuantite($p->getQuantite()+1);
                $em = $this->getDoctrine()->getManager();
                $em->persist($p);
                $em->flush();
            }
        }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($panier);
        return new JsonResponse($formatted);

    }




    public function afficherfrontMaterielAction()
    {
        //$catgs = $this->getDoctrine()->getRepository('FabBundle:CatgMateriel')->findAll();

        $mats = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted1 = $serializer->normalize($mats);
        //$formatted2 = $serializer->normalize($catgs, null);
        return new JsonResponse($formatted1);
    }



    public function afficherfrontLogicielAction()
    {
        $types = $this->getDoctrine()->getRepository('FabBundle:TypeLog')->findAll();

        $logs = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted1 = $serializer->normalize($types, null);
        $formatted2 = $serializer->normalize($logs, null);
        return new JsonResponse([$formatted1,$formatted2]);
}


    public function afficherMaterielFablabAction(Request $request, $id)
    {
        $mats = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();
        $matss = array();
        foreach ($mats as $mat ) {
            if ($mat->getFabLab() == null) {

            } elseif ($mat->getFabLab()->getId() == $id) {
                array_push($matss, $mat);
            }
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($matss);
        return new JsonResponse($formatted);

    }




    public function afficherLogicielFablabAction($id)
    {
        $logss = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();
        $logs = array();
        foreach ($logss as $log )
        {
            if($log->getFabLab() == null)
            { }
            elseif ($log->getFabLab()->getId() == $id)
            {
                array_push($logs,$log);
            }
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($logs);
        return new JsonResponse($formatted);
     }



    public function moinPanierAction($id)
    {  $pan = $this->getDoctrine()
        ->getRepository('FabBundle:Panier')
        ->find($id);
        if($pan->getQuantite()!=1){
            $pan->setQuantite($pan->getQuantite()-1);

            $em =$this->getDoctrine()->getManager();
            $em->persist($pan);
            $em->flush();
        }
        return new Response ("ok");

    }

    public function plusPanierAction($id)
    {  $pan = $this->getDoctrine()
        ->getRepository('FabBundle:Panier')
        ->find($id);

        if($pan->getQuantite()<$pan->getMateriel()->getStock())
        {
            $pan->setQuantite($pan->getQuantite()+1);

            $em =$this->getDoctrine()->getManager();
            $em->persist($pan);
            $em->flush();
        }
        return new Response ("ok");

    }


    public function SupprimerPanierAction($id)
    {  $em =$this->getDoctrine()->getManager();
        $pan = $em ->getRepository('FabBundle:Panier') ->find($id);
        if ($pan) {
            $em->remove($pan);
            $em->flush();
            return new Response ("yes");
        }
        return new Response("no");
    }




    public function detailFablabAction($id,Request $request)
    {
        $fab = $this->getDoctrine()->getRepository('FabBundle:FabLab')->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($fab);
        return new JsonResponse($formatted);
    }


    public function detailMaterielAction($id,Request $request)
    {
        $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($mat);
        return new JsonResponse($formatted);
    }


    public function detailLogicielAction($id,Request $request)
    {
        $log = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer -> normalize($log);
        return new JsonResponse($formatted);
    }




    public function connexionAction(Request $req){
        $orm= $this->getDoctrine()->getManager();
        $user = $orm->getRepository('techEventsBundle:User')
            ->findOneBy(array("username"=>$req->query->get("username")));
        if ($user){
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            if ($encoder->isPasswordValid($user->getPassword(), $req->query->get("password"), $user->getSalt())){
                $encoders = [new JsonEncoder()];
                $normalizers = [new ObjectNormalizer()];
                $serializer = new Serializer($normalizers,$encoders);
                return new JsonResponse($serializer->normalize($user, 'json',
                    array("attributes"=>["id","username","email", "nomPrenom", "adresse", "numeroTel"]) ));
            }
        }

        return new Response("no");
    }



    public function LoginAction($nom, $mdp)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('techEventsBundle:User')->findBy(array('username' => $nom));
        
        if (!empty($user)) {
            $username = $user[0]->getUsername();
            if ($this->Encoded($username, $mdp)) {
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(1);
                // Add Circular reference handler
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $serializer = new Serializer(array($normalizer));
                $data = $serializer->normalize($user);
                return new JsonResponse($data);
            } else {
                return new JsonResponse([]);
               // return new Response("no");
            }
        }else
            return new JsonResponse([]);
            //return new Response("no");

    }


    public function Encoded($username,$mdp)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $user = $user_manager->findUserByUsername($username);
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();
        if($encoder->isPasswordValid($user->getPassword(),$mdp,$salt))
            return true ;
        else
            return false ;
    }

    public function filterAction(Request $request){

        if ($request->isMethod('post')) {
            if (empty($request->get('nom')) && empty($request->get('ville')) && empty($request->get('responsable'))) {
                return $this->redirectToRoute('fab_front_affichage');
            }
            else {
                $post_array = array("nom"=>"", "ville"=>"", "responsable"=>"");
                $orm = $this->getDoctrine()->getManager();
                $repos = $orm->getRepository(FabLab::class);
                $query = $orm->createQueryBuilder();
                $query->select('f')
                    ->from('FabBundle:FabLab', 'f');


                if (!empty($request->get('nom'))){
                    $query->andWhere("f.nom = :nom");
                    $query->setParameter('nom',$request->get('nom'));
                    $post_array["nom"]=$request->get('nom');
                }
                if (!empty($request->get('ville'))){
                    $query->andWhere("f.ville = :ville");
                    $query->setParameter('ville',$request->get('ville'));
                    $post_array["ville"]=$request->get('ville');
                }
                if (!empty($request->get('responsable'))){
                    $query->andWhere("f.responsable = :responsable");
                    $query->setParameter('responsable',$request->get('responsable'));
                    $post_array["responsable"]=$request->get('responsable');
                }
                $query->add('orderBy', 'f.nom ASC');
                $fablab =$query->getQuery()->getResult();

                return $this->render('FabBundle:front:rechercheFab.html.twig',
                    array("fabs" => $fablab, "post_array"=>$post_array));

            }
        }
        throw new NotFoundHttpException();
    }



    public function payerAction(Request $request)
    {
        $em =$this->getDoctrine()->getManager();
        $produits = $this->getDoctrine()->getRepository('FabBundle:Panier')->findAll();

        foreach ($produits as $prod)
        {

            $p = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($prod->getMateriel()->getId());

            $p->setStock($p->getStock() - $prod->getQuantite());


            $em->remove($prod);

        }
        $em->flush();
        if($_GET!=null)
        {
            $tot=$_GET['x'];

            $tot=(int)$tot*100;


            \Stripe\Stripe::setApiKey("sk_test_B1wAs6qRmIHjP3mxlwaVl1Nk00IYtmcO23");

            \Stripe\Charge::create(array(
                "amount" =>  $tot,
                "currency" => "eur",
                "source" => "tok_visa", // obtained with Stripe.js
                "description" => "Charge for madison.robinson@example.com",
                "receipt_email"=>"dhia.boutej@esprit.tn",
            ));

            //return $this->redirectToRoute('fab_homepage');
            return new Response("yes");

        }

       // return $this->render('@Fab/front/payement.html.twig');
        return new Response("yes");
    }



}
