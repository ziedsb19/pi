<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 13/04/2019
 * Time: 14:04
 */

namespace UserBundle\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $router;
    private $session;

    public function __construct(RouterInterface $router, SessionInterface $session)
    {
        $this->router = $router;
        $this->session = $session;
    }


    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        if ($request->attributes->get('requires_premium')) {
            $this->session->set('premium_redirect', $request->getUri());
            return new RedirectResponse($this->router->generate('premium_index'));
        }
    }


}