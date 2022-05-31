<?php


namespace App\Tests\Unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\DataFixtures\AppFixtures;


/**
 * Entity User Unit Tests
 */
class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testGetEmail(): void
    {
        $response = $this->user->setEmail(AppFixtures::DEFAULT_USER['email']);
        self::assertInstanceOf(User::class, $response);
        self::assertEquals(AppFixtures::DEFAULT_USER['email'], $this->user->getEmail());
    }

    public function testGetRoles(): void
    {
        $value = ['ROLE_USER'];

        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());
    }

    public function testGetPassword(): void
    {

        $response = $this->user->setPassword(AppFixtures::DEFAULT_USER['password']);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals(AppFixtures::DEFAULT_USER['password'], $this->user->getPassword());
    }
}
