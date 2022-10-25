<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomType;

use App\Domain\Shared\Entity\IntVO;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

abstract class AbstractIntType extends IntegerType
{
    public function getName(): string
    {
        return $this->getNameType();
    }

    abstract protected function getNameType(): string;


    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        /** @var IntVO $entityClass */
        $entityClass = $this->getEntityClass();
        return $entityClass::fromInt((int)$value);
    }

    abstract protected function getEntityClass(): string;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return $value->toInt();
    }
}
