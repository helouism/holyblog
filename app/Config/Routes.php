<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get("/", "Home::index");
$routes->get("posts/(:segment)", 'Home::show/$1');
$routes->get("tag/(:segment)", 'Home::byTag/$1');

service("auth")->routes($routes);

// Admin – only admin can access (use Shield filters/middleware)
$routes->group("admin", ["filter" => "group:admin"], static function ($routes) {
    $routes->get("/", "Admin\Dashboard::index", ["as" => "admin.dashboard"]);
    $routes->get("posts", "Admin\Posts::index", ["as" => "admin.posts.index"]);
    $routes->get("posts/create", "Admin\Posts::create", [
        "as" => "admin.posts.create",
    ]);
    $routes->post("posts", "Admin\Posts::store", ["as" => "admin.posts.store"]);
    $routes->get("posts/(:num)/edit", 'Admin\Posts::edit/$1', [
        "as" => "admin.posts.edit",
    ]);
    $routes->put("posts/(:num)", 'Admin\Posts::update/$1', [
        "as" => "admin.posts.update",
    ]);
    $routes->delete("posts/(:num)", 'Admin\Posts::delete/$1', [
        "as" => "admin.posts.delete",
    ]);

    $routes->post(
        "posts/(:num)/toggle-status",
        'Admin\Posts::toggleStatus/$1',
        ["as" => "admin.posts.toggleStatus"],
    );

     // FilePond upload for cover image
    $routes->post('uploads/cover', 'Admin\Uploads::cover', ['as' => 'admin.uploads.cover']);
});
