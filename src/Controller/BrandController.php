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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/brand')]
final class BrandController extends AbstractController
{
    #[Route(name: 'app_brand_index', methods: ['GET'])]
    public function index(Request $request, BrandRepository $brandRepository, GeolocationService $geolocationService): Response
    {
        $currentCountry = $geolocationService->getCurrentCountry();

        if ($currentCountry) {
            $brands = $brandRepository->findBy(['countryCode' => $currentCountry]);
        } else {
            $brands = $brandRepository->findAll();
        }

        usort($brands, function($a, $b) {
            return $b->getRating() <=> $a->getRating();
        });

        if ($this->shouldReturnJson($request)) {
            return $this->createJsonResponse($brands, $currentCountry);
        }

        return $this->render('brand/index.html.twig', [
            'brands' => $brands,
            'currentCountry' => $currentCountry ?? 'Default',
        ]);
    }

    #[Route('/new', name: 'app_brand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        if ($request->isMethod('POST')) {
            $payload = $this->extractRequestPayload($request);
            $brand = new Brand();
            $this->hydrateBrandFromData($brand, $payload);

            $errors = $validator->validate($brand);
            if (count($errors) > 0) {
                if ($this->shouldReturnJson($request)) {
                    return new JsonResponse([
                        'status' => 'error',
                        'validation_errors' => $this->formatValidationErrors($errors)
                    ], 400);
                }

                $form = $this->createForm(BrandType::class, $brand);
                $form->submit($payload);
                return $this->render('brand/new.html.twig', [
                    'brand' => $brand,
                    'form' => $form,
                ]);
            }

            $entityManager->persist($brand);
            $entityManager->flush();

            if ($this->shouldReturnJson($request)) {
                return new JsonResponse([
                    'status' => 'created',
                    'brand' => $this->mapBrandToResponse($brand)
                ], 201);
            }

            return $this->redirectToRoute('app_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(BrandType::class);
        return $this->render('brand/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_brand_show', methods: ['GET'])]
    public function show(Request $request, Brand $brand): Response
    {
        if ($this->shouldReturnJson($request)) {
            return $this->createJsonResponse([$brand]);
        }

        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_brand_edit', methods: ['GET', 'POST', 'PATCH'])]
    public function edit(Request $request, Brand $brand, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        if ($request->isMethod('POST') || $request->isMethod('PATCH')) {
            $payload = $this->extractRequestPayload($request);
            $this->hydrateBrandFromData($brand, $payload);

            $errors = $validator->validate($brand);
            if (count($errors) > 0) {
                if ($this->shouldReturnJson($request)) {
                    return new JsonResponse([
                        'status' => 'error',
                        'validation_errors' => $this->formatValidationErrors($errors)
                    ], 400);
                }

                $form = $this->createForm(BrandType::class, $brand);
                $form->submit($payload);
                return $this->render('brand/edit.html.twig', [
                    'brand' => $brand,
                    'form' => $form,
                ]);
            }

            $entityManager->flush();

            if ($this->shouldReturnJson($request)) {
                return new JsonResponse([
                    'status' => 'updated',
                    'brand' => $this->mapBrandToResponse($brand)
                ]);
            }

            return $this->redirectToRoute('app_brand_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(BrandType::class, $brand);
        return $this->render('brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_brand_delete', methods: ['DELETE'])]
    public function delete(Request $request, Brand $brand, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($brand);
        $entityManager->flush();

        return new JsonResponse(['status' => 'deleted', 'message' => 'Brand removed successfully']);
    }

    #[Route('/fake-header/{countryCode}', name: 'app_brand_fake_header', methods: ['GET'])]
    public function fakeHeader(string $countryCode, Request $request, BrandRepository $brandRepository, GeolocationService $geolocationService): Response
    {
        $request->headers->set('CF-IPCountry', $countryCode);
        $currentCountry = $geolocationService->getCurrentCountry();

        if ($currentCountry) {
            $brands = $brandRepository->findBy(['countryCode' => $currentCountry]);
        } else {
            $brands = $brandRepository->findAll();
        }

        usort($brands, function($a, $b) {
            return $b->getRating() <=> $a->getRating();
        });

        if ($this->shouldReturnJson($request)) {
            return $this->createJsonResponse($brands, $currentCountry);
        }

        return $this->render('brand/index.html.twig', [
            'brands' => $brands,
            'currentCountry' => $currentCountry ?? 'Default',
        ]);
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->headers->get('Accept') === 'application/json'
            || $request->getRequestFormat() === 'json'
            || $request->headers->get('Content-Type') === 'application/json';
    }

    private function extractRequestPayload(Request $request): array
    {
        if ($request->headers->get('Content-Type') === 'application/json') {
            return json_decode($request->getContent(), true) ?? [];
        }

        $data = $request->request->all();

        // Handle nested form data (e.g., brand[name], brand[rating])
        if (isset($data['brand']) && is_array($data['brand'])) {
            return $data['brand'];
        }

        return $data;
    }

    private function hydrateBrandFromData(Brand $brand, array $data): void
    {
        if (isset($data['name'])) $brand->setName($data['name']);
        if (isset($data['image'])) $brand->setImage($data['image']);
        if (isset($data['rating'])) $brand->setRating((int) $data['rating']);
        if (isset($data['countryCode'])) $brand->setCountryCode(strtoupper($data['countryCode']));
    }

    private function mapBrandToResponse(Brand $brand): array
    {
        return [
            'id' => $brand->getId(),
            'name' => $brand->getName(),
            'image' => $brand->getImage(),
            'rating' => $brand->getRating(),
            'countryCode' => $brand->getCountryCode(),
        ];
    }

    private function createJsonResponse(array $brands, ?string $currentCountry = null): JsonResponse
    {
        $brandData = array_map([$this, 'mapBrandToResponse'], $brands);

        return new JsonResponse([
            'status' => 'ok',
            'brands' => $brandData,
            'meta' => [
                'country' => $currentCountry,
                'total_count' => count($brandData)
            ]
        ]);
    }

    private function formatValidationErrors($errors): array
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }
        return $errorMessages;
    }
}
