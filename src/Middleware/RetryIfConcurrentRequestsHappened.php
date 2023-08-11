<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Middleware;

use AspearIT\DDDemo\Domain\Exception\EventVersionNotUniqueException;

/**
 * Just an example how you can deal with concurrent requests that affects the same domain object.
 */
class RetryIfConcurrentRequestsHappened
{
    public function request($request, $next)
    {
        $response = $next($request);

        if ($response->exception !== null && $response->exception instanceof EventVersionNotUniqueException) {
            // Probably a concurrent request happend that changed exactly the same domain object, so we retry the request again with the new version of the domain object
            return $next($request);
        }
        return $response;
    }
}