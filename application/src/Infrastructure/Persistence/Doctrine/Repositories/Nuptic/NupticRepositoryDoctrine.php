<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repositories\Nuptic;

use App\Domain\Nuptic\Nuptic;
use App\Domain\Nuptic\NupticWriteRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class NupticRepositoryDoctrine extends ServiceEntityRepository implements NupticWriteRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nuptic::class);
    }

    public function save(Nuptic $nuptic) : void
    {
        $this->_em->persist($nuptic);
        $this->_em->flush();
    }
}
