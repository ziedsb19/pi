<?php

namespace evenementsBundle\Controller;

use evenementsBundle\Entity\Inscription;
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

    public function evenementsOrganiseAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository("evenementsBundle:Evenement");
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $events = $repos->EventsOrganises($id);
        $jsonContent = $serializer->serialize($events, 'json',
            array("attributes"=>["id","titre","urlImage","date","adresse","user","prix","categories"]));
        return new response($jsonContent);
    }

    public function evenementsInscrisAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository("evenementsBundle:Inscription");
        $reposUser = $orm->getRepository("techEventsBundle:User");
        $user = $reposUser->find($id);
        $inscriptions = $repos->findBy(array("user"=>$user));
        $evenements = array();
        foreach ($inscriptions as $i)
            array_push($evenements, $i->getEvenement());

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonResponse = $serializer->serialize($evenements, 'json',
            array("attributes"=>["id","titre","urlImage","date","adresse","user","prix","categories"]));
        return new Response ($jsonResponse);
    }

    public function evenementsFavorisAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos = $orm->getRepository("evenementsBundle:Evenement");
        $reposUser = $orm->getRepository("techEventsBundle:User");
        $user = $reposUser->find($id);
        $evenements = $repos->findBy(array("disponibilite"=>1));
        $evenements_fav = array();
        foreach ($evenements as $event){
            if (in_array($user,$event->getEvenementSauvegardes()->toArray()))
                array_push($evenements_fav, $event);
        }
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonResponse = $serializer->serialize($evenements_fav, 'json',
            array("attributes"=>["id","titre","urlImage","date","adresse","user","prix","categories"]));
        return new Response ($jsonResponse);
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
        if ($req->isMethod('post') ){
            $orm=$this->getDoctrine()->getManager();
            $reposU= $orm->getRepository('techEventsBundle:User');
            $reposCat = $orm->getRepository('evenementsBundle:Categorie');
            $user = $reposU->find($id);
            $evenement->setUser($user);
            $evenement->setTitre($req->get("titre"));
            $evenement->setAdresse($req->get("adresse"));
            if ($req->get("description"))
                $evenement->setDescription($req->get("description"));
            $evenement->setDate(new \DateTime($req->get("date")));
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

    public function updateAction(Request $req, $id){
        if ($req->isMethod('post')){
            $orm = $this->getDoctrine()->getManager();
            $repos = $orm->getRepository('evenementsBundle:Evenement');
            $reposCat = $orm->getRepository('evenementsBundle:Categorie');
            $evenement = $repos->find($id);
            if ($evenement){
                $evenement->setTitre($req->get("titre"));
                $evenement->setAdresse($req->get("adresse"));
                $evenement->setDate(new \DateTime($req->get("date")));
                if ($req->get("description"))
                    $evenement->setDescription($req->get("description"));
                else
                    $evenement->setDescription(null);
                $categories =  $evenement->getCategories()->toArray();
                foreach ($categories as $cat)
                    $evenement->removeCategory($cat);
                if ($req->get("categorie")){
                    foreach ($req->get("categorie") as $id_cat) {
                        $cat = $reposCat->find($id_cat);
                        $evenement->addCategory($cat);
                    }
                }
                $orm->flush();
                return new Response("yes");
            }
        }
        throw new NotFoundHttpException();
    }

    public function addImageAction(Request $req, $id){
        if ($req->isMethod("post")) {
            $dossier = $this->getParameter('kernel.project_dir') . "/web/images/evenements/";
            $orm = $this->getDoctrine()->getManager();
            $repos = $orm->getRepository('evenementsBundle:Evenement');
            $event = $repos->find($id);
            if($event) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . "." . $ext;
                $file_tmp = $_FILES['file']['tmp_name'];
                if (move_uploaded_file($file_tmp, $dossier . $file_name)) {
                    $event->setUrlImage($file_name);
                    $orm->flush();
                }
                return new Response("yes");
            }
        }
        throw new NotFoundHttpException();
    }

    public function deleteAction($id){
        $orm= $this->getDoctrine()->getManager();
        $repos= $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event){
            $imagesRepos = $orm->getRepository('evenementsBundle:ImageEvenement');
            $esRepos = $orm->getRepository('evenementsBundle:EventSignales');
            $insciRepos = $orm->getRepository('evenementsBundle:Inscription');
            $images=$imagesRepos->findBy(array("evenement"=>$event));
            foreach ($images as $im){
                $orm->remove($im);
            }
            $esig = $esRepos->findBy(array("evenement"=>$event));
            foreach ($esig as $es){
                $orm->remove($es);
            }
            $inscri = $insciRepos->findBy(array("evenement"=>$event));
            foreach ($inscri as $ins){
                $orm->remove($ins);
            }
            $orm->remove($event);
            $orm->flush();
            return new Response("yes");
        }
        throw  new NotFoundHttpException();
    }

    public function inscriAction($id, $userId){
        $orm= $this->getDoctrine()->getManager();
        $eventRepos = $orm->getRepository('evenementsBundle:Evenement');
        $userRepos = $orm->getRepository('techEventsBundle:User');
        $event = $eventRepos->find($id);
        $user = $userRepos->find($userId);
        if ($event and $user) {
            $repos = $orm->getRepository('evenementsBundle:Inscription');
            $inscri = $repos->findOneBy(array("evenement" => $event, "user" => $user));
            if ($inscri){
                $orm->remove($inscri);
                $orm->flush();
                return new Response("no");
            }
            $inscri= new Inscription();
            $inscri->setEvenement($event);
            $inscri->setUser($user);
            $orm->persist($inscri);
            $orm->flush();
            return new Response("yes");
        }
        throw new NotFoundHttpException();
    }

    public function getCategoriesAction(){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Categorie');
        $categories = $repos->findAll();
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);
        return new JsonResponse($serializer->normalize($categories));
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

    public function isSavedAction($id,$user_id){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $reposUser = $orm->getRepository('techEventsBundle:User');
        $event = $repos->find($id);
        $user = $reposUser->find($user_id);
        if ($event and $user){
            if (in_array($user,$event->getEvenementSauvegardes()->toArray()))
                return new Response("yes");
            else
                return new Response("no");
        }
        throw new NotFoundHttpException();
    }
    
    public function toggleSaveAction($id,$user_id){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $reposUser = $orm->getRepository('techEventsBundle:User');
        $event = $repos->find($id);
        $user = $reposUser->find($user_id);
        if ($event and $user){
            if (in_array($user,$event->getEvenementSauvegardes()->toArray())){
                $event->removeEvenementSauvegarde($user);
                $orm->flush();
                return new Response("no");
            }
            else{
                $event->addEvenementSauvegarde($user);
                $orm->flush();
                return new Response("yes");
            }
        }
        throw new NotFoundHttpException();
    }

    public function deleteImageAction($id){
        $orm = $this->getDoctrine()->getManager();
        $repos = $orm->getRepository('evenementsBundle:Evenement');
        $event = $repos->find($id);
        if ($event){
            $event->setUrlImage(null);
            $orm->flush();
            return new Response("ok");
        }
        throw new NotFoundHttpException();
    }

}