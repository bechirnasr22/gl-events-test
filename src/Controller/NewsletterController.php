<?php

namespace App\Controller;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsletterRepository;
use App\Form\NewsletterType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterController extends AbstractController
{
    /**
     * These routes are used to create a new Newsletter
     * 
     * @Route("/", name="home", methods={"GET", "POST"})
     * @Route("/newsletter", name="newsletter", methods={"GET", "POST"})
     */
    public function index(Request $request, NewsletterRepository $newsLetterRepository, MailerInterface $mailer): Response
    {
        $newsLetter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsLetter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO : create a new service to manage the process of creating a new newsletter
            $newsLetterRepository->add($newsLetter, true);
            //TODO : Send the mail asynchronously with messenger/rabbitmq 
            $email = (new Email())
                ->from("contact@bechir.info")
                ->to($newsLetter->getEmail())
                ->subject('Merci, vous êtes inscrit')
                ->text('Merci, vous êtes inscrit');
            $mailer->send($email);
            $this->addFlash("success", "Merci, vous êtes inscrit !");
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('newsletter/new.html.twig', [
            'form' => $form,
        ]);
    }
}
