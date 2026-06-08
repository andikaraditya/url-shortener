<?php

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/url')]
final class URLController extends AbstractController
{
    #[Route('', name: 'app_url_index', methods: ['GET'])]
    public function index(UrlRepository $urlRepository): JsonResponse
    {
        $urls = $urlRepository->findAll();

        return $this->json([
            'results' => $urls,
            'count' => $urlRepository->count([]),
        ]);
    }

    #[Route('', name: 'app_url_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $url = new Url();
        $url->setName($data['name'] ?? null);
        $url->setUrl($data['url'] ?? null);
        $url->setCreatedAt(new \DateTime());
        $url->setUpdatedAt(new \DateTime());

        $entityManager->persist($url);
        $entityManager->flush();

        return $this->json($url, 201);
    }

    #[Route('/{id}', name: 'app_url_show', methods: ['GET'])]
    public function show(Url $url): JsonResponse
    {
        return $this->json($url);
    }

    #[Route('/{id}', name: 'app_url_update', methods: ['PUT', 'PATCH'])]
    public function update(
        Request $request,
        Url $url,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (isset($data['url'])) {
            $url->setUrl($data['url']);
        }

        $entityManager->flush();

        return $this->json($url);
    }

    #[Route('/{id}', name: 'app_url_delete', methods: ['DELETE'])]
    public function delete(
        Url $url,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($url);
        $entityManager->flush();

        return $this->json([
            'message' => 'URL deleted successfully',
        ]);
    }
}
