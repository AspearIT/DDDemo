<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain;

interface EventBusInterface
{
    public function publish(Event ...$events): void;
}