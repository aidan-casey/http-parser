<?php

namespace AidanCasey\HttpParser;

use AidanCasey\HttpParser\Exception\UnsupportedRequestMethod;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Db\Sql\AbstractPreparableSql;
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
        }

        // Setup a GET method.
        if ( $Request->isGet() )
        {
            $Query = new Select();

            $this->_QueryAddFields( $Request, $Query );
            $this->_QueryAddOrderBy( $Request, $Query );
            $this->_QueryAddLimit( $Request, $Query );
            $this->_QueryAddOffset( $Request, $Query );
        }

        // Setup an update method (PATCH or PUT).
        if ( $Request->isPatch() || $Request->isPut() )
        {
            $Query = new Update();
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
        
        // This method is acceptable for all types.
        $this->_QueryAddFilters( $Request, $Query );

        // Now return the query we've put together.
        return $Query;
    }

    /**
     * Parses fields from the request object and adds it to the query.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _QueryAddFields ( ServerRequestInterface $Request, AbstractPreparableSql &$Query )
    {
        $Query->columns(
            explode(
                ',',
                $Request->getQueryParam( 'fields', '*' )
            )
        );
    }

    /**
     * Parses filters from the request object and adds them to the query.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _QueryAddFilters ( ServerRequestInterface $Request, AbstractPreparableSql &$Query )
    {
        // First get the filter parameter.
        $Filter = json_decode(
            $Request->getQueryParam( 'filter', null ),
            True
        );

        // If there are no filters, don't waste time.
        if (! $Filter )
        {
            return;
        }

        // Now add any filters we've got.
        $Where  = new Where();

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

        $Query->where( $Where );
    }

    /**
     * Parses limits from the request object and adds them to the query.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _QueryAddLimit ( ServerRequestInterface $Request, AbstractPreparableSql &$Query )
    {
        $Limit = $Request->getQueryParam( 'limit', null );

        if (! is_null($Limit) )
        {
            $Query->limit( $Limit );
        }
    }

    /**
     * Parses offsets from the request object and adds them to the query.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _QueryAddOffset ( ServerRequestInterface $Request, AbstractPreparableSql &$Query )
    {
        $Offset = $Request->getQueryParam( 'offset', null );

        if (! is_null($Offset) )
        {
            $Query->offset( $Offset );
        }
    }

    /**
     * Parses orderbys from the request object and adds them to the query.
     *
     * @param   ServerRequestInterface  $Request The current HTTP request.
     *
     * @author  Aidan Casey <aidan.casey@anteris.com>
     * @since   v0.1.0
     */
    protected function _QueryAddOrderBy ( ServerRequestInterface $Request, AbstractPreparableSql &$Query )
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
            return;
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

        // Add it to the query!
        $Query->order( $OrderByArray );
    }
}