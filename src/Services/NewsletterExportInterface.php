<?php

namespace App\Services;

interface NewsletterExportInterface
{
    public function getNewslettersAsCSV(): string;
}
