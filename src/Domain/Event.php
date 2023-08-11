<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain;

use Ramsey\Uuid\UuidInterface;

readonly abstract class Event
{
    public function __construct(
        private string $aggregateType,
        private UuidInterface $aggregateId,
        private int $aggregateVersion,
    ) {}

    public function getAggregateType(): string
    {
        return $this->aggregateType;
    }

    public function getAggregateId(): UuidInterface
    {
        return $this->aggregateId;
    }

    public function getAggregateVersion(): int
    {
        return $this->aggregateVersion;
    }
}