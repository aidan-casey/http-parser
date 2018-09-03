This package provides various classes that help convert a PSR representation of an HTTP request into other forms useful for an API server.

For example, the ``HttpToZend`` class will convert HTTP parameters into a database query that can be passed to your model for serving up information or carrying out actions.

# Install
Coming soon! For now, feel free to clone this repository.

# HttpToZend

## Supported GET Parameters / request methods
|Parameter|Request Method|Value|
|--|--|--|
|fields|SELECT|String|
|filter|DELETE, SELECT, PATCH, PUT|JSON|
|orderby|SELECT|JSON|
|limit|SELECT|Integer|
|offset|SELECT|Integer|

### Fields
```fields=ID, Name```

### Filter
```json
filter={
    "ID": {
        "$gte" : 4
    },
    "Name": "Example"
}
```

#### Supported Filters
|Name|Key|Value|
|--|--|--|
|Greater Than|$gt|Integer|
|Greater Than or Equal To|$gte|Integer|
|In|$in|Array|
|Not In|$nin|Array|
|Like|$like|String|
|Not Like|$nlike|String|
|Less Than|$lt|Integer|
|Less Than or Equal To|$lte|Integer|
|Not|$not|String|

### Order By
```json
orderby={
    "Name": "ASC",
    "ID": "DESC"
}
```

### Limit
```limit=20```

### Offset
```offset=20```

## Example
```php
<?php

// Provides composer dependencies.
require_once 'vendor/autoload.php';

// Start our Slim app.
$App = new Slim\App();

// Setup our API route.
$App->get('/api/v1/me', function ( $Request, $Response ) use ( $App )
{
    // Setup our database connection.
    $DB     = new Zend\Db\Adapter\Adapter([
        'database'  => 'Example',
        'driver'    => 'Mysqli',
        'host'      => 'localhost',
        'password'  => '',
        'username'  => 'root'
    ]);
    $Table  = new Zend\Db\TableGateway\TableGateway( 'Table', $DB );
    
    // Parse any filters, etc.
    $Parser = new AidanCasey\HttpParser\HttpToZend();
    $Select = $Parser->ConvertHttpRequest( $Request );
    
    // Run a query against the database.
    $Result = $Table->selectWith( $Select );
});

// Run our Slim app.
$App->run();
```