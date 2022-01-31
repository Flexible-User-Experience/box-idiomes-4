<?php

namespace App\Controller;

use App\Form\Type\AdminLoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AdminLoginController extends AbstractController
{
    private AuthenticationUtils $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/admin/login", name="admin_app_login")
     */
    public function loginAction(RouterInterface $router): Response
    {
        $form = $this->createForm(AdminLoginForm::class, [
            'username' => $this->authenticationUtils->getLastUsername(),
            'target_path' => $router->generate('sonata_admin_dashboard'),
        ]);

        return $this->render('Admin/Security/login.html.twig', [
            'form' => $form->createView(),
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_app_logout")
     */
    public function logoutAction(): void
    {
        // Left empty intentionally because this will be handled by Symfony.
    }
}
