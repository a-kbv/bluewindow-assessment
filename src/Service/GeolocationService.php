<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GeolocationService
{
    private RequestStack $requestStack;
    private LoggerInterface $logger;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    public function getCurrentCountry(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            $this->logger->info('No request available, returning null for country');
            return null; // No request, show default toplist
        }

        $country = $request->headers->get("CF-IPCountry");

        // Debug logging
        $this->logger->info('Geolocation debug', [
            'CF-IPCountry' => $country,
            'all_headers' => $request->headers->all(),
            'user_agent' => $request->headers->get('User-Agent'),
            'remote_addr' => $request->getClientIp()
        ]);

        if ($country && strlen($country) === 2 && ctype_alpha($country)) {
            $this->logger->info('Valid country detected', ['country' => $country]);
            return strtoupper($country);
        }

        $this->logger->info('No valid country detected, returning null');
        return null; // No valid country detected, show default toplist
    }
}
