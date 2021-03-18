<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;

class ExtraHelperManagerAdminController extends BaseAdminController
{
    public function handlerAction(): Response
    {
        return $this->renderWithExtraParams(
            'Admin/FileManager/show_file_manager.html.twig',
            [
                '_sonata_admin' => 'admin.file_manager_dummy',
                'action' => 'show',
            ]
        );
    }
}
