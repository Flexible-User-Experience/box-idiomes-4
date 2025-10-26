<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BackendTeacherDisabledControllerTest extends WebTestCase
{
    public function testAdminLoginPageIsSuccessful(): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', '/admin/login');
        self::assertResponseIsSuccessful();
        $client->request('GET', '/reset-password');
        self::assertResponseIsSuccessful();
    }

    #[DataProvider('provideRedirectUrls')]
    public function testAdminPagesAreRedirect(string $url): void
    {
        $client = $this->getAuthenticatedClient();
        $client->request('GET', $url);
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public static function provideRedirectUrls(): array
    {
        return [
            ['/admin/dashboard'],
            ['/admin/students/student/list'],
            ['/admin/students/student/create'],
            ['/admin/students/student/1/edit'],
            ['/admin/students/student/1/delete'],
            ['/admin/students/student/1/show'],
//            ['/admin/students/student/1/image-rights'], // avoid PDF to String -> output
//            ['/admin/students/student/1/sepa-agreement'], // avoid PDF to String -> output
            ['/admin/students/student/mailing'],
            ['/admin/students/absence/list'],
            ['/admin/students/absence/create'],
            ['/admin/students/absence/1/edit'],
            ['/admin/students/absence/1/delete'],
            ['/admin/students/evaluation/list'],
            ['/admin/students/evaluation/create'],
            ['/admin/students/evaluation/1/edit'],
            ['/admin/students/evaluation/1/delete'],
            ['/admin/students/parent/list'],
            ['/admin/students/parent/create'],
            ['/admin/students/parent/1/edit'],
            ['/admin/students/pre-register/list'],
            ['/admin/students/pre-register/1/delete'],
            ['/admin/students/pre-register/1/show'],
            ['/admin/teachers/teacher/list'],
            ['/admin/teachers/teacher/create'],
            ['/admin/teachers/teacher/1/edit'],
            ['/admin/teachers/teacher/1/detail'],
            ['/admin/teachers/absence/list'],
            ['/admin/teachers/absence/create'],
            ['/admin/teachers/absence/1/edit'],
            ['/admin/classrooms/group/list'],
            ['/admin/classrooms/group/create'],
            ['/admin/classrooms/group/1/edit'],
//            ['/admin/classrooms/group/1/get-group-emails'], // avoid PDF to String -> output
            ['/admin/classrooms/timetable/list'],
            ['/admin/classrooms/timetable/create'],
            ['/admin/classrooms/timetable/1/edit'],
            ['/admin/classrooms/timetable/2/batch-edit'],
            ['/admin/classrooms/timetable/2/batch-delete'],
            ['/admin/classrooms/timetable/2/api/get'],
            ['/admin/classrooms/timetable/2/api/1/attended-the-class'],
            ['/admin/classrooms/timetable/2/api/1/not-attended-the-class'],
            ['/admin/contacts/message/list'],
            ['/admin/contacts/message/1/delete'],
            ['/admin/contacts/message/1/show'],
            ['/admin/contacts/message/1/answer'],
            ['/admin/administrations/bank/list'],
            ['/admin/administrations/bank/create'],
            ['/admin/administrations/bank/1/edit'],
            ['/admin/administrations/bank/1/delete'],
            ['/admin/fitxers/gestor'],
            ['/admin/fitxers/gestor/handler/?conf=default'],
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
            ['/admin/students/absence/batch'],
            ['/admin/students/evaluation/batch'],
            ['/admin/students/parent/1/delete'],
            ['/admin/students/parent/1/show'],
            ['/admin/students/pre-register/create'],
            ['/admin/students/pre-register/1/edit'],
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

    private function getAuthenticatedClient(): KernelBrowser
    {
        return WebTestCase::createClient([], [
            'PHP_AUTH_USER' => 'teacher_disabled@email.com',
            'PHP_AUTH_PW'   => 'passwd',
        ]);
    }
}
