<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 13/04/2019
 * Time: 14:17
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PremiumController extends Controller
{
    /**
     * @Route("/prem", name="premium_index")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('@User/Premium/index.html.twig', [
            'payment_config' => $this->getParameter('payment'),
        ]);
    }

    public function checkAction()
    {
        return $this->render('@User/Premium/checkout.html.twig');
    }

    /**
     * @Route("/payment", name="premium_payment")
     * @param Request $request
     * @return Response
     */
    public function paymentAction(Request $request)
    {
        $form = $this->get('form.factory')
            ->createNamedBuilder('payment-form')
            ->add('token', HiddenType::class, [
                'constraints' => [new NotBlank()],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get('app.client.stripe')->createPremiumCharge($this->getUser(), $form->get('token')->getData());
                    $redirect = $this->get('session')->get('premium_redirect');
                } catch (\Stripe\Error\Base $e) {
                    $this->addFlash('warning', sprintf('Unable to take payment, %s', $e instanceof \Stripe\Error\Card ? lcfirst($e->getMessage()) : 'please try again.'));
                    $redirect = $this->generateUrl('premium_payment');
                } finally {

                    $this->addFlash('success','Votre payment a été bien envoyé !');
                    //return $this->redirect($redirect);
                    return $this->redirectToRoute('premium_payment');
                }
            }
        }
        return $this->render('@User/Premium/payment.html.twig', [
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);
    }

}