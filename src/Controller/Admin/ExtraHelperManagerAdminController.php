<?php

namespace App\Controller\Admin;

use App\Pdf\ExportCalendarToListBuilderPdf;
use App\Repository\EventRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExtraHelperManagerAdminController extends BaseAdminController
{
    public function exportCalendarPdfListAction(EventRepository $er, ExportCalendarToListBuilderPdf $eclb, TranslatorInterface $ts, string $start, string $end): Response
    {
        $startDate = DateTime::createFromFormat('Y-m-d', $start);
        $endDate = DateTime::createFromFormat('Y-m-d', $end);
        if ($startDate && $endDate) {
            if ($endDate->format('Y-m-d') >= $startDate->format('Y-m-d')) {
                $events = $er->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
                if (count($events) > 0) {
                    $pdf = $eclb->build($events);
                    $this->addFlash('success', count($events).' found.');
                } else {
                    $this->addFlash('warning', $ts->trans('backend.admin.calendar.export.error.no_items_found', [
                        '%start%' => $startDate->format('d/m/Y'),
                        '%end%' => $endDate->format('d/m/Y'),
                    ]));
                }
            } else {
                $this->addFlash('danger', $ts->trans('backend.admin.calendar.export.error.end_date_period'));
            }
        } else {
            $this->addFlash('danger', $ts->trans('backend.admin.calendar.export.error.no_dates_found'));
        }

        return $this->redirectToRoute('sonata_admin_dashboard');
    }
}
