<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BackendCmsControllerTest extends WebTestCase
{
    public function testAdminLoginPageIsSuccessful(): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', '/admin/login');
        self::assertResponseIsSuccessful();
        $client->request('GET', '/reset-password');
        self::assertResponseIsSuccessful();
    }

    #[DataProvider('provideSuccessfulUrls')]
    public function testAdminPagesAreSuccessful(string $url): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public static function provideSuccessfulUrls(): array
    {
        return [
            ['/admin/dashboard'],
            ['/admin/teachers/teacher/list'],
            ['/admin/teachers/teacher/create'],
            ['/admin/teachers/teacher/1/edit'],
            ['/admin/teachers/teacher/1/detail'],
        ];
    }

    #[DataProvider('provideForbiddenUrls')]
    public function testAdminPagesAreForbidden(string $url): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', $url);

        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public static function provideForbiddenUrls(): array
    {
        return [
            ['/admin/students/student/list'],
            ['/admin/students/student/create'],
            ['/admin/students/student/1/edit'],
            ['/admin/students/student/1/delete'],
            ['/admin/students/student/1/show'],
            ['/admin/students/student/1/image-rights'],
            ['/admin/students/student/1/sepa-agreement'],
            ['/admin/students/student/mailing'],
            ['/admin/teachers/absence/list'],
            ['/admin/teachers/absence/create'],
            ['/admin/teachers/absence/1/edit'],
            ['/admin/students/absence/1/delete'],
            ['/admin/students/absence/1/notification'],
            ['/admin/students/parent/list'],
            ['/admin/students/parent/create'],
            ['/admin/students/parent/1/edit'],
            ['/admin/students/pre-register/list'],
            ['/admin/students/pre-register/1/delete'],
            ['/admin/students/pre-register/1/show'],
            ['/admin/teachers/absence/list'],
            ['/admin/teachers/absence/create'],
            ['/admin/teachers/absence/1/edit'],
            ['/admin/classrooms/group/list'],
            ['/admin/classrooms/group/create'],
            ['/admin/classrooms/group/1/edit'],
            ['/admin/classrooms/group/1/get-group-emails'],
            ['/admin/classrooms/timetable/list'],
            ['/admin/classrooms/timetable/create'],
            ['/admin/classrooms/timetable/1/edit'],
            ['/admin/classrooms/timetable/2/batch-edit'],
            ['/admin/classrooms/timetable/2/batch-delete'],
            ['/admin/classrooms/timetable/2/api/get'],
            ['/admin/classrooms/timetable/2/api/1/attended-the-class'],
            ['/admin/classrooms/timetable/2/api/1/not-attended-the-class'],
            ['/admin/classrooms/training-center/list'],
            ['/admin/classrooms/training-center/create'],
            ['/admin/classrooms/training-center/1/edit'],
            ['/admin/billings/tariff/list'],
            ['/admin/billings/tariff/create'],
            ['/admin/billings/tariff/1/edit'],
            ['/admin/billings/receipt/list'],
            ['/admin/billings/receipt/create'],
            ['/admin/billings/receipt/1/edit'],
            ['/admin/billings/receipt/1/delete'],
            ['/admin/billings/receipt/generate'],
            ['/admin/billings/receipt/creator'],
            ['/admin/billings/receipt/1/create-invoice'],
            ['/admin/billings/receipt/1/reminder-pdf'],
            ['/admin/billings/receipt/1/reminder-send'],
            ['/admin/billings/receipt/1/pdf'],
            ['/admin/billings/receipt/1/send'],
            ['/admin/billings/receipt/1/generate-direct-debit-xml'],
            ['/admin/billings/receipt-line/list'],
            ['/admin/billings/receipt-line/create'],
            ['/admin/billings/receipt-line/1/edit'],
            ['/admin/billings/receipt-line/1/delete'],
            ['/admin/billings/receipt-group/list'],
            ['/admin/billings/receipt-group/1/delete'],
            ['/admin/billings/invoice/list'],
            ['/admin/billings/invoice/create'],
            ['/admin/billings/invoice/1/edit'],
            ['/admin/billings/invoice/1/delete'],
            ['/admin/billings/invoice/1/duplicate'],
            ['/admin/billings/invoice/1/pdf'],
            ['/admin/billings/invoice/1/send'],
            ['/admin/billings/invoice/1/generate-direct-debit-xml'],
            ['/admin/billings/invoice-line/list'],
            ['/admin/billings/invoice-line/create'],
            ['/admin/billings/invoice-line/1/edit'],
            ['/admin/billings/invoice-line/1/delete'],
            ['/admin/purchases/provider/list'],
            ['/admin/purchases/provider/create'],
            ['/admin/purchases/provider/1/edit'],
            ['/admin/purchases/spending-category/list'],
            ['/admin/purchases/spending-category/create'],
            ['/admin/purchases/spending-category/1/edit'],
            ['/admin/purchases/spending-category/1/delete'],
            ['/admin/purchases/spending/list'],
            ['/admin/purchases/spending/create'],
            ['/admin/purchases/spending/1/edit'],
            ['/admin/purchases/spending/1/delete'],
            ['/admin/purchases/spending/1/duplicate'],
            ['/admin/contacts/message/list'],
            ['/admin/contacts/message/1/delete'],
            ['/admin/contacts/message/1/show'],
            ['/admin/contacts/message/1/answer'],
            ['/admin/contacts/newsletter/list'],
            ['/admin/contacts/newsletter/1/show'],
            ['/admin/contacts/newsletter/1/answer'],
            ['/admin/administrations/bank-creditor-sepa/list'],
            ['/admin/administrations/bank-creditor-sepa/create'],
            ['/admin/administrations/bank-creditor-sepa/1/edit'],
            ['/admin/administrations/bank-creditor-sepa/1/delete'],
            ['/admin/administrations/province/list'],
            ['/admin/administrations/province/create'],
            ['/admin/administrations/province/1/edit'],
            ['/admin/administrations/city/list'],
            ['/admin/administrations/city/create'],
            ['/admin/administrations/city/1/edit'],
            ['/admin/users/list'],
            ['/admin/users/create'],
            ['/admin/users/1/edit'],
            ['/admin/users/1/delete'],
            ['/admin/administrations/bank/list'],
            ['/admin/administrations/bank/create'],
            ['/admin/administrations/bank/1/edit'],
            ['/admin/administrations/bank/1/delete'],
        ];
    }

    #[DataProvider('provideNotFoundUrls')]
    public function testAdminPagesAreNotFound(string $url): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', $url);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public static function provideNotFoundUrls(): array
    {
        return [
            ['/admin/students/student/batch'],
            ['/admin/students/absence/batch'],
            ['/admin/students/parent/1/delete'],
            ['/admin/students/parent/1/show'],
            ['/admin/students/pre-register/create'],
            ['/admin/students/pre-register/1/edit'],
            ['/admin/students/pre-register/batch'],
            ['/admin/teachers/teacher/1/delete'],
            ['/admin/teachers/teacher/batch'],
            ['/admin/teachers/absence/batch'],
            ['/admin/teachers/absence/1/delete'],
            ['/admin/classrooms/group/1/delete'],
            ['/admin/classrooms/timetable/1/delete'],
            ['/admin/classrooms/training-center/1/delete'],
            ['/admin/billings/receipt-group/create'],
            ['/admin/billings/receipt-group/batch'],
            ['/admin/billings/receipt-group/1/edit'],
            ['/admin/billings/receipt-group/1/show'],
            ['/admin/billings/invoice/generate'],
            ['/admin/billings/invoice/batch'],
            ['/admin/purchases/provider/batch'],
            ['/admin/purchases/provider/1/show'],
            ['/admin/purchases/provider/1/delete'],
            ['/admin/purchases/spending-category/batch'],
            ['/admin/purchases/spending-category/1/show'],
            ['/admin/purchases/spending/batch'],
            ['/admin/purchases/spending/1/show'],
            ['/admin/contacts/message/create'],
            ['/admin/contacts/message/1/edit'],
            ['/admin/contacts/message/batch'],
            ['/admin/administrations/bank-creditor-sepa/batch'],
            ['/admin/administrations/bank-creditor-sepa/1/show'],
            ['/admin/administrations/province/batch'],
            ['/admin/administrations/province/1/delete'],
            ['/admin/administrations/city/1/delete'],
        ];
    }

    /**
     * @return KernelBrowser
     */
    private function getAuthenticatedClient(): KernelBrowser
    {
        return WebTestCase::createClient([], [
            'PHP_AUTH_USER' => 'cms@email.com',
            'PHP_AUTH_PW'   => 'passwd',
        ]);
    }
}
