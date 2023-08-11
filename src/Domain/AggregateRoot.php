<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain;

abstract class AggregateRoot
{
    private array $newEvents = [];

    protected function event(Event $event): void
    {
        $this->newEvents[] = $event;
    }

    /**
     * @return Event[]
     */
    public function popNewEvents(): array
    {
        $events = $this->newEvents;
        $this->newEvents = [];
        return $events;
    }
}