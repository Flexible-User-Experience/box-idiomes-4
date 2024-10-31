<?php

namespace App\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

final class FileManagerAdminController extends CRUDController
{
    public function handlerAction(): Response
    {
        return $this->render(
            'Admin/FileManager/show_file_manager.html.twig',
            [
                '_sonata_admin' => 'admin.file_manager_dummy',
                'action' => 'show',
            ]
        );
    }
}
