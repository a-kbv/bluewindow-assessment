<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use App\Service\GeolocationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brand')]
final class BrandController extends AbstractController
{
    #[Route(name: 'app_brand_index', methods: ['GET'])]
    public function index(BrandRepository $brandRepository, GeolocationService $geolocationService): Response
    {
        // Get current country from Cloudflare header
        $currentCountry = $geolocationService->getCurrentCountry();

        if ($currentCountry) {
            // Country detected - show country-specific brands
            $brands = $brandRepository->findBy(['countryCode' => $currentCountry]);
        } else {
            // No country detected - show default toplist with all casinos
            $brands = $brandRepository->findAll();
        }

        // Sort by rating (descending)
        usort($brands, function($a, $b) {
            return $b->getRating() <=> $a->getRating();
        });

        return $this->render('brand/index.html.twig', [
            'brands' => $brands,
            'currentCountry' => $currentCountry ?? 'No country detected',
        ]);
    }

    #[Route('/fake-header/{countryCode}', name: 'app_brand_fake_header', methods: ['GET'])]
    public function fakeHeader(string $countryCode, Request $request, BrandRepository $brandRepository, GeolocationService $geolocationService): Response
    {
        // Manually set the CF-IPCountry header for testing
        $request->headers->set('CF-IPCountry', $countryCode);

        // Get current country from the faked header
        $currentCountry = $geolocationService->getCurrentCountry();

        if ($currentCountry) {
            // Country detected - show country-specific brands
            $brands = $brandRepository->findBy(['countryCode' => $currentCountry]);
        } else {
            // No country detected - show default toplist with all casinos
            $brands = $brandRepository->findAll();
        }

        // Sort by rating (descending)
        usort($brands, function($a, $b) {
            return $b->getRating() <=> $a->getRating();
        });

        return $this->render('brand/index.html.twig', [
            'brands' => $brands,
            'currentCountry' => $currentCountry ?? 'Default',
        ]);
    }

    #[Route('/new', name: 'app_brand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('app_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_brand_show', methods: ['GET'])]
    public function show(Brand $brand): Response
    {
        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_brand_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brand $brand, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_brand_delete', methods: ['POST'])]
    public function delete(Request $request, Brand $brand, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_brand_index', [], Response::HTTP_SEE_OTHER);
    }
}
