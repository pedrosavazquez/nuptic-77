<?php

declare(strict_types=1);

namespace App\Application\Nuptic\Query\GetResumeData;

use Redis;

final class GetResumeDataQueryHandler
{
    private const RESUME_DATA = 'resumeData';

    public function __construct(private readonly Redis $redis)
    {
    }

    public function __invoke(GetResumeDataQuery $query): array
    {
        $todayDate = (new \DateTimeImmutable())->format('Ymd');
        $resumeData = $this->redis->get(self::RESUME_DATA .$todayDate);
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
