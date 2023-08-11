<?php
declare(strict_types=1);

namespace Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class UnitTestCase extends MockeryTestCase
{
    protected function assertEvents(array $expectedEventClassNames, array $events): void
    {
        foreach ($expectedEventClassNames as $expectedEventClassName => $assertion) {
            if (is_numeric($expectedEventClassName)) {
                $expectedEventClassName = $assertion;
                $assertion = fn () => true;
            }
            $nextEvent = array_shift($events);
            if ($nextEvent === null) {
                $this->fail('Event ' . $expectedEventClassName . ' was not dispatched');
            }
            $this->assertInstanceOf($expectedEventClassName, $nextEvent);
            call_user_func_array($assertion, [$nextEvent]);
        }
        foreach ($events as $event) {
            $this->fail('Unexpected event ' . get_class($event) . ' was dispatched');
        }
    }
}