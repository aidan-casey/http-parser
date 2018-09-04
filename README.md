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
This example uses Slim Framework and converts a GET request into a database select query.

```php
<?php

// Provides composer dependencies.
require_once 'vendor/autoload.php';

// Start our Slim app.
$App = new Slim\App([
    'settings'  => [
        'displayErrorDetails' => true
    ]
]);

// Add a database connection to our application container.
$Container = $App->getContainer();

$Container['DB'] = function ( $Container )
{
    // Setup our query adapter.
    $Adapter = new Zend\Db\Adapter\Adapter([
        'database'  => 'Example',
        'driver'    => 'Mysqli',
        'host'      => 'localhost',
        'password'  => '',
        'username'  => 'ExampleUser'
    ]);

    // Now setup our sql object.
    return new Zend\Db\Sql\Sql( $Adapter );
};

// Setup our API route.
$App->get('/', function ( $Request, $Response ) use ( $Container )
{
    // Parse any filters, etc.
    $Parser = new AidanCasey\HttpParser\HttpToZend();
    $Select = $Parser->ConvertHttpRequest( $Request );
    
    // Prepare a query for the database.
    $Statement  = $Container['DB']->prepareStatementForSqlObject(
        $Select->from('ExampleTable')
    );

    // Now execute that query.
    $Result     = $Statement->execute();
});

// Run our Slim app.
$App->run();
```
