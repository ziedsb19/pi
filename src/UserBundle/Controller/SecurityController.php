<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 14/04/2019
 * Time: 13:36
 */

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends Controller
{
    /**
     * @Route("/home" , name="homepage")
     */
    public function redirectAction()
    {
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN')) {

            return $this->render('@User/Admin/offresAdmin.html.twig');
        }

        else if ($authChecker->isGranted('ROLE_USER')) {
           return $this->render('@User/Portfolio/profil.html.twig');
       }

        else {
            return $this->render('@FOSUser/Security/login.html.twig');
        }

    }


    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        /** @var $session Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : null;

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));
    }

}