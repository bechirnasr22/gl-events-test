<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Newsletter;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const DEFAULT_USER = ['email' => 'admin@bechir.info', 'password' => 'p2ssw0rd'];
    public const DEFAULT_NEWSLETTER_EMAILS = ['user@gmail.com', 'user@bechir.info'];
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadUser($manager);
        $this->loadNewsletters($manager);
    }
    /**
     * Create 1 User
     *
     * @param  ObjectManager $manager
     * @return void
     */
    private function loadUser(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail(self::DEFAULT_USER['email']);
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_USER['password']));
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * Create 2 Newsletter
     *
     * @param  ObjectManager $manager
     * @return void
     */
    private function loadNewsletters(ObjectManager $manager): void
    {
        foreach (self::DEFAULT_NEWSLETTER_EMAILS as $email) {
            $newsLetter = new Newsletter();
            $newsLetter->setEmail($email);
            $manager->persist($newsLetter);
        }
        $manager->flush();
    }
}
