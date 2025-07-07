<?php
// app/routes.php

// Publikus útvonalak
$router->get('', ['controller' => 'Auth', 'action' => 'login']);
$router->get('login', ['controller' => 'Auth', 'action' => 'login']);
$router->post('login', ['controller' => 'Auth', 'action' => 'doLogin']);
$router->get('logout', ['controller' => 'Auth', 'action' => 'logout']);

// Védett útvonalak (bejelentkezés szükséges)
$router->get('dashboard', ['controller' => 'Dashboard', 'action' => 'index', 'middleware' => 'AuthMiddleware']);

// Munkalapok
$router->get('worksheets', ['controller' => 'Worksheet', 'action' => 'index', 'middleware' => 'AuthMiddleware']);
$router->get('worksheets/create', ['controller' => 'Worksheet', 'action' => 'create', 'middleware' => 'AuthMiddleware']);
$router->post('worksheets/store', ['controller' => 'Worksheet', 'action' => 'store', 'middleware' => 'AuthMiddleware']);
$router->get('worksheets/{id:\d+}', ['controller' => 'Worksheet', 'action' => 'show', 'middleware' => 'AuthMiddleware']);
$router->get('worksheets/{id:\d+}/edit', ['controller' => 'Worksheet', 'action' => 'edit', 'middleware' => 'AuthMiddleware']);
$router->post('worksheets/{id:\d+}/update', ['controller' => 'Worksheet', 'action' => 'update', 'middleware' => 'AuthMiddleware']);
$router->post('worksheets/{id:\d+}/delete', ['controller' => 'Worksheet', 'action' => 'delete', 'middleware' => 'AuthMiddleware']);
$router->get('worksheets/{id:\d+}/print', ['controller' => 'Worksheet', 'action' => 'print', 'middleware' => 'AuthMiddleware']);
$router->post('worksheets/{id:\d+}/email', ['controller' => 'Worksheet', 'action' => 'email', 'middleware' => 'AuthMiddleware']);
$router->post('worksheets/{id:\d+}/upload', ['controller' => 'Worksheet', 'action' => 'uploadFile', 'middleware' => 'AuthMiddleware']);
$router->get('worksheets/export', ['controller' => 'Worksheet', 'action' => 'export', 'middleware' => 'AuthMiddleware']);

// Worksheet attachments - ÚJ ROUTE-OK
$router->get('worksheets/{worksheetId:\d+}/attachment/{attachmentId:\d+}/download', ['controller' => 'Worksheet', 'action' => 'downloadAttachment', 'middleware' => 'AuthMiddleware']);
$router->post('worksheets/{worksheetId:\d+}/attachment/{attachmentId:\d+}/delete', ['controller' => 'Worksheet', 'action' => 'deleteAttachment', 'middleware' => 'AuthMiddleware']);

$router->post('ajax/device/update', ['controller' => 'Ajax', 'action' => 'updateDevice', 'middleware' => 'AuthMiddleware']);


// Ügyfelek
$router->get('customers', ['controller' => 'Customer', 'action' => 'index', 'middleware' => 'AuthMiddleware']);
$router->get('customers/create', ['controller' => 'Customer', 'action' => 'create', 'middleware' => 'AuthMiddleware']);
$router->post('customers/store', ['controller' => 'Customer', 'action' => 'store', 'middleware' => 'AuthMiddleware']);
$router->get('customers/{id:\d+}/edit', ['controller' => 'Customer', 'action' => 'edit', 'middleware' => 'AuthMiddleware']);
$router->post('customers/{id:\d+}/update', ['controller' => 'Customer', 'action' => 'update', 'middleware' => 'AuthMiddleware']);
$router->post('customers/{id:\d+}/delete', ['controller' => 'Customer', 'action' => 'delete', 'middleware' => 'AuthMiddleware']);
$router->get('customers/search', ['controller' => 'Customer', 'action' => 'search', 'middleware' => 'AuthMiddleware']);

// Eszközök
$router->get('devices', ['controller' => 'Device', 'action' => 'index', 'middleware' => 'AuthMiddleware']);
$router->get('devices/create', ['controller' => 'Device', 'action' => 'create', 'middleware' => 'AuthMiddleware']);
$router->post('devices/store', ['controller' => 'Device', 'action' => 'store', 'middleware' => 'AuthMiddleware']);
$router->get('devices/{id:\d+}/edit', ['controller' => 'Device', 'action' => 'edit', 'middleware' => 'AuthMiddleware']);
$router->post('devices/{id:\d+}/update', ['controller' => 'Device', 'action' => 'update', 'middleware' => 'AuthMiddleware']);
$router->post('devices/{id:\d+}/delete', ['controller' => 'Device', 'action' => 'delete', 'middleware' => 'AuthMiddleware']);

// Alkatrészek/Szolgáltatások
$router->get('parts', ['controller' => 'Part', 'action' => 'index', 'middleware' => 'AuthMiddleware']);
$router->get('parts/create', ['controller' => 'Part', 'action' => 'create', 'middleware' => 'AuthMiddleware']);
$router->post('parts/store', ['controller' => 'Part', 'action' => 'store', 'middleware' => 'AuthMiddleware']);
$router->get('parts/{id:\d+}/edit', ['controller' => 'Part', 'action' => 'edit', 'middleware' => 'AuthMiddleware']);
$router->post('parts/{id:\d+}/update', ['controller' => 'Part', 'action' => 'update', 'middleware' => 'AuthMiddleware']);
$router->post('parts/{id:\d+}/delete', ['controller' => 'Part', 'action' => 'delete', 'middleware' => 'AuthMiddleware']);
$router->get('parts/search', ['controller' => 'Part', 'action' => 'search', 'middleware' => 'AuthMiddleware']);

// Kategóriák - ÚJ ROUTE-OK
$router->get('categories', ['controller' => 'Category', 'action' => 'index', 'middleware' => 'AuthMiddleware']);
$router->get('categories/create', ['controller' => 'Category', 'action' => 'create', 'middleware' => 'AuthMiddleware']);
$router->post('categories/store', ['controller' => 'Category', 'action' => 'store', 'middleware' => 'AuthMiddleware']);
$router->get('categories/{id:\d+}/edit', ['controller' => 'Category', 'action' => 'edit', 'middleware' => 'AuthMiddleware']);
$router->post('categories/{id:\d+}/update', ['controller' => 'Category', 'action' => 'update', 'middleware' => 'AuthMiddleware']);
$router->post('categories/{id:\d+}/delete', ['controller' => 'Category', 'action' => 'delete', 'middleware' => 'AuthMiddleware']);
$router->get('categories/{id:\d+}/parts', ['controller' => 'Category', 'action' => 'parts', 'middleware' => 'AuthMiddleware']);
$router->get('categories/tree', ['controller' => 'Category', 'action' => 'tree', 'middleware' => 'AuthMiddleware']);
$router->get('categories/search', ['controller' => 'Category', 'action' => 'search', 'middleware' => 'AuthMiddleware']);


// Kimutatások
$router->get('reports', ['controller' => 'Report', 'action' => 'index', 'middleware' => 'AuthMiddleware']);
$router->get('reports/revenue', ['controller' => 'Report', 'action' => 'revenue', 'middleware' => 'AuthMiddleware']);
$router->get('reports/technician', ['controller' => 'Report', 'action' => 'technician', 'middleware' => 'AuthMiddleware']);
$router->get('reports/device', ['controller' => 'Report', 'action' => 'device', 'middleware' => 'AuthMiddleware']);
$router->get('reports/customer', ['controller' => 'Report', 'action' => 'customer', 'middleware' => 'AuthMiddleware']);

// Admin útvonalak
$router->get('admin', ['controller' => 'Admin', 'action' => 'index', 'middleware' => 'AdminMiddleware']);
$router->get('admin/users', ['controller' => 'Admin', 'action' => 'users', 'middleware' => 'AdminMiddleware']);
$router->get('admin/users/create', ['controller' => 'Admin', 'action' => 'createUser', 'middleware' => 'AdminMiddleware']);
$router->post('admin/users/store', ['controller' => 'Admin', 'action' => 'storeUser', 'middleware' => 'AdminMiddleware']);
$router->get('admin/users/{id:\d+}/edit', ['controller' => 'Admin', 'action' => 'editUser', 'middleware' => 'AdminMiddleware']);
$router->post('admin/users/{id:\d+}/update', ['controller' => 'Admin', 'action' => 'updateUser', 'middleware' => 'AdminMiddleware']);
$router->post('admin/users/{id:\d+}/delete', ['controller' => 'Admin', 'action' => 'deleteUser', 'middleware' => 'AdminMiddleware']);

$router->get('admin/settings', ['controller' => 'Admin', 'action' => 'settings', 'middleware' => 'AdminMiddleware']);
$router->post('admin/settings/update', ['controller' => 'Admin', 'action' => 'updateSettings', 'middleware' => 'AdminMiddleware']);

$router->get('admin/permissions', ['controller' => 'Admin', 'action' => 'permissions', 'middleware' => 'AdminMiddleware']);
$router->post('admin/permissions/update', ['controller' => 'Admin', 'action' => 'updatePermissions', 'middleware' => 'AdminMiddleware']);

// AJAX útvonalak
$router->post('ajax/worksheet/add-item', ['controller' => 'Ajax', 'action' => 'addWorksheetItem', 'middleware' => 'AuthMiddleware']);
$router->post('ajax/worksheet/remove-item', ['controller' => 'Ajax', 'action' => 'removeWorksheetItem', 'middleware' => 'AuthMiddleware']);
$router->post('ajax/worksheet/update-status', ['controller' => 'Ajax', 'action' => 'updateWorksheetStatus', 'middleware' => 'AuthMiddleware']);
$router->get('ajax/parts/search', ['controller' => 'Ajax', 'action' => 'searchParts', 'middleware' => 'AuthMiddleware']);

// Hibakezelés
$router->get('error/403', ['controller' => 'Error', 'action' => 'forbidden']);
$router->get('error/404', ['controller' => 'Error', 'action' => 'notFound']);
$router->get('error/500', ['controller' => 'Error', 'action' => 'serverError']);