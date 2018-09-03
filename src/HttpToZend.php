<?php

namespace AidanCasey\HttpParser;

use AidanCasey\HttpParser\Exception\UnsupportedRequestMethod;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

/**
 * A conversion class that converts HTTP requests into Zend objects.
 *
 * @author  Aidan Casey <aidan.casey@anteris.com>
 * @since   v0.1.0
 */ 
class HttpToZend
{
    /**
     * Starts the conversion.
     *
     * @param   ServerRequestInterface  $Request    The current HTTP request.
     *
     * @return  Delete|Select|Update    The appropriate Zend DB object.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    public function ConvertHTTPRequest ( ServerRequestInterface $Request )
    {
        // Setup a DELETE method.
        if ( $Request->isDelete() )
        {
            $Query = new Delete();
            
            $Query->where(
                $this->_RequestParseFilter( $Request )
            );
        }

        // Setup a GET method.
        if ( $Request->isGet() )
        {
            $Query = new Select();

            $Query
                ->columns(
                    $this->_RequestParseFields( $Request )
                )
                ->where(
                    $this->_RequestParseFilter( $Request )
                )
                ->order(
                    $this->_RequestParseOrderBy( $Request )
                )
                ->limit(
                    $this->_RequestParseLimit( $Request )
                )
                ->offset(
                    $this->_RequestParseOffset( $Request )
                );
        }

        // Setup an update method (PATCH or PUT).
        if ( $Request->isPatch() || $Request->isPut() )
        {
            $Query = new Update();

            $Query->where(
                $this->_RequestParseFilter( $Request )
            );
        }

        // If there's no query, throw an exception.
        if (! isset($Query) )
        {
            throw new UnsupportedRequestMethod(
                'This class does not currently support the '
                . $Request->getMethod()
                . ' method.'
            );
        }

        // Otherwise return the query we've put together.
        return $Query;
    }

    /**
     * Parses fields from the request object.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @return  array   An array of fields to return.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _RequestParseFields ( ServerRequestInterface $Request )
    {
        return explode(
            ',',
            $Request->getQueryParam( 'fields', '*' )
        );
    }

    /**
     * Parses filters from the request object.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @return  Where   Zend Framework WHERE object.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _RequestParseFilter ( ServerRequestInterface $Request )
    {
        // First get the filter parameter.
        $Filter = json_decode(
            $Request->getQueryParam( 'filter', null ),
            True
        );

        // Now add any filters we've got.
        $Where  = new Where();

        if (! $Filter )
        {
            return $Where;
        }

        // Look at each filter and determine
        // it's query.
        foreach ( $Filter as $Column => $Requirement )
        {
            // In this case, it's not a complex query.
            // Just make sure the column = requirement.
            if (! is_array($Requirement) )
            {
                $Where->equalTo( $Column, $Requirement );

                continue;
            }

            // In this case, it's a complex query.
            // We have to convert the string operator
            // to a Zend DB predicate.
            foreach ( $Requirement as $Operator => $Value )
            {
                switch ( $Operator )
                {
                    case '$gt':
                        $Where->greaterThan( $Column, $Value );
                    break 1;

                    case '$gte':
                        $Where->greaterThanOrEqualTo( $Column, $Value );
                    break 1;
                    
                    case '$in':
                        $Where->in( $Column, $Value );
                    break 1;

                    case '$nin':
                        $Where->notIn( $Column, $Value );
                    break 1;

                    case '$like':
                        $Where->like( $Column, $Value );
                    break 1;

                    case '$nlike':
                        $Where->notLike( $Column, $Value );
                    break 1;

                    case '$lt':
                        $Where->lessThan( $Column, $Value );
                    break 1;

                    case '$lte':
                        $Where->lessThanOrEqualTo( $Column, $Value );
                    break 1;

                    case '$not':
                        $Where->notEqualTo( $Column, $Value );
                    break 1;

                    default:
                        $Where->equalTo( $Column, $Value );
                    break 1;

                    // End of switch.
                }
                // End of nested foreach.
            }
            // End of foreach.
        }

        return $Where;
    }

    /**
     * Parses limit statements from the request object.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @return  integer     An integer suitable for Zend DB.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _RequestParseLimit ( ServerRequestInterface $Request )
    {
        return $Request->getQueryParam( 'limit', 0 );
    }

    /**
     * Parses offest statements from the request object.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @return  integer     An integer suiteable for Zend DB.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _RequestParseOffset ( ServerRequestInterface $Request )
    {
        return $Request->getQueryParam( 'offset', 0 );
    }

    /**
     * Parses orderby statements from the request object.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @return  array   An array of order by strings suitable for Zend DB.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _RequestParseOrderBy ( ServerRequestInterface $Request )
    {
        // Decode the JSON format.
        $OrderBy        = json_decode(
            $Request->getQueryParam( 'orderby', '' ),
            True
        );
        $OrderByArray   = [];

        // If there's no orderby, stop here.
        if (! $OrderBy )
        {
            return '';
        }

        // This is so we can parse multiple orderby statements.
        foreach ( $OrderBy as $Column => $Direction )
        {
            // Standardize the spelling format and validate.
            $Direction = strtoupper($Direction);

            if ( $Direction !== 'ASC' && $Direction !== 'DESC' )
            {
                continue;
            }

            // Add it to the array!
            $OrderByArray[] = "{$Column} {$Direction}";
        }

        return $OrderByArray;
    }
}