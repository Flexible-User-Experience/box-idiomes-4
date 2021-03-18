<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;

class ExtraHelperManagerAdminController extends BaseAdminController
{
    public function exportCalendarPdfListAction(): Response
    {
        $this->addFlash('success', 'HIT');

        return $this->redirectToRoute('sonata_admin_dashboard');
    }
}
