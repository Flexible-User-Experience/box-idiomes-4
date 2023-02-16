<?php

namespace App\Controller;

use App\Form\Model\FilterCalendarEventModel;
use App\Form\Type\AdminLoginForm;
use App\Form\Type\FilterCalendarEventsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/admin/filter-calendar", name="admin_app_filter_calendar")
     */
    public function filterCalendarAction(Request $request): Response
    {
        $calendarEventsFilter = new FilterCalendarEventModel();
        if ($request->query->get('reset')) {
            $request->getSession()->remove(FilterCalendarEventsType::SESSION_KEY);

            return $this->redirectToRoute('sonata_admin_dashboard');
        }
        if ($request->getSession()->has(FilterCalendarEventsType::SESSION_KEY)) {
            $calendarEventsFilter = $request->getSession()->get(FilterCalendarEventsType::SESSION_KEY);
        }
        $calendarEventsFilterForm = $this->createForm(FilterCalendarEventsType::class, $calendarEventsFilter);
        $calendarEventsFilterForm->handleRequest($request);
        if ($calendarEventsFilterForm->isSubmitted()) {
            $request->getSession()->set(FilterCalendarEventsType::SESSION_KEY, $calendarEventsFilter);

            return $this->redirectToRoute('sonata_admin_dashboard');
        }

        return $this->render(
            'Admin/Helpers/filter_calendar.html.twig',
            [
                'filter' => $calendarEventsFilterForm->createView(),
            ]
        );
    }
}
