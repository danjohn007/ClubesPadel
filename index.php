<?php
/**
 * ClubesPadel - Main Entry Point
 * Sistema de Administración de Clubes de Padel
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Core/Controller.php';

// Initialize router
$router = new Core\Router();

// Start routing
$router->dispatch();
