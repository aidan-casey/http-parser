<?php

namespace AidanCasey\HttpParser\Middleware;

use AidanCasey\HttpParser\HttpToZend;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Middleware for Slim Framework.
 * Converts HTTP queries to Zend SQL queries.
 *
 * @author  Aidan Casey <aidan.casey@anteris.com>
 * @since   v1.2.0
 */
class Slim
{
    /**
     * Adds a "Query" attribute to the request object.
     * Converts an HTTP query to a Zend SQL query.
     *
     * @param   ServerRequestInterface  $Request    The current HTTP request.
     * @param   ResponseInterface       $Response   The HTTP response.
     * @param   callable                $Next       The next middleware.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v1.2.0
     */ 
    public function __invoke (
        ServerRequestInterface $Request,
        ResponseInterface $Response,
        callable $Next
    )
    {
        // Convert the passed HTTP request.
        $Parser     = new HttpToZend();
        $Request    = $Request->withAttribute(
            'Query',
            $Parser->ConvertHttpRequest( $Request )
        );

        // Call the next middleware.
        return $Next( $Request, $Response );
    }
}