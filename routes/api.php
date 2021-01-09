<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

 // Login And User

Route::post('/login', 'AuthController@login')->name('login');
Route::post('/logout', 'AuthController@logout');
Route::get('/users/logged_in_user', 'UserController@loggedInUser');
Route::get('/users/select_list', 'UserController@user_select_list');

//company
Route::get('/company', 'CompanyController@index');
Route::post('/company', 'CompanyController@store');
Route::put('/company/{id}', 'CompanyController@update');
Route::get('/company/select_list', 'CompanyController@select_list');
Route::get('/company/search', 'CompanyController@search');
Route::get('/company/paginate_search', 'CompanyController@paginate_search');

//supplier
Route::get('/suppliers', 'SupplierController@index');
Route::post('/suppliers', 'SupplierController@store');
Route::put('/suppliers/{id}', 'SupplierController@update');
Route::get('/suppliers/select_list', 'SupplierController@select_list');
Route::get('/suppliers/search', 'SupplierController@search');
Route::get('/suppliers/paginate_search', 'SupplierController@paginate_search');

//supplier_payment

Route::get('/supplier_payment', 'SupplierPaymentController@index');
Route::post('/supplier_payment', 'SupplierPaymentController@store');
Route::get('/supplier_payment/paginate_search', 'SupplierPaymentController@paginate_search');

//inventory
Route::get('/inventories/search_by_name', 'InventoryController@search_name');
Route::post('/inventories', 'InventoryController@add_inventory');
Route::post('/purchase', 'InventoryController@store');
Route::get('/inventories', 'InventoryController@index');
Route::get('/inventories/search', 'InventoryController@search');
Route::get('/inventories/select_list', 'InventoryController@select_list');
Route::get('/inventories/{id}', 'InventoryController@show');
Route::post('/sale_price_history', 'InventoryController@update_price');
Route::put('/update_inventory/{id}', 'InventoryController@update_inventory');
//Sale
Route::post('/sales', 'SaleController@create');
Route::get('/sales/get_by_receipt_code', 'SaleController@get_by_receipt_code');

//Return Sale
Route::post('/returned_items', 'SaleController@return_sale');

//Stock History
Route::get('/stock_histories', 'StockHistoryController@index');
Route::get('/stock_quantity_histories/get_by_stock_history', 'StockHistoryController@get_stock_quantity');

//Expiry

Route::get('/near_expiry_items', 'ReturnExpiryController@check_expiry');
Route::post('/expiry_returns', 'ReturnExpiryController@return_expiry');
Route::post('/disposed_inventory', 'ReturnExpiryController@dispose_item');

//Reporting
Route::get('/sale_profit', 'ReportingController@sale_profit');
Route::get('/sales/get_by_date', 'ReportingController@sale_get_by_date');
Route::get('/sales/sale_report', 'ReportingController@sale_report');
Route::get('/sales/user_sale_report', 'ReportingController@user_sale_report');
Route::get('/sales/product_sale_report', 'ReportingController@product_sale_report');
Route::get('/sales/user_product_sale', 'ReportingController@user_product_sale');
Route::get('/sales/invoice_sale', 'ReportingController@invoice_sale');
Route::get('/sales/return_report', 'ReportingController@return_report');
Route::get('/sales/return_by_user', 'ReportingController@return_by_user');
Route::get('/stock_by_supplier', 'ReportingController@supplier_stock_report');
Route::get('/payment_report', 'ReportingController@payment_report');
Route::get('/supplier_payment_report', 'ReportingController@supplier_payment_report');
Route::get('/product_sale_profit', 'ReportingController@product_sale_profit');
Route::get('/company_stock', 'ReportingController@company_stock');
Route::get('/purchase_by_user', 'ReportingController@purchase_by_user');
Route::get('/expiry_return_report', 'ReportingController@expiry_return');
Route::get('/product_expiry_return', 'ReportingController@product_expiry_return');
Route::get('/user_expiry_return', 'ReportingController@user_expiry_return');
Route::get('/supplier_expiry_return', 'ReportingController@supplier_expiry_return');
Route::get('/dispose_items_report', 'ReportingController@dispose_items_report');
Route::get('/last_six_month', 'ReportingController@last_six_month');
Route::get('/company_sale', 'ReportingController@company_sale');
Route::get('/dashboard_report', 'ReportingController@get_dashboard_data');
Route::get('/product_purchase', 'ReportingController@product_purchase');
Route::get('/total_stock_amount', 'ReportingController@total_stock_amount');
// Notification

Route::get('/check_index', 'MessageController@index');
Route::get('/check_message', 'MessageController@send');

// Configuration

Route::get('/check_qty', 'ConfigController@check_product_qty');
Route::get('/check_qty_by_company', 'ConfigController@check_minimum_by_company');

 //Patient Charges

 Route::post('/patient_charges', 'PatientChargesController@create_charges');

 // Employee

Route::get('/employees', 'EmployeeController@index');
Route::post('/employee', 'EmployeeController@store');
Route::put('/employee/{id}', 'EmployeeController@update');
Route::get('/employee_select_list', 'EmployeeController@employee_select_list');

// Daily Wages

Route::get('/daily_wages', 'DailyWagesController@index');
Route::post('/daily_wages', 'DailyWagesController@store');
Route::put('/daily_wages/{id}', 'DailyWagesController@update');

// Daily Expenses

Route::get('/daily_expenses', 'DailyExpensesController@index');
Route::post('/daily_expense', 'DailyExpensesController@store');
Route::put('/daily_expense/{id}', 'DailyExpensesController@update');



