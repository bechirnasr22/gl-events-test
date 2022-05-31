<?php


namespace App\Tests\Functional\Repository;

use App\Entity\NewsLetter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Functional test NewsLetterRepositoryTest
 */
class NewsLetterRepositoryTest extends KernelTestCase

{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Ensures that the findByGmailEmail method gives only Gmail emails.
     */
    public function testfindByGmailEmail()
    {
        $newsLetters = $this->entityManager->getRepository(NewsLetter::class)->findByGmailEmail();
        foreach ($newsLetters as $n) {
            $this->assertTrue(str_ends_with($n->getEmail(), '@gmail.com'));
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
