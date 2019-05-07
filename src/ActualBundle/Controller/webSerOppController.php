<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29/04/2019
 * Time: 17:55
 */

namespace ActualBundle\Controller;
use ActualBundle\Entity\Opportunite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;

class webSerOppController extends Controller
{
    public function allAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('ActualBundle:Opportunite')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = new Opportunite();
        $task->setOffre($request->get('offre'));
        $task->setDescription($request->get('description'));
        $em->persist($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);
    }



}