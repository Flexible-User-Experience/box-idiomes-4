<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;

class ExtraHelperManagerAdminController extends BaseAdminController
{
    public function exportCalendarPdfListAction(string $start, string $end): Response
    {
        $this->addFlash('success', 'HIT '.$start.' '.$end);

        return $this->redirectToRoute('sonata_admin_dashboard');
    }
}
