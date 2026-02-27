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
    $routes->post('auth/login', 'AuthController::login');
    $routes->post('auth/logout', 'AuthController::logout', ['filter' => 'jwt']);
    $routes->get('auth/me', 'AuthController::me', ['filter' => 'jwt']);
    $routes->post('auth/refresh', 'AuthController::refresh', ['filter' => 'jwt']);
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
    $routes->post('/story/(:num)/submit-review', 'StoryController::submitForReview/$1');

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

// Admin Logout (outside group, no auth filter needed after session destroy)
$routes->post('admin/logout', 'AuthController::logout');

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Story Management
    $routes->get('stories', 'Admin\StoryManagementController::index');
    $routes->get('stories/detail/(:num)', 'Admin\StoryManagementController::detail/$1');
    $routes->post('stories/update/(:num)', 'Admin\StoryManagementController::update/$1');
    $routes->post('stories/approve/(:num)', 'Admin\StoryManagementController::approve/$1');
    $routes->get('stories/approve/(:num)', 'Admin\StoryManagementController::approve/$1');
    $routes->get('stories/archive/(:num)', 'Admin\StoryManagementController::archive/$1');
    $routes->post('stories/archive/(:num)', 'Admin\StoryManagementController::archive/$1');
    $routes->post('stories/toggle-featured/(:num)', 'Admin\StoryManagementController::toggleFeatured/$1');
    $routes->delete('stories/delete/(:num)', 'Admin\StoryManagementController::delete/$1');
    $routes->post('stories/delete/(:num)', 'Admin\StoryManagementController::delete/$1');

    // Chapter Management
    $routes->get('chapters', 'Admin\ChapterManagementController::index');
    $routes->get('chapters/detail/(:num)', 'Admin\ChapterManagementController::detail/$1');
    $routes->post('chapters/update/(:num)', 'Admin\ChapterManagementController::update/$1');
    $routes->post('chapters/publish/(:num)', 'Admin\ChapterManagementController::publish/$1');
    $routes->get('chapters/publish/(:num)', 'Admin\ChapterManagementController::publish/$1');
    $routes->post('chapters/archive/(:num)', 'Admin\ChapterManagementController::archive/$1');
    $routes->get('chapters/archive/(:num)', 'Admin\ChapterManagementController::archive/$1');
    $routes->delete('chapters/delete/(:num)', 'Admin\ChapterManagementController::delete/$1');
    $routes->post('chapters/delete/(:num)', 'Admin\ChapterManagementController::delete/$1');
    // Review Management
    $routes->get('reviews', 'Admin\ReviewManagementController::index');
    $routes->get('reviews/detail/(:num)', 'Admin\ReviewManagementController::detail/$1');
    $routes->post('reviews/toggle-featured/(:num)', 'Admin\ReviewManagementController::toggleFeatured/$1');
    $routes->delete('reviews/delete/(:num)', 'Admin\ReviewManagementController::delete/$1');
    $routes->post('reviews/delete/(:num)', 'Admin\ReviewManagementController::delete/$1');

    // User Management
    $routes->get('users', 'Admin\UserManagementController::index');
    $routes->get('users/detail/(:num)', 'Admin\UserManagementController::detail/$1');
    $routes->post('users/update/(:num)', 'Admin\UserManagementController::update/$1');
    $routes->get('users/verify/(:num)', 'Admin\UserManagementController::verify/$1');
    $routes->post('users/verify/(:num)', 'Admin\UserManagementController::verify/$1');
    $routes->post('users/role/(:num)', 'Admin\UserManagementController::changeRole/$1');
    $routes->post('users/delete/(:num)', 'Admin\UserManagementController::delete/$1');

    // Report Story Management
    $routes->get('reports', 'Admin\ReportManagementController::index');
    $routes->get('reports/detail/(:num)', 'Admin\ReportManagementController::detail/$1');
    $routes->post('reports/update/(:num)', 'Admin\ReportManagementController::update/$1');
    $routes->post('reports/resolve/(:num)', 'Admin\ReportManagementController::resolve/$1');
    $routes->get('reports/resolve/(:num)', 'Admin\ReportManagementController::resolve/$1');
    $routes->post('reports/dismiss/(:num)', 'Admin\ReportManagementController::dismiss/$1');
    $routes->get('reports/dismiss/(:num)', 'Admin\ReportManagementController::dismiss/$1');
    $routes->post('reports/delete/(:num)', 'Admin\ReportManagementController::delete/$1');
    $routes->delete('reports/delete/(:num)', 'Admin\ReportManagementController::delete/$1');

    // User Library Management
    $routes->get('library', 'Admin\LibraryManagementController::index');
    $routes->get('library/detail/(:num)', 'Admin\LibraryManagementController::detail/$1');
    $routes->post('library/delete/(:num)', 'Admin\LibraryManagementController::delete/$1');
    $routes->delete('library/delete/(:num)', 'Admin\LibraryManagementController::delete/$1');

    // Comment Management
    $routes->get('comments', 'Admin\CommentManagementController::index');
    $routes->get('comments/detail/(:num)', 'Admin\CommentManagementController::detail/$1');
    $routes->post('comments/delete/(:num)', 'Admin\CommentManagementController::delete/$1');
    $routes->delete('comments/delete/(:num)', 'Admin\CommentManagementController::delete/$1');

});

$routes->get('auth/google', 'AuthController::googleRedirect');
$routes->get('auth/google-callback', 'AuthController::googleCallback');