<?php

namespace App\Controller;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsletterRepository;
use App\Form\NewsletterType;
use App\Services\NewsletterExport;
use App\Message\MailNotification;

/**
 * Controller used to manage Newsletters
 */
class NewsletterController extends AbstractController
{
    /**
     * These routes are used to create a new Newsletter
     * 
     * @Route("/", name="home", methods={"GET", "POST"})
     * @Route("/newsletter", name="newsletter", methods={"GET", "POST"})
     */
    public function index(Request $request, NewsletterRepository $newsLetterRepository): Response
    {
        $newsLetter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsLetter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsLetterRepository->add($newsLetter, true);
            //Send the mail in async with messenger/rabbitmq 
            $this->dispatchMessage(new MailNotification('Merci pour votre inscription', $newsLetter->getEmail()));
            $this->addFlash("success", "Merci pour votre inscription !");
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm(
            'newsletter/new.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    /**
     * Route to display the list of registrants
     * 
     * @Route("/admin/newsletter", name="newsletter_index", methods={"GET"})
     */
    public function admin_index(NewsletterRepository $newsletterRepository): Response
    {
        return $this->render(
            'newsletter/index.html.twig',
            [
                'newsletters' => $newsletterRepository->findByGmailEmail(),
            ]
        );
    }

    /**
     * Route for exporting the list of registrants to a CSV file
     * 
     * @Route("/admin/newsletter/export", name="newsletter_export", methods={"GET"})
     */
    public function export(NewsletterExport $newsletterExport): Response
    {
        //We retrieve the list of registrants in csv format
        $newsletters_as_csv = $newsletterExport->getNewslettersAsCSV();
        //We generate the CSV file
        return new Response(
            $newsletters_as_csv,
            200,
            [
                'Content-Type' => 'application/vnd.ms-excel',
                "Content-disposition" => "attachment; filename=Export.csv"
            ]
        );
    }
}
