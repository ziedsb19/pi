<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29/04/2019
 * Time: 17:55
 */

namespace ActualBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class webSerActController extends Controller
{
    public function allAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('ActualBundle:Actualite')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }





}