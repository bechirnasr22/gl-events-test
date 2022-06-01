<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\AppFixtures;
use App\Entity\Newsletter;
use App\Repository\NewsletterRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Functional test NewsletterController
 */
class NewsletterControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private NewsletterRepository $newsletterRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->newsletterRepository = (static::getContainer()->get('doctrine'))->getRepository(Newsletter::class);
    }

    /**
     * Test Home Page
     */
    public function testIndex(): void
    {
        $this->client->request('GET', "/");
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Accueil');
    }

    /**
     * Test create new Newsletter
     */
    public function testNew(): void
    {
        $originalNumObjectsInnewsletterRepository = count($this->newsletterRepository->findAll());
        $this->client->request('GET', "/newsletter");
        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm(
            "S'abonner",
            [
                'newsletter[email]' => AppFixtures::DEFAULT_NEWSLETTER_EMAILS[0],
            ]
        );
        self::assertResponseRedirects('/');
        self::assertSame($originalNumObjectsInnewsletterRepository + 1, count($this->newsletterRepository->findAll()));
    }

    /**
     * Test page /admin/newsletter without login
     * The URL /admin/newsletter shouldn't be
     * publicly accessible. This tests ensures that whenever a user tries to
     * access to this page, a redirection to the login form is performed.
     */
    public function testAdminIndexWithoutLogin()
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/admin/newsletter');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    /**
     * Test page /admin/newsletter with login
     */
    public function testAdminIndexWithLogin()
    {
        // retrieve the test user
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail(AppFixtures::DEFAULT_USER['email']);
        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
        // test /admin/newsletter page
        $this->client->request('GET', '/admin/newsletter');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des inscrits');
    }


    /**
     * Test page /admin/newsletter/export without login
     * The URL /admin/newsletter/export shouldn't be
     * publicly accessible. This tests ensures that whenever a user tries to
     * access to this page, a redirection to the login form is performed.
     */
    public function testExportWithoutLogin()
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/admin/newsletter/export');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    /**
     * Test page /admin/newsletter/export with login
     */
    public function testExportWithLogin()
    {
        // retrieve the test user
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail(AppFixtures::DEFAULT_USER['email']);
        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
        // test /admin/newsletter/export page
        $this->client->request('GET', '/admin/newsletter/export');
        $this->assertResponseIsSuccessful();
        self::assertSame($this->client->getResponse()->headers->get("Content-Type"), "application/vnd.ms-excel");
        self::assertSame($this->client->getResponse()->headers->get("Content-disposition"), "attachment; filename=Export.csv");
    }

    /**
     * TODO : To test that our application sends emails correctly
     */
    // public function testSendEmail()
    // {
    //     $this->client->enableProfiler();
    //     $this->client->request('GET', "/newsletter");
    //     self::assertResponseStatusCodeSame(200);
    //     $this->client->submitForm("S'abonner", [
    //         'newsletter[email]' => AppFixtures::DEFAULT_NEWSLETTER_EMAILS[0],
    //     ]);
    //     $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
    //     $this->assertEquals(1, $mailCollector->getMessageCount());
    //     /** @var \Swift_Message[] $messages */
    //     $messages = $mailCollector->getMessages();
    //     $this->assertEquals($messages[0]->getTo(), ['contact@bechir.info' => null]);
    // }
}
