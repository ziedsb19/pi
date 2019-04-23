<?php

namespace evenementsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use evenementsBundle\Entity\Evenement;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EvenementMobileController extends Controller
{
    public function indexAction(){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository("evenementsBundle:Evenement");
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $events = $repos->allEventsByViews()->getResult();
        $jsonContent = $serializer->serialize($events, 'json',
        array("attributes"=>["id","titre","urlImage","date","adresse","user","prix","categories"]));
        return new response($jsonContent);
    }

    public function showAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $reposI = $orm->getRepository('evenementsBundle:Inscription');
        $event = $repos->find($id);
        if ($event){
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $inscriptions= $reposI->findBy(array("evenement"=>$event));
            $event_array = $serializer->normalize($event,null,array());
            $inscri_array = $serializer->normalize($inscriptions,null,array());

            return new JsonResponse($serializer->normalize([$event_array,$inscri_array],'json'));

        }
        throw new NotFoundHttpException();
    }

    public function addAction(Request $req, $id){
        $evenement = new Evenement();
        if ($req->isMethod('post')){
            $orm=$this->getDoctrine()->getManager();
            $reposU= $orm->getRepository('techEventsBundle:User');
            $reposCat = $orm->getRepository('evenementsBundle:Categorie');
            $user = $reposU->find($id);
            $evenement->setUser($user);
            $evenement->setTitre($req->get("titre"));
            $evenement->setAdresse($req->get("adresse"));
            if ($req->get("description") != "" )
                $evenement->setDescription($req->get("description"));
            $evenement->setDate(new \DateTime($req->get("date")));
            if ($req->get("image") != "" and $req->get("image")!= null )
                $evenement->setUrlImage($req->get("image"));
            if ($req->get("prix") != 0)
                $evenement->setPrix($req->get("prix"));
            if ($req->get("billets") != 0)
                $evenement->setBilletsRestants($req->get("billets"));
            if ($req->get("categorie")){
                foreach ($req->get("categorie") as $id_cat) {
                    $cat = $reposCat->find($id_cat);
                    $evenement->addCategory($cat);
                }
            }
            $orm->persist($evenement);
            $orm->flush();
            return new Response($evenement->getId());
        }
        throw new NotFoundHttpException();
    }

}