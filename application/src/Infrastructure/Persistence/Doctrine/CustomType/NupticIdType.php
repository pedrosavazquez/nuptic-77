<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\CustomType;

use App\Domain\Nuptic\NupticId;use Doctrine\DBAL\Platforms\AbstractPlatform;use Ramsey\Uuid\Uuid;use Ramsey\Uuid\UuidInterface;final class NupticIdType
{
    public const NAME = 'nupticId';

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UuidInterface
    {
        if (null === $value) {
            return null;
        }

        /** @var Uuid $uuidEntity */
        $uuidEntity = $this->getUuidEntity();

        return $uuidEntity::fromString($value);
    }

    protected function getUuidEntity(): string
    {
        return NupticId::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        return (string) $value;
    }
}
