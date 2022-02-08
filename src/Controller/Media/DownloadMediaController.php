<?php

namespace App\Controller\Media;

use App\Entity\Spending;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

final class DownloadMediaController extends AbstractController
{
    /**
     * @Route("/app/spending-media-download/{id}", name="media_download_spending_item", options={"expose"=false})
     * @ParamConverter("attachmentFile", class="App\Entity\Spending")
     */
    public function mediaDownloadSpendingAction(DownloadHandler $downloadHandler, Spending $attachmentFile): Response
    {
        return $downloadHandler->downloadObject($attachmentFile, 'documentFile', Spending::class, true, true);
    }

    /**
     * @Route("/app/spending-media-inline/{id}", name="media_inline_spending_item", options={"expose"=false})
     * @ParamConverter("attachmentFile", class="App\Entity\Spending")
     */
    public function mediaInlineSpendingAction(DownloadHandler $downloadHandler, Spending $attachmentFile): Response
    {
        return $downloadHandler->downloadObject($attachmentFile, 'documentFile', Spending::class, true, false);
    }
}
