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
$routes->group('auth', function ($routes) {
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('logout', 'AuthController::logout');
});
// API Authenticatin
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('auth/register', 'AuthController::register');
    $routes->post('auth/login',    'AuthController::login');
    $routes->post('auth/logout',   'AuthController::logout',  ['filter' => 'jwt']);
    $routes->get('auth/me',        'AuthController::me',      ['filter' => 'jwt']);
    $routes->post('auth/refresh',  'AuthController::refresh', ['filter' => 'jwt']);
});

// Stories - Rute
$routes->get('/write', 'StoryController::write');
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/create-story', 'StoryController::create');
    $routes->post('/create-story', 'StoryController::save');
    $routes->get('my-stories', 'StoryController::myStories');
    $routes->get('/story/edit/(:num)', 'StoryController::edit/$1');
    $routes->post('/story/update/(:num)', 'StoryController::update/$1');
    $routes->post('/story/delete/(:num)', 'StoryController::delete/$1');
    $routes->get('/story/(:num)/chapter/create', 'ChapterController::create/$1');
    $routes->post('/story/(:num)/chapter/save', 'ChapterController::save/$1');
    $routes->get('/story/(:num)/chapter/(:num)/edit', 'ChapterController::edit/$1/$2');
    $routes->post('/story/(:num)/chapter/(:num)/update', 'ChapterController::update/$1/$2');
    $routes->post('/story/(:num)/chapter/(:num)/delete', 'ChapterController::deleteChapter/$1/$2');

    $routes->post('/story/(:num)/review', 'ReviewController::submit/$1');
    $routes->get('/my-reviews', 'ReviewController::myReviews');
    $routes->post('/review/(:num)/update', 'ReviewController::update/$1');
    $routes->post('/review/(:num)/delete', 'ReviewController::delete/$1');
    $routes->post('/review/delete/(:num)', 'ReviewController::delete/$1');
    $routes->get('/my-library', 'UserLibraryController::myLibrary');
    $routes->get('/story/(:num)/add-to-library', 'StoryController::addToLibrary/$1');
    $routes->get('/story/(:num)/remove-from-library', 'StoryController::removeFromLibrary/$1');
    $routes->post('/story/(:num)/add-to-library', 'StoryController::addToLibrary/$1');
    $routes->post('/story/(:num)/remove-from-library', 'StoryController::removeFromLibrary/$1');
    $routes->get('/chapter/(:num)', 'ChapterController::read/$1');
    $routes->get('/read-chapter/(:num)', 'ChapterController::read/$1');
    $routes->post('/chapter/(:num)/comment', 'ChapterController::addComment/$1');
});
$routes->get('/discover', 'StoryController::discover');
$routes->get('/discover/all', 'StoryController::allStories');
$routes->get('/story/(:num)', 'StoryController::detail/$1');
$routes->post('/story/(:num)/rate', 'StoryController::rate/$1');

// User/Author Profile
$routes->get('/user/(:num)', 'UserController::viewUser/$1');

// User Post Comments
$routes->post('/user-post/comment', 'UserPostCommentController::add');

// User Profile & Activity
$routes->get('/profile', 'UserController::profile');
$routes->get('/profile/(:num)', 'UserController::profile/$1');
$routes->get('/my-comments', 'UserController::comments');
$routes->get('/profile/edit', 'UserController::editProfile');
$routes->post('/profile/update', 'UserController::updateProfile');

// Report Story
$routes->get('/report-story/(:num)', 'ReportStoryController::index/$1');
$routes->post('/report-story/submit', 'ReportStoryController::submit');

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Story Management
    $routes->get('stories', 'Admin\StoryManagement::index');
    $routes->get('stories/approve/(:num)', 'Admin\StoryManagement::approve/$1');
    $routes->get('stories/archive/(:num)', 'Admin\StoryManagement::archive/$1');
    $routes->delete('stories/delete/(:num)', 'Admin\StoryManagement::delete/$1');
    $routes->post('stories/delete/(:num)', 'Admin\StoryManagement::delete/$1');

    // Chapter Management
    $routes->get('chapters', 'Admin\ChapterManagement::index');
    $routes->delete('chapters/delete/(:num)', 'Admin\ChapterManagement::delete/$1');
    $routes->post('chapters/delete/(:num)', 'Admin\ChapterManagement::delete/$1');

    // Review Management
    $routes->get('reviews', 'Admin\ReviewManagement::index');
    $routes->delete('reviews/delete/(:num)', 'Admin\ReviewManagement::delete/$1');
    $routes->post('reviews/delete/(:num)', 'Admin\ReviewManagement::delete/$1');

    // User Management
    $routes->get('users', 'Admin\UserManagement::index');
    $routes->get('users/verify/(:num)', 'Admin\UserManagement::verify/$1');
    $routes->post('users/role/(:num)', 'Admin\UserManagement::changeRole/$1');
    $routes->post('users/delete/(:num)', 'Admin\UserManagement::delete/$1');
});

$routes->get('auth/google', 'AuthController::googleRedirect');
$routes->get('auth/google-callback', 'AuthController::googleCallback');