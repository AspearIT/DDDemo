<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class EventVersionNotUniqueException extends \RuntimeException
{
    public static function forAggregateRoot(string $aggregateRootType, UuidInterface $aggregateRootId, int $version): self
    {
        return new self("Event version $version for aggregate $aggregateRootType $aggregateRootId already exists. Probably a concurrent request happend, please try again!");
    }
}