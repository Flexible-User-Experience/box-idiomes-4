<?php

namespace App\Twig;

use App\Entity\ClassGroup;
use App\Entity\Event;
use App\Entity\Invoice;
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
use App\Service\SmartAssetsHelperService;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class AppExtension.
 *
 * @category Twig
 */
class AppExtension extends AbstractExtension
{
    /**
     * @var SmartAssetsHelperService
     */
    private $sahs;

    /**
     * @var ReceiptManager
     */
    private $rm;

    /**
     * @var Translator
     */
    private $ts;

    /**
     * Methods.
     */

    /**
     * AppExtension constructor.
     *
     * @param SmartAssetsHelperService $sahs
     * @param ReceiptManager           $rm
     * @param TranslatorInterface      $ts
     */
    public function __construct(SmartAssetsHelperService $sahs, ReceiptManager $rm, TranslatorInterface $ts)
    {
        $this->sahs = $sahs;
        $this->rm = $rm;
        $this->ts = $ts;
    }

    /**
     * Twig Tests.
     */

    /**
     * @return array
     */
    public function getTests()
    {
        return array(
            new TwigTest('instance_of', array($this, 'isInstanceOf')),
        );
    }

    /**
     * Return if a given object is instance of.
     *
     * @param object $var
     * @param string $instance
     *
     * @return string
     */
    public function isInstanceOf($var, $instance)
    {
        try {
            $reflexionClass = new ReflectionClass($instance);
            $result = $reflexionClass->isInstance($var);
        } catch (ReflectionException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Twig Functions.
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('generate_random_error_text', array($this, 'generateRandomErrorText')),
            new TwigFunction('is_receipt_invoiced', array($this, 'isReceiptInvoicedFunction')),
            new TwigFunction('get_absolute_asset_path_context_independent', array($this, 'getAbsoluteAssetPathContextIndependent')),
        );
    }

    /**
     * @param int $length length of Random String returned
     *
     * @return string
     */
    public function generateRandomErrorText($length = 1024)
    {
        // Character List to Pick from
        $chrList = '012 3456 789 abcdef ghij klmno pqrs tuvwxyz ABCD EFGHIJK LMN OPQ RSTU VWXYZ';
        // Minimum/Maximum times to repeat character List to seed from
        $chrRepeatMin = 1; // Minimum times to repeat the seed string
        $chrRepeatMax = 30; // Maximum times to repeat the seed string

        return substr(str_shuffle(str_repeat($chrList, mt_rand($chrRepeatMin, $chrRepeatMax))), 1, $length);
    }

    /**
     * @param Receipt $receipt
     *
     * @return bool
     */
    public function isReceiptInvoicedFunction(Receipt $receipt)
    {
        return $this->rm->isReceiptInvoiced($receipt);
    }

    /**
     * Always return absolute URL path, even in CLI contexts useful for background shell processes.
     *
     * @param string $assetPath
     *
     * @return string
     */
    public function getAbsoluteAssetPathContextIndependent($assetPath)
    {
        return $this->sahs->getAbsoluteAssetPathContextIndependent($assetPath);
    }

    /**
     * Twig Filters.
     */

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('draw_role_span', array($this, 'drawRoleSpan')),
            new TwigFilter('draw_teacher_color', array($this, 'drawTeacherColorSpan')),
            new TwigFilter('draw_teacher_absence_type', array($this, 'drawTeacherAbsenceType')),
            new TwigFilter('draw_class_group_color', array($this, 'drawClassGroupColorSpan')),
            new TwigFilter('draw_tariff_type', array($this, 'drawTariffType')),
            new TwigFilter('draw_event_classroom_type', array($this, 'drawEventClassroomType')),
            new TwigFilter('draw_invoice_month', array($this, 'drawInvoiceMonth')),
            new TwigFilter('draw_money', array($this, 'drawMoney')),
            new TwigFilter('draw_pre_register_season_type', array($this, 'drawPreRegisterSeasonType')),
            new TwigFilter('write_pre_register_season_string', array($this, 'writePreRegisterSeasonString')),
        );
    }

    /**
     * @param User $object
     *
     * @return string
     */
    public function drawRoleSpan(User $object)
    {
        $span = '';
        if ($object instanceof User && count($object->getRoles()) > 0) {
            $ea = UserRolesEnum::getReversedEnumArray();
            /** @var string $role */
            foreach ($object->getRoles() as $role) {
                if (UserRolesEnum::ROLE_CMS == $role) {
                    $span .= '<span class="label label-warning" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_CMS])).'</span>';
                } elseif (UserRolesEnum::ROLE_MANAGER == $role) {
                    $span .= '<span class="label label-info" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_MANAGER])).'</span>';
                } elseif (UserRolesEnum::ROLE_ADMIN == $role) {
                    $span .= '<span class="label label-primary" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_ADMIN])).'</span>';
                } elseif (UserRolesEnum::ROLE_SUPER_ADMIN == $role) {
                    $span .= '<span class="label label-danger" style="margin-right:10px">'.strtolower($this->ts->trans($ea[UserRolesEnum::ROLE_SUPER_ADMIN])).'</span>';
                }
            }
        } else {
            $span = '<span class="label label-success" style="margin-right:10px">---</span>';
        }

        return $span;
    }

    /**
     * @param Teacher $object
     *
     * @return string
     */
    public function drawTeacherColorSpan(Teacher $object)
    {
        $span = '';
        if ($object instanceof Teacher) {
            if (TeacherColorEnum::MAGENTA == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #EE388A"></span>';
            } elseif (TeacherColorEnum::BLUE == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #00ABE0"></span>';
            } elseif (TeacherColorEnum::YELLOW == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #FFCD38"></span>';
            } elseif (TeacherColorEnum::GREEN == $object->getColor()) {
                $span .= '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color: #CEC533"></span>';
            }
        } else {
            $span = '<span class="label label-success" style="margin-right:10px">---</span>';
        }

        return $span;
    }

    /**
     * @param ClassGroup $object
     *
     * @return string
     */
    public function drawClassGroupColorSpan(ClassGroup $object)
    {
        return '<span class="label" style="margin-right:10px; width: 100%; height: 12px; display: block; background-color:'.$object->getColor().'"></span>';
    }

    /**
     * @param TeacherAbsence $object
     *
     * @return string
     */
    public function drawTeacherAbsenceType(TeacherAbsence $object)
    {
        return '<div class="text-left">'.TeacherAbsenceTypeEnum::getReversedEnumArray()[$object->getType()].'</div>';
    }

    /**
     * @param Tariff $object
     *
     * @return string
     */
    public function drawTariffType(Tariff $object)
    {
        return TariffTypeEnum::getReversedEnumArray()[$object->getType()];
    }

    /**
     * @param Event $object
     *
     * @return string
     */
    public function drawEventClassroomType(Event $object)
    {
        return EventClassroomTypeEnum::getReversedEnumArray()[$object->getClassroom()];
    }

    /**
     * @param Invoice|Receipt $object
     *
     * @return string
     */
    public function drawInvoiceMonth($object)
    {
        return $object->getMonthNameString();
    }

    /**
     * @param mixed $object
     *
     * @return string
     */
    public function drawMoney($object)
    {
        $result = '<span class="text text-info">0,00 €</span>';
        if (is_numeric($object)) {
            if ($object < 0) {
                $result = '<span class="text text-danger">'.number_format($object, 2, ',', '.').' €</span>';
            } elseif ($object > 0) {
                $result = '<span class="text text-success">'.number_format($object, 2, ',', '.').' €</span>';
            } else {
                $result = '<span class="text text-info">0,00 €</span>';
            }
        }

        return $result;
    }

    /**
     * @param PreRegister $object
     *
     * @return string
     */
    public function drawPreRegisterSeasonType(PreRegister $object)
    {
        $span = '';
        if ($object instanceof PreRegister) {
            if (PreRegisterSeasonEnum::SEASON_JULY_2020 === $object->getSeason()) {
                $span = '<span class="label label-warning">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
            } elseif (PreRegisterSeasonEnum::SEASON_SEPTEMBER_2020 == $object->getSeason()) {
                $span = '<span class="label label-info">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
            } elseif (PreRegisterSeasonEnum::SEASON_JULY_2021 == $object->getSeason()) {
                $span = '<span class="label label-warning">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
            } elseif (PreRegisterSeasonEnum::SEASON_SEPTEMBER_2021 == $object->getSeason()) {
                $span = '<span class="label label-info">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
            } elseif (PreRegisterSeasonEnum::SEASON_JULY_2022 == $object->getSeason()) {
                $span = '<span class="label label-warning">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
            } elseif (PreRegisterSeasonEnum::SEASON_SEPTEMBER_2022 == $object->getSeason()) {
                $span = '<span class="label label-info">'.$this->ts->trans(PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()]).'</span>';
            }
        } else {
            $span = '<span class="label label-success" style="margin-right:10px">---</span>';
        }

        return $span;
    }

    /**
     * @param PreRegister $object
     *
     * @return string
     */
    public function writePreRegisterSeasonString(PreRegister $object)
    {
        return PreRegisterSeasonEnum::getReversedEnumArray()[$object->getSeason()];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}
