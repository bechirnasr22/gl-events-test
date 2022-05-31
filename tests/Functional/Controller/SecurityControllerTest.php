<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

use App\Entity\User;


/**
 * Functional test SecurityController
 * TODO : Comment methodes
 */
class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = (static::getContainer()->get('doctrine'))->getRepository(User::class);
    }

    public function testRegister()
    {
        $this->client->request('GET', '/register');
        $this->client->followRedirects();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "S'inscrire");
    }

    public function testRegisterNewUser(): void
    {
        $originalNumObjectsInUserRepository = count($this->userRepository->findAll());
        $this->client->request('GET', "/register");
        $this->client->followRedirects();

        self::assertResponseStatusCodeSame(200);
        $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => "new_" . AppFixtures::DEFAULT_USER['email'],
            'registration_form[plainPassword]' => AppFixtures::DEFAULT_USER['password'],
        ]);
        $this->assertResponseIsSuccessful();
        self::assertSame($originalNumObjectsInUserRepository + 1, count($this->userRepository->findAll()));
    }

    public function testLogin()
    {
        $this->client->request('GET', '/login');
        $this->client->followRedirects();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testLoginWithBadCredentials()
    {
        $crawler = $this->client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            '_username'    => AppFixtures::DEFAULT_USER['email'],
            '_password'    => 'fakepassword',
        ]);
        $this->client->submit($form);
        //TODO : Remove the static link 
        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirects();
    }

    public function testSuccessfullLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->client->followRedirects();

        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            '_username'    => AppFixtures::DEFAULT_USER['email'],
            '_password'    => AppFixtures::DEFAULT_USER['password'],
        ]);
        $this->client->submit($form);
        $this->client->request('GET', '/admin/newsletter');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des inscrits');
    }
}
