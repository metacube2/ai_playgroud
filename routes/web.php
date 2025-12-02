<?php

use App\Controllers\HomeController;
use App\Controllers\BandController;
use App\Controllers\BookingController;
use App\Controllers\AuthController;
use App\Controllers\ProfileController;
use App\Controllers\Admin\AdminController;

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/bands', [BandController::class, 'index']);
$router->get('/bands/{slug}', [BandController::class, 'show']);

// Authentication routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

// Protected routes (require authentication)
$router->group(['middleware' => 'auth'], function($router) {
    // Profile
    $router->get('/profile', [ProfileController::class, 'show']);
    $router->post('/profile/update', [ProfileController::class, 'update']);

    // Booking routes
    $router->post('/bookings/create', [BookingController::class, 'create']);
    $router->get('/my-bookings', [BookingController::class, 'myBookings']);

    // Band management (for band users)
    $router->group(['middleware' => 'role:band'], function($router) {
        $router->get('/band/manage', [BandController::class, 'manage']);
        $router->post('/band/update', [BandController::class, 'update']);
        $router->get('/band/bookings', [BookingController::class, 'bandBookings']);
        $router->post('/band/bookings/{id}/respond', [BookingController::class, 'respond']);
    });

    // Admin routes
    $router->group(['middleware' => 'role:admin'], function($router) {
        $router->get('/admin', [AdminController::class, 'dashboard']);
        $router->get('/admin/bands', [AdminController::class, 'bands']);
        $router->post('/admin/bands/{id}/approve', [AdminController::class, 'approveBand']);
        $router->get('/admin/reviews', [AdminController::class, 'reviews']);
        $router->post('/admin/reviews/{id}/moderate', [AdminController::class, 'moderateReview']);
    });
});
