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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

class AppExtension extends AbstractExtension
{
    private FileService $fs;
    private SmartAssetsHelperService $sahs;
    private ReceiptManager $rm;
    private TranslatorInterface $ts;

    public function __construct(FileService $fs, SmartAssetsHelperService $sahs, ReceiptManager $rm, TranslatorInterface $ts)
    {
        $this->fs = $fs;
        $this->sahs = $sahs;
        $this->rm = $rm;
        $this->ts = $ts;
    }

    /**
     * Twig Tests.
     */
    public function getTests(): array
    {
        return [
            new TwigTest('instance_of', [$this, 'isInstanceOf']),
            new TwigTest('pdf_file_type', [$this, 'isPdfFileType']),
            new TwigTest('audio_file_type', [$this, 'isAudioFileType']),
        ];
    }

    public function isInstanceOf($var, $instance): bool
    {
        return (new \ReflectionClass($instance))->isInstance($var);
    }

    public function isPdfFileType($object): bool
    {
        return $this->isInstanceOf($object, \SplFileInfo::class) && 'pdf' === strtolower($object->getExtension());
    }

    public function isAudioFileType($object): bool
    {
        return $this->isInstanceOf($object, \SplFileInfo::class) && ('mp3' === strtolower($object->getExtension()) || 'wav' === strtolower($object->getExtension()));
    }

    /**
     * Twig Functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('generate_random_error_text', [$this, 'generateRandomErrorText']),
            new TwigFunction('is_contact_message_document_pdf_file', [$this, 'isContactMessageDocumentPdfFile']),
            new TwigFunction('is_contact_message_document_image_file', [$this, 'isContactMessageDocumentImageFile']),
            new TwigFunction('is_receipt_invoiced', [$this, 'isReceiptInvoicedFunction']),
            new TwigFunction('get_absolute_asset_path_context_independent', [$this, 'getAbsoluteAssetPathContextIndependent']),
        ];
    }

    /**
     * @throws \Exception
     */
    public function generateRandomErrorText($length = 1024): string
    {
        // Character List to Pick from
        $chrList = '012 3456 789 abcdef ghij klmno pqrs tuvwxyz ABCD EFGHIJK LMN OPQ RSTU VWXYZ';
        // Minimum/Maximum times to repeat character List to seed from
        $chrRepeatMin = 1; // Minimum times to repeat the seed string
        $chrRepeatMax = 30; // Maximum times to repeat the seed string

        return substr(str_shuffle(str_repeat($chrList, random_int($chrRepeatMin, $chrRepeatMax))), 1, $length);
    }

    public function isContactMessageDocumentPdfFile(ContactMessage $contactMessage): bool
    {
        return $this->fs->isPdf($contactMessage, 'documentFile');
    }

    public function isContactMessageDocumentImageFile(ContactMessage $contactMessage): bool
    {
        return $this->fs->isImage($contactMessage, 'documentFile');
    }

    public function isReceiptInvoicedFunction(Receipt $receipt): bool
    {
        return $this->rm->isReceiptInvoiced($receipt);
    }

    /**
     * Always return absolute URL path, even in CLI contexts useful for background shell processes.
     */
    public function getAbsoluteAssetPathContextIndependent($assetPath): string
    {
        return $this->sahs->getAbsoluteAssetPathContextIndependent($assetPath);
    }

    /**
     * Twig Filters.
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('draw_role_span', [$this, 'drawRoleSpan']),
            new TwigFilter('draw_teacher_color', [$this, 'drawTeacherColorSpan']),
            new TwigFilter('draw_teacher_absence_type', [$this, 'drawTeacherAbsenceType']),
            new TwigFilter('draw_class_group_color', [$this, 'drawClassGroupColorSpan']),
            new TwigFilter('draw_tariff_type', [$this, 'drawTariffType']),
            new TwigFilter('draw_event_classroom_type', [$this, 'drawEventClassroomType']),
            new TwigFilter('draw_invoice_month', [$this, 'drawInvoiceMonth']),
            new TwigFilter('draw_money', [$this, 'drawMoney']),
            new TwigFilter('draw_pre_register_season_type', [$this, 'drawPreRegisterSeasonType']),
            new TwigFilter('write_pre_register_season_string', [$this, 'writePreRegisterSeasonString']),
            new TwigFilter('i', [$this, 'integerNumberFormattedString']),
            new TwigFilter('f', [$this, 'floatNumberFormattedString']),
            new TwigFilter('euro', [$this, 'euroFloatNumberFormattedString']),
        ];
    }

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

    public function drawClassGroupColorSpan(ClassGroup $object): string
    {
        return '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color:'.$object->getColor().'"></span>';
    }

    public function drawTeacherAbsenceType(TeacherAbsence $object): string
    {
        return '<div class="text-left">'.TeacherAbsenceTypeEnum::getReversedEnumArray()[$object->getType()].'</div>';
    }

    public function drawTariffType(Tariff $object): string
    {
        return TariffTypeEnum::getReversedEnumArray()[$object->getType()];
    }

    public function drawEventClassroomType(Event $object): string
    {
        return EventClassroomTypeEnum::getReversedEnumArray()[$object->getClassroom()];
    }

    public function drawInvoiceMonth($object): string
    {
        return $object->getMonthNameString();
    }

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

    public function writePreRegisterSeasonString(PreRegister $object): string
    {
        return PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()];
    }

    public function integerNumberFormattedString(int $value): string
    {
        return number_format($value, 0, '\'', '.');
    }

    public function floatNumberFormattedString(float $value): string
    {
        return number_format($value, 2, '\'', '.');
    }

    public function euroFloatNumberFormattedString(?float $value): string
    {
        return $value ? $this->floatNumberFormattedString($value).' €' : AbstractBase::DEFAULT_NULL_STRING;
    }
}
