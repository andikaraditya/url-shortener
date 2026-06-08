<?php

namespace App\Controller;

use App\Repository\UrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/u', name: 'app_redirect')]
final class RedirectController extends AbstractController
{
    #[Route('/{name}', name: 'app_redirect')]
    public function index(UrlRepository $urlRepository, string $name): RedirectResponse
    {
        $url = $urlRepository->findOneBy(['name' => $name]);

        if (!$url) {
            throw $this->createNotFoundException('URL not found');
        }

        return $this->redirect($url->getUrl());
    }
}
