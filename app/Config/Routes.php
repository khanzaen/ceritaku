<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage
$routes->get('/', 'Home::index');

// File serving from writable folder
$routes->get('files/(:segment)/(:any)', 'FileController::serve/$1/$2');

// Authentication (Shield)
$routes->group('auth', function($routes) {
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('logout', 'AuthController::logout');
});

// Stories
$routes->get('/discover', 'StoryController::discover');
$routes->get('/story/(:num)', 'StoryController::detail/$1');
$routes->post('/story/(:num)/rate', 'StoryController::rate/$1');
$routes->get('/story/(:num)/add-to-library', 'StoryController::addToLibrary/$1');
$routes->get('/story/(:num)/remove-from-library', 'StoryController::removeFromLibrary/$1');

// Chapters
$routes->get('/chapter/(:num)', 'ChapterController::read/$1');
$routes->post('/chapter/(:num)/comment', 'ChapterController::addComment/$1');

// User Profile & Activity
$routes->get('/profile', 'UserController::profile');
$routes->get('/profile/(:num)', 'UserController::profile/$1');
$routes->get('/library', 'UserController::library');
$routes->get('/my-reviews', 'UserController::reviews');
$routes->get('/my-comments', 'UserController::comments');
$routes->get('/profile/edit', 'UserController::editProfile');
$routes->post('/profile/update', 'UserController::updateProfile');

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    
    // Story Management
    $routes->get('stories', 'Admin\StoryManagement::index');
    $routes->get('stories/approve/(:num)', 'Admin\StoryManagement::approve/$1');
    $routes->get('stories/archive/(:num)', 'Admin\StoryManagement::archive/$1');
    $routes->post('stories/delete/(:num)', 'Admin\StoryManagement::delete/$1');
    
    // User Management
    $routes->get('users', 'Admin\UserManagement::index');
    $routes->get('users/verify/(:num)', 'Admin\UserManagement::verify/$1');
    $routes->post('users/role/(:num)', 'Admin\UserManagement::changeRole/$1');
    $routes->post('users/delete/(:num)', 'Admin\UserManagement::delete/$1');
});
