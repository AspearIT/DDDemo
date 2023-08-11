<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain;

use AspearIT\DDDemo\Domain\Exception\EventVersionNotUniqueException;
use Ramsey\Uuid\UuidInterface;

/**
 * The implementation of this interface can be anything you want to store events
 */
interface EventStorageInterface
{
    /**
     * @throws EventVersionNotUniqueException the storage should be able to check if the event version is unique
     */
    public function saveEvents(Event ...$events): void;

    public function getEvents(string $aggregateType, UuidInterface $aggregateRootId): array;
}