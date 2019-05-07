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


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FabBundle:front:cart.html.twig');
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

    public function afficherPanierAction()
    {
        $total=0;
        $id = $this->getUser()->getId();
        $panier = $this->getDoctrine()->getRepository('FabBundle:Panier')->findBy(array("iduser"=>$id));
        foreach ($panier as $p){
            $total += $p->getMateriel()->getPrix()*$p->getQuantite();
        }

        return $this->render('FabBundle:front:cart.html.twig',array(
            'panier'=>$panier,
            'total'=>$total

        ));
    }


    public function afficherfrontfablabAction(Request $request)
    {
        $produits = $this->getDoctrine()->getRepository('FabBundle:Panier')->findAll();
        $panier = array();
        foreach ($produits as $mat )
        {
            if($mat->getIduser() == 1)
            {
                array_push($panier,$mat);
            }

        }
        $matpanier = array();
        foreach ($panier as $p){
            $pp = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($p->getId());
            array_push($matpanier,$pp);
        }


        $em = $this->getDoctrine()->getManager();
        $fabs = $em->getRepository('FabBundle:Fablab')->findAll();


        return $this->render('FabBundle:front:afficherFablabFront.html.twig',array(
            'fabs'=>$fabs
            ,'matpanier'=>$matpanier

        ));
    }

    public function ajouterPanierAction($id)
    {
        $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);
        $p = $this->getDoctrine()->getRepository('FabBundle:Panier')->findOneBy(array('materiel'=>$mat, 'iduser'=>$this->getUser()->getId()));
       if($p == null)
        {
            $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);

        $panier = new Panier();

        $panier->setMateriel($mat);
        $panier->setIduser($this->getUser()->getId());
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



        return $this->redirectToRoute('mat_front_affichage');

    }

    public function afficherfrontMaterielAction(Request $request)
    {
        $catgs = $this->getDoctrine()->getRepository('FabBundle:CatgMateriel')->findAll();

        $mats = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();

        /**
         * @var $paginator |Knp|Component|pager|pagiantor
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
          $mats,
          $request->query->getInt('page',1),
            $request->query->getInt('limit',9)
        );
        return $this->render('FabBundle:front:afficherMaterielFront.html.twig',array(
            'mats'=>$result,
            'catgs'=>$catgs


        ));
    }
    public function afficherfrontLogicielAction(Request $request)
    {
        $types = $this->getDoctrine()->getRepository('FabBundle:TypeLog')->findAll();

        $logs = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();

        /**
         * @var $paginator |Knp|Component|pager|pagiantor
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $logs,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',9)
        );
        return $this->render('FabBundle:front:affichageLogicielFront.html.twig',array(
            'logs'=>$result,
            'types'=>$types


        ));
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



        return $this->render('FabBundle:front:affichageMaterielFablab.html.twig',array(
            'mats'=>$matss,



        ));
    }
    public function afficherLogicielFablabAction(Request $request, $id)
    {

        $logss = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();
        $logs = array();
        foreach ($logss as $log )
        {
            if($log->getFabLab() == null)
            {

            }
            elseif ($log->getFabLab()->getId() == $id)
            {
                array_push($logs,$log);
            }
        }

        /**
         * @var $paginator |Knp|Component|pager|pagiantor
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $logs,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',6)
        );
        return $this->render('FabBundle:front:affichageLogicielFablab.html.twig',array(
            'logs'=>$result


        ));
    }
    public function ajouterFablabAction(Request $request)
    {

        $fabLab = new Fablab();
        $form = $this->createForm(FabLabType::class, $fabLab);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $fabLab->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $fabLab->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($fabLab);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add("success", "Validation Faite");

        }
        return $this->render('@Fab/back/ajouterFablab.html.twig',array(
            'fabLab' => $fabLab,
            'form' => $form->createView(),
        ));


    }



    public function listFablabBackAction(Request $request)
    {
        $fabs = $this->getDoctrine()->getRepository('FabBundle:FabLab')->findAll();
        /**
         * @var $paginator |Knp|Component|pager|pagiantor
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $fabs,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );

        return $this->render('@Fab/back/afficherfablab.html.twig',array(
            'fabs'=>$result


        ));

    }


    public function listMateirelBackAction(Request $request)
    {
        $mats = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();

        /**
         * @var $paginator |Knp|Component|pager|pagiantor
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $mats,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)
        );

        return $this->render('@Fab/back/afficherMateriels.html.twig',array(
            'mats'=>$result


        ));

    }
    public function listLogicielBackAction(Request $request)
    {
        $logs = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();
        /**
         * @var $paginator |Knp|Component|pager|pagiantor
         */
        $paginator = $this->get('knp_paginator');
        $result=$paginator->paginate(
            $logs,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)
        );

        return $this->render('@Fab/back/afficherLogiciels.html.twig',array(
            'logs'=>$result


        ));

    }


    public function modifierFablabAction($id,Request $request)
    {
        $dossier = $this->getParameter('kernel.project_dir')."/web/uploads/images/";

        $fab = $this->getDoctrine()->getRepository('FabBundle:FabLab')->find($id);

        if ($request->isMethod('POST')) {

            $fab->setNom($request->get('nom'));
            $fab->setAdresse($request->get('adr'));
            $fab->setVille($request->get('ville'));
            $fab->setDescription($request->get('desc'));
            $fab->setResponsable($request->get('res'));
            $fab->setNumerotel($request->get('tel'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($fab);
            $em->flush();
            return $this->redirectToRoute('fab_affichageback');

        }


        return $this->render('FabBundle:back:modifierFablab.html.twig', array(

            'fab' => $fab,
            'test'=>$dossier


        ));
    }


    public function modifierLogicielAction($id,Request $request)
    {

        $log = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->find($id);

        if ($request->isMethod('POST')) {


            $log->setNom($request->get('nom'));
            $log->setPrix($request->get('prix'));
            $log->setNbrlicence($request->get('nbr'));
            $typelog = $this->getDoctrine()->getRepository('FabBundle:TypeLog')->find((int)$request->get('typelog'));
            $log->setTypeLog($typelog);
            $fab = $this->getDoctrine()->getRepository('FabBundle:FabLab')->find((int)$request->get('fab'));
            $log->setFabLab($fab);
            $four = $this->getDoctrine()->getRepository('FabBundle:FournisseurLog')->find((int)$request->get('four'));
            $log->setFournisseurLog($four);
            $log->setDescription($request->get('desc'));

            $log->setDatesortie($request->get('date'));



            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();

            return $this->redirectToRoute('log_affichageback');
        }


        $fabs = $this->getDoctrine()->getRepository('FabBundle:Fablab')->findAll();
        $types = $this->getDoctrine()->getRepository('FabBundle:TypeLog')->findAll();
        $fournisseur = $this->getDoctrine()->getRepository('FabBundle:FournisseurLog')->findAll();
        return $this->render('FabBundle:back:modifierLogiciel.html.twig', array(

            'log' => $log,
            'fabs' => $fabs,
            'types' => $types,
            'fournisseur' => $fournisseur

        ));
    }
    public function SupprimerFablabAction($id)
    {  $fab = $this->getDoctrine()
        ->getRepository('FabBundle:FabLab')
        ->find($id);

        $mats = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();

        foreach ($mats as $mat)
        {
            if($mat->getFabLab()==$fab)
                $mat->setFabLab(null);
                $em =$this->getDoctrine()->getManager();
            $em->persist($mat);
            $em->flush();
        }
        $em =$this->getDoctrine()->getManager();
        $em->remove($fab);
        $em->flush();
        return $this->redirectToRoute('fab_affichageback');

    }


    public function SupprimerPanierAction($id)
    {  $pan = $this->getDoctrine()
        ->getRepository('FabBundle:Panier')
        ->find($id);
        $em =$this->getDoctrine()->getManager();
        $em->remove($pan);
        $em->flush();
        return $this->redirectToRoute('fab_homepage');

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
        return $this->redirectToRoute('fab_homepage');

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
        return $this->redirectToRoute('fab_homepage');

    }

    public function SupprimerMaterielAction($id)
    {
        $produits = $this->getDoctrine()->getRepository('FabBundle:Panier')->findAll();


        $mat = $this->getDoctrine()
        ->getRepository('FabBundle:Materiels')
        ->find($id);

        foreach ($produits as$p){
            if ($p->getMateriel()==$mat){

                $em =$this->getDoctrine()->getManager();
                $em->remove($p);
                $em->flush();
            }
        }
    


        $em =$this->getDoctrine()->getManager();
        $em->remove($mat);
        $em->flush();
        return $this->redirectToRoute('mat_affichageback');

    }

    public function SupprimerLogicielAction($id)
    {
        $produits = $this->getDoctrine()->getRepository('FabBundle:Panier')->findAll();


        $log = $this->getDoctrine()
            ->getRepository('FabBundle:Logiciel')
            ->find($id);

       /*
        *  foreach ($produits as$p){
            if ($p->getMateriel()==$mat){

                $em =$this->getDoctrine()->getManager();
                $em->remove($p);
                $em->flush();
            }
        }
*/


        $em =$this->getDoctrine()->getManager();
        $em->remove($log);
        $em->flush();
        return $this->redirectToRoute('log_affichageback');

    }
    public function ajouterMaterielAction(Request $request)
    {

        $materiel = new Materiels();
        $form = $this->createForm(MaterielsType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $materiel->getImage();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $materiel->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($materiel);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add("success", "This is a success message");


        }

        $fabs = $this->getDoctrine()->getRepository('FabBundle:FabLab')->findAll();
        $cats = $this->getDoctrine()->getRepository('FabBundle:CatgMateriel')->findAll();
        return $this->render('@Fab/back/ajouterMateriel.html.twig',array(
            'fabs' => $fabs,
            'cats' => $cats,
            'form' => $form->createView(),

        ));


    }



    public function ajouterLogicielAction(Request $request)
    {

        $logiciel = new Logiciel();
        $form = $this->createForm(LogicielType::class, $logiciel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $logiciel->getUrlphoto();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );

            $logiciel->setUrlphoto($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($logiciel);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add("success", "This is a success message");


        }
        return $this->render('@Fab/back/ajouterLogiciel.html.twig', array(
            'logiciel' => $logiciel,
            'form' => $form->createView(),
        ));

    }



    public function modifierMaterielAction (Request $request, $id)
    {

        $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);

        if ($request->isMethod('POST')) {

            $mat->setNom($request->get('nom'));
            $mat->setPrix($request->get('prix'));
            $mat->setStock($request->get('stock'));
            $cat = $this->getDoctrine()->getRepository('FabBundle:CatgMateriel')->find((int)$request->get('cat'));
            $mat->setCatgMateriel($cat);
            $fab = $this->getDoctrine()->getRepository('FabBundle:Fablab')->find((int)$request->get('fab'));
            $mat->setFabLab($fab);
            $mat->setDescription($request->get('desc'));


            $em = $this->getDoctrine()->getManager();
            $em->persist($mat);
            $em->flush();
            return $this->redirectToRoute('mat_affichageback');

        }

        $fabs = $this->getDoctrine()->getRepository('FabBundle:Fablab')->findAll();
        $cats = $this->getDoctrine()->getRepository('FabBundle:CatgMateriel')->findAll();
        return $this->render('FabBundle:back:modifierMateriel.html.twig', array(

            'mat' => $mat,
            'fabs' => $fabs,
            'cats' => $cats

        ));
    }


    public function detailFablabAction($id,Request $request)
    {

        $fab = $this->getDoctrine()->getRepository('FabBundle:FabLab')->find($id);
        return $this->render('FabBundle:front:detailFablab.html.twig', array(

            'fab' => $fab,


        ));
    }

    public function detailMaterielAction($id,Request $request)
    {

        $mat = $this->getDoctrine()->getRepository('FabBundle:Materiels')->find($id);
        return $this->render('FabBundle:front:detailMateriel.html.twig', array(

            'mat' => $mat,


        ));
    }

    public function detailLogicielAction($id,Request $request)
    {

        $log = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->find($id);
        return $this->render('FabBundle:front:detailLogiciel.html.twig', array(

            'log' => $log,


        ));
    }



    public function afficherMaterielParTypeAction(Request $request)
    {
        $type = $request->get("type");


        $materiels = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();
        $mats = array();
        foreach ($materiels as $mat)
        {
            if($mat->getCatgMateriel()=="")
            {

            }
            elseif($mat->getCatgMateriel()->getId()== $type)
            {
                array_push($mats,$mat);

            }
        }
      
        return $this->render('FabBundle:front:affichageParCategirie.html.twig', array(

            'mats' => $mats,



        ));

    }



    public function afficherLogicielParTypeAction(Request $request)
    {
        $type = $request->get("type");


        $logiciels = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();
        $logs = array();
        foreach ($logiciels as $log)
        {
            if($log->getTypeLog()=="")
            {

            }
            elseif($log->getTypeLog()->getId()== $type)
            {
                array_push($logs,$log);

            }
        }

        return $this->render('FabBundle:front:affichageParTypeLogiciel.html.twig', array(

            'logs' => $logs,



        ));

    }


    public function statAction(Request $request)
    {

        $pc=0;
        $impr=0;
        $materiels = $this->getDoctrine()->getRepository('FabBundle:Materiels')->findAll();
        foreach ($materiels as $mat)
        {
            if($mat->getCatgMateriel()->getLibelle()=="PC")
            {
                $pc=$pc+1;
            }elseif ($mat->getCatgMateriel()->getLibelle()=="Imprimante")
            {
                $impr=$impr+1;
            }

        }




        $opensource=3;
        $payant=2;
        $logiciels = $this->getDoctrine()->getRepository('FabBundle:Logiciel')->findAll();
        foreach ($logiciels as $log)
        {
            if($log->getTypeLog()->getLibelle()=="Logiciel Open Source")
            {
                $opensource=$opensource+1;
            }elseif ($log->getTypeLog()->getLibelle()=="Logiciel Payant")
            {
                $payant=$payant+1;
            }

        }
        return $this->render('FabBundle:front:statistiques.html.twig', array(
'materiels'=>$materiels,
'pc'=>$pc,
'impr'=>$impr,
'payant'=>$payant,
'opensource'=>$opensource,


        ));


    }

    public function statistiquesAction(){

        $chart = new ColumnChart();
        $arrayData = [['fablabs','nombre de materiels']];
        $orm = $this->getDoctrine()->getManager();
        $cnx=$orm->getConnection();
        $statement = $cnx->prepare("SELECT f.nom nom, count(m.id) total FROM fab_lab f left join materiels m on f.id = m.fab_lab_id GROUP by f.nom; ");
        $statement->execute();
        $data = $statement->fetchAll();
        foreach($data as $row){
            array_push($arrayData, [$row['nom'],(int)$row['total']]);
        }
        $chart->getData()->setArrayToDataTable($arrayData);
        return $this->render('FabBundle:back:stat.html.twig',array('chart'=>$chart, 'array'=>print_r($data)));
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

            return $this->redirectToRoute('fab_homepage');


        }

        return $this->render('@Fab/front/payement.html.twig');

    }

    }
