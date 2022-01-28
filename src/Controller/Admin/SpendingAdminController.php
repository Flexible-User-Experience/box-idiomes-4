<?php

namespace App\Controller\Admin;

use App\Entity\Spending;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;

final class SpendingAdminController extends BaseAdminController
{
    public function duplicateAction(): Response
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var Spending $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        // new spending
        $newSpending = new Spending();
        $newSpending
            ->setDate(new DateTimeImmutable())
            ->setCategory($object->getCategory())
            ->setProvider($object->getProvider())
            ->setDescription($object->getDescription())
            ->setBaseAmount($object->getBaseAmount())
            ->setIsPayed(false)
            ->setPaymentMethod($object->getPaymentMethod())
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($newSpending);
        $em->flush();
        $this->addFlash('success', 'S\'ha duplicat la despesa núm. '.$object->getId().' amb la factura núm. '.$newSpending->getId().' correctament.');

        return $this->redirectToList();
    }
}
