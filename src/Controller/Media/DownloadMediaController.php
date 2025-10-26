<?php

namespace App\Controller\Media;

use App\Entity\Spending;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

final class DownloadMediaController extends AbstractController
{
    #[Route('/app/spending-media-download/{id}', name: 'media_download_spending_item', options: ['expose' => false])]
    public function mediaDownloadSpendingAction(
        DownloadHandler $downloadHandler,
        #[MapEntity(mapping: ['id' => 'id'])] Spending $attachmentFile,
    ): Response {
        return $downloadHandler->downloadObject($attachmentFile, 'documentFile', Spending::class, true);
    }

    #[Route('/app/spending-media-inline/{id}', name: 'media_inline_spending_item', options: ['expose' => false])]
    public function mediaInlineSpendingAction(
        DownloadHandler $downloadHandler,
        #[MapEntity(mapping: ['id' => 'id'])] Spending $attachmentFile,
    ): Response {
        return $downloadHandler->downloadObject(
            object: $attachmentFile,
            field: 'documentFile',
            className: Spending::class,
            fileName: true,
            forceDownload: false
        );
    }
}
