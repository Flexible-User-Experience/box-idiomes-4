<?php

namespace App\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseAdminController extends Controller
{
    protected function resolveRequest(Request $request = null): ?Request
    {
        return $request ?? $this->getRequest();
    }
}
