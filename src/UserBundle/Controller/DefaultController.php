<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use UserBundle\Form\ContactType;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@User/Default/index.html.twig');
    }
    public function testAction()
    {
        return $this->render('@User/Offre/creationOffre.html.twig');
    }
    public function profilAction()
    {
        return $this->render('@User/Portfolio/profil.html.twig');
    }
    public function adminAction()
    {
        return $this->render('@User/Admin/dashboard.html.twig');
    }
    public function googleAction()
    {
        return $this->render('@User/Default/google.html.twig');
    }

    public function contactAction(Request $request)
    {

        $form = $this->createForm(ContactType::class,null,array(

            'action' => $this->generateUrl('sponsoring_contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if($form->isValid()){

                if($this->sendEmail($form->getData())){
                    $this->addFlash('success','Votre e_mail a été bien envoyé !');

                    return $this->redirectToRoute('sponsoring_contact');
                }else{
                    var_dump("Erreur :(");
                }
            }
        }

        return $this->render('@User/Default/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function sendEmail($data){

        $myappContactMail = 'technologyevents2019@gmail.com';
        $myappContactPassword = 'Godofwar3';

        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("Our Code World Contact Form ". $data["subject"])
            ->setFrom(array($myappContactMail => "Message by ".$data["name"]))
            ->setTo(array(
                $myappContactMail => $myappContactMail
            ))
            ->setBody($data["message"]."<br>ContactMail :".$data["email"]);

        return $mailer->send($message);
    }

    public function mysubmitedAction(Request $request){

        $recaptcha = new ReCaptcha('6LefZp0UAAAAAC-780hEM6wPeVKR4YYPJIf0NYxU');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        if (!$resp->isSuccess()) {

            $message = "The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")";
        }
        else{

        }
    }

    public function payAction(Request $request){

        \Stripe\Stripe::setApiKey("sk_test_GjmFut25IhrBlSjB7IpiOrIT00JDSauH4W");

        \Stripe\Charge::create([
            "amount" => 2000,
            "currency" => "eur",
            "source" => $request->request->get('stripeToken'),
            "description" => "Payment de Rania "
        ]);

        return $this->render('@User/Default/payment.html.twig');
    }

    public function payyAction(Request $request){

        $em =$this->getDoctrine()->getManager();
        $pack = $this->getDoctrine()->getRepository(Pack::class)->findAll();

        $em->flush();
        if($_GET!=null) {
            $tot = $_GET['x'];

            $tot = (int)$tot * 100;

            \Stripe\Stripe::setApiKey("sk_test_GjmFut25IhrBlSjB7IpiOrIT00JDSauH4W");

            \Stripe\Charge::create([
                "amount" => $tot,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "description" => "Payment de Rania "
            ]);
        }

        return $this->render('@User/Default/payment.html.twig');
    }

    /**
     * @Route("/pay", name="payment_homepage")
     * @param Request $request
     * @return Response
     */

    public function paymentAction(Request $request){

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
                    //$redirect = $this->get('session')->get('premium_redirect');
                } catch (\Stripe\Error\Base $e) {
                    $this->addFlash('warning', sprintf('Unable to take payment, %s', $e instanceof \Stripe\Error\Card ? lcfirst($e->getMessage()) : 'please try again.'));
                    return $this->redirectToRoute('payment_homepage');
                } finally {
                    //return $this->redirect($redirect);
                    return $this->redirectToRoute('sponsoring_contact');
                }
            }
        }

        return $this->render('@User/Default/payment.html.twig', [
            'form' => $form->createView(),
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
        ]);
    }




}
