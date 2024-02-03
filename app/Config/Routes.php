<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->addRedirect("/", "/employees");

// $routes->get("/employees", "Employee::index");
// $routes->post("/employees", "Employee::store");
// $routes->get("/employees/create", "Employee::create");
// $routes->get("/employees/(:num)/edit", "Employee::edit/$1");

$routes->resource("employees", ["websafe" => 1]);
