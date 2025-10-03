<?php
// Authentication Routes
$router->get('/login', 'AuthController@showLogin')->name('login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister')->name('register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout')->middleware('auth');
$router->get('/forgot-password', 'AuthController@showForgotPassword')->name('password.request');
$router->post('/forgot-password', 'AuthController@forgotPassword')->name('password.email');
$router->get('/reset-password/{token}', 'AuthController@showResetPassword')->name('password.reset');
$router->post('/reset-password', 'AuthController@resetPassword')->name('password.update');
$router->get('/verify-email/{token}', 'AuthController@verifyEmail')->name('verification.verify');