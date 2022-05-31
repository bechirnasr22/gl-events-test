<?php

namespace App\Services;


use App\Services\NewsletterExportInterface;
use App\Repository\NewsletterRepository;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class NewsletterExport implements NewsletterExportInterface
{
    private $newsletterRepository;

    public function __construct(NewsletterRepository $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * Fonction pour sérialiser la liste des inscrits en CSV
     *
     * @return string
     */
    public function getNewslettersAsCSV(): string
    {
        $data = $this->newsletterRepository->findAll();
        $encoders = new CsvEncoder();
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, [$encoders]);
        return $serializer->serialize($data, 'csv');
    }
}
