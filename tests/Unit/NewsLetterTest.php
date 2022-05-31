<?php


namespace App\Tests\Unit;

use App\Entity\Newsletter;
use PHPUnit\Framework\TestCase;
use App\DataFixtures\AppFixtures;

/**
 * Entity Newsletter Unit Tests
 */
class NewsletterTest extends TestCase
{
    private Newsletter $newsLetter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newsLetter = new Newsletter();
    }

    public function testGetEmail(): void
    {
        $response = $this->newsLetter->setEmail(AppFixtures::DEFAULT_NEWSLETTER_EMAILS[0]);
        self::assertInstanceOf(Newsletter::class, $response);
        self::assertEquals(AppFixtures::DEFAULT_NEWSLETTER_EMAILS[0], $this->newsLetter->getEmail());
    }
}
