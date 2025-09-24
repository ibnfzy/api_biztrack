<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Barang routes
$routes->get('barang', 'BarangController::index');
$routes->post('barang', 'BarangController::create');
$routes->put('barang/(:segment)', 'BarangController::update/$1');
$routes->delete('barang/(:segment)', 'BarangController::delete/$1');

// Supplier routes
$routes->get('supplier', 'SupplierController::index');
$routes->post('supplier', 'SupplierController::create');
$routes->put('supplier/(:segment)', 'SupplierController::update/$1');
$routes->delete('supplier/(:segment)', 'SupplierController::delete/$1');

// Stock routes
$routes->get('stok', 'StockController::index');
$routes->put('stok', 'StockController::update');
