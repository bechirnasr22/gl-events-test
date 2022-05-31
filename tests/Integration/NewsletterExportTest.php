<?php


namespace App\Tests\Integration;

use App\Services\NewsletterExport;
use App\DataFixtures\AppFixtures;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use App\Entity\NewsLetter;
use App\Repository\NewsLetterRepository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Service NewsletterExport  
 */
class NewsletterExportTest extends KernelTestCase
{
    private NewsletterExport $newsletterExport;
    private NewsLetterRepository $newsletterRepository;
    private Serializer $serializer;

    protected function setUp(): void
    {
        $this->newsletterExport = self::getContainer()->get(NewsletterExport::class);
        $this->newsletterRepository = (static::getContainer()->get('doctrine'))->getRepository(NewsLetter::class);
        $this->serializer = new Serializer(
            [new GetSetMethodNormalizer(), new ArrayDenormalizer()],
            [new CsvEncoder()]
        );
    }

    /**
     * Ensures that the getNewslettersAsCSV method gives the data correctly.
     */
    public function testGetDataAsCSV()
    {
        $data = $this->newsletterExport->getNewslettersAsCSV();
        self::assertNotNull($data);
        self::assertStringContainsString(AppFixtures::DEFAULT_NEWSLETTER_EMAILS[0], $data);
        $data_as_array = $this->serializer->deserialize($data, 'App\Entity\NewsLetter[]', 'csv');
        self::assertEquals(count($this->newsletterRepository->findAll()), count($data_as_array));
        self::assertEquals($data_as_array[0]->getEmail(), AppFixtures::DEFAULT_NEWSLETTER_EMAILS[0]);
    }
}
