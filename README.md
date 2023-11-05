# Simple php router
It is an easy-to-use router for your application in `php`.

# Getting started
Before we start working with the router, we need to set up
our application. To do this, we need to create a file in the root of the project
`.htaccess` file in the root of the project, 
where we will write a few lines of code for the `Apache` server.
```apacheconf
RewriteEngine on

# Ignore files
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !(\.css|\.html|\.png|\.gif|\.jpeg)

# Here we redirect all calls to the server 
# to the main file of the application.
RewriteRule ^ index.php
```
The next step is to initialize the router.
To do this, write the following code.
```php
use router\Router; // Connecting the router namespace
```
Next, create a new router using `new Router()`
```php
$router = new Router();
```
The router has such methods as `get`, `post`, `put`, `patch`, `delete`.
You can use them as follows:
```php
$router->get('/', function () {
    echo 'Hello world';
});
```
The next and last step is to start the router using the `resolve` method.
```php
$url = Router::getPath();
$method = Router::getMethod();

$router->resolve($url, $method);
```
In the code above, we learned the `$method` and `$url` from the request.
It is not necessary to use the static methods `getPath()` and `getMethod()`,
but we recommend that you write these methods in your code to make it easier to
understanding of the algorithm.

Thus, we have created an application that shows `Hello world` using the `Router` class.