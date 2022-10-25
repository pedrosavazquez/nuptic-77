<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Query\GetResumeData;

use App\Domain\Shared\Cache\CacheRepository;

final class GetResumeDataQueryHandler
{
    private const RESUME_DATA = 'resumeData';

    public function __construct(private readonly CacheRepository $cacheRepository)
    {
    }

    public function __invoke(GetResumeDataQuery $query): array
    {
        $todayDate = (new \DateTimeImmutable())->format('Ymd');
        $resumeData = $this->cacheRepository->get(self::RESUME_DATA .$todayDate);
        return $this->getData($resumeData);
    }

    public function getData($resumeData): mixed
    {
        $route = $resumeData['Route'];
        unset($resumeData['Route']);
        arsort($resumeData);
        return ['Route' => $route, 'Direction' => array_key_first($resumeData)];
    }
}
