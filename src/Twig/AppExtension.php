<?php

namespace App\Twig;

use App\Entity\AbstractBase;
use App\Entity\ClassGroup;
use App\Entity\ContactMessage;
use App\Entity\Event;
use App\Entity\PreRegister;
use App\Entity\Receipt;
use App\Entity\Tariff;
use App\Entity\Teacher;
use App\Entity\TeacherAbsence;
use App\Entity\User;
use App\Enum\EventClassroomTypeEnum;
use App\Enum\PreRegisterSeasonEnum;
use App\Enum\TariffTypeEnum;
use App\Enum\TeacherAbsenceTypeEnum;
use App\Enum\TeacherColorEnum;
use App\Enum\UserRolesEnum;
use App\Manager\ReceiptManager;
use App\Service\FileService;
use App\Service\SmartAssetsHelperService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;
use Twig\Attribute\AsTwigTest;

final readonly class AppExtension
{
    public function __construct(
        private FileService $fs,
        private SmartAssetsHelperService $sahs,
        private ReceiptManager $rm,
        private TranslatorInterface $ts,
    ) {
    }

    // tests
    #[AsTwigTest('instance_of')]
    public function isInstanceOf($var, $instance): bool
    {
        return (new \ReflectionClass($instance))->isInstance($var);
    }

    #[AsTwigTest('pdf_file_type')]
    public function isPdfFileType($object): bool
    {
        return $this->isInstanceOf($object, \SplFileInfo::class) && 'pdf' === strtolower($object->getExtension());
    }

    #[AsTwigTest('audio_file_type')]
    public function isAudioFileType($object): bool
    {
        return $this->isInstanceOf($object, \SplFileInfo::class) && ('mp3' === strtolower($object->getExtension()) || 'wav' === strtolower($object->getExtension()));
    }

    // functions
    #[AsTwigFunction('generate_random_error_text')]
    public function generateRandomErrorText($length = 1024): string
    {
        // Character List to Pick from
        $chrList = '012 3456 789 abcdef ghij klmno pqrs tuvwxyz ABCD EFGHIJK LMN OPQ RSTU VWXYZ';
        // Minimum/Maximum times to repeat character List to seed from
        $chrRepeatMin = 1; // Minimum times to repeat the seed string
        $chrRepeatMax = 30; // Maximum times to repeat the seed string

        return substr(str_shuffle(str_repeat($chrList, random_int($chrRepeatMin, $chrRepeatMax))), 1, $length);
    }

    #[AsTwigFunction('is_contact_message_document_pdf_file')]
    public function isContactMessageDocumentPdfFile(ContactMessage $contactMessage): bool
    {
        return $this->fs->isPdf($contactMessage, 'documentFile');
    }

    #[AsTwigFunction('is_contact_message_document_image_file')]
    public function isContactMessageDocumentImageFile(ContactMessage $contactMessage): bool
    {
        return $this->fs->isImage($contactMessage, 'documentFile');
    }

    #[AsTwigFunction('is_receipt_invoiced')]
    public function isReceiptInvoicedFunction(Receipt $receipt): bool
    {
        return $this->rm->isReceiptInvoiced($receipt);
    }

    /**
     * Always return absolute URL path, even in CLI contexts useful for background shell processes.
     */
    #[AsTwigFunction('get_absolute_asset_path_context_independent')]
    public function getAbsoluteAssetPathContextIndependent($assetPath): string
    {
        return $this->sahs->getAbsoluteAssetPathContextIndependent($assetPath);
    }

    // filters
    #[AsTwigFilter('draw_role_span')]
    public function drawRoleSpan(User $object): string
    {
        $span = '';
        if (count($object->getRoles()) > 0) {
            $ea = UserRolesEnum::getReversedEnumArray();
            /** @var string $role */
            foreach ($object->getRoles() as $role) {
                if (UserRolesEnum::ROLE_CMS === $role) {
                    $span .= '<span class="label label-warning" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_CMS])).'</span>';
                } elseif (UserRolesEnum::ROLE_MANAGER === $role) {
                    $span .= '<span class="label label-info" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_MANAGER])).'</span>';
                } elseif (UserRolesEnum::ROLE_ADMIN === $role) {
                    $span .= '<span class="label label-primary" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_ADMIN])).'</span>';
                } elseif (UserRolesEnum::ROLE_SUPER_ADMIN === $role) {
                    $span .= '<span class="label label-danger" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_SUPER_ADMIN])).'</span>';
                }
            }
        } else {
            $span = '<span class="label label-success" style="margin-right:10px">---</span>';
        }

        return $span;
    }

    #[AsTwigFilter('draw_teacher_color')]
    public function drawTeacherColorSpan(Teacher $object): string
    {
        $span = '';
        if (TeacherColorEnum::MAGENTA === $object->getColor()) {
            $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #EE388A"></span>';
        } elseif (TeacherColorEnum::BLUE === $object->getColor()) {
            $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #00ABE0"></span>';
        } elseif (TeacherColorEnum::YELLOW === $object->getColor()) {
            $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #FFCD38"></span>';
        } elseif (TeacherColorEnum::GREEN === $object->getColor()) {
            $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #80C66A"></span>';
        }

        return $span;
    }

    #[AsTwigFilter('draw_class_group_color')]
    public function drawClassGroupColorSpan(ClassGroup $object): string
    {
        return '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color:'.$object->getColor().'"></span>';
    }

    #[AsTwigFilter('draw_teacher_absence_type')]
    public function drawTeacherAbsenceType(TeacherAbsence $object): string
    {
        return '<div class="text-left">'.TeacherAbsenceTypeEnum::getReversedEnumArray()[$object->getType()].'</div>';
    }

    #[AsTwigFilter('draw_tariff_type')]
    public function drawTariffType(Tariff $object): string
    {
        return TariffTypeEnum::getReversedEnumArray()[$object->getType()];
    }

    #[AsTwigFilter('draw_event_classroom_type')]
    public function drawEventClassroomType(Event $object): string
    {
        return EventClassroomTypeEnum::getReversedEnumArray()[$object->getClassroom()];
    }

    #[AsTwigFilter('draw_invoice_month')]
    public function drawInvoiceMonth($object): string
    {
        return $object->getMonthNameString();
    }

    #[AsTwigFilter('draw_money')]
    public function drawMoney($object): string
    {
        $result = '<span class="text text-info">0,00 €</span>';
        if (is_numeric($object)) {
            if ($object < 0) {
                $result = '<span class="text text-danger">'.number_format($object, 2, ',', '.').' €</span>';
            } elseif ($object > 0) {
                $result = '<span class="text text-success">'.number_format($object, 2, ',', '.').' €</span>';
            }
        }

        return $result;
    }

    #[AsTwigFilter('draw_pre_register_season_type')]
    public function drawPreRegisterSeasonType(PreRegister $object): string
    {
        $span = '';
        if (PreRegisterSeasonEnum::SEASON_JULY_2020 === $object->getSeason()) {
            $span = '<span class="label label-warning">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
        } elseif (PreRegisterSeasonEnum::SEASON_SEPTEMBER_2020 === $object->getSeason()) {
            $span = '<span class="label label-info">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
        } elseif (PreRegisterSeasonEnum::SEASON_JULY_2021 === $object->getSeason()) {
            $span = '<span class="label label-warning">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
        } elseif (PreRegisterSeasonEnum::SEASON_SEPTEMBER_2021 === $object->getSeason()) {
            $span = '<span class="label label-info">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
        } elseif (PreRegisterSeasonEnum::SEASON_JULY_2022 === $object->getSeason()) {
            $span = '<span class="label label-warning">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
        } elseif (PreRegisterSeasonEnum::SEASON_SEPTEMBER_2022 === $object->getSeason()) {
            $span = '<span class="label label-info">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
        }

        return $span;
    }

    #[AsTwigFilter('write_pre_register_season_string')]
    public function writePreRegisterSeasonString(PreRegister $object): string
    {
        return PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()];
    }

    #[AsTwigFilter('i')]
    public function integerNumberFormattedString(int $value): string
    {
        return number_format($value, 0, '\'', '.');
    }

    #[AsTwigFilter('f')]
    public function floatNumberFormattedString(float $value): string
    {
        return number_format($value, 2, '\'', '.');
    }

    #[AsTwigFilter('euro')]
    public function euroFloatNumberFormattedString(?float $value): string
    {
        return $value ? $this->floatNumberFormattedString($value).' €' : AbstractBase::DEFAULT_NULL_STRING;
    }
}
