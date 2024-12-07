<?php
$router->get('login', 'LoginController@index');
$router->post('login', 'LoginController@login');
$router->get('logout', 'LoginController@logout');

$router->get('forgotpassword', 'LoginController@forgotpassword');
$router->post('forgotpassword', 'LoginController@forgotAction');

$router->get('resetpassword', 'LoginController@resetpassword');
$router->post('resetpassword', 'LoginController@resetpasswordAction');

$router->get('profile', 'ProfileController@index');
$router->post('profile', 'ProfileController@indexAction');
$router->post('profile/password', 'ProfileController@indexPassword');

$router->get('dashboard', 'DashboardController@index');

$router->get('info', 'SettingController@index');
$router->post('info', 'SettingController@indexAction');

$router->get('customers', 'CustomerController@index');
$router->get('customer/view', 'CustomerController@indexView');
$router->get('customer/add', 'CustomerController@indexAdd');
$router->post('customer/add', 'CustomerController@indexAction');
$router->get('customer/edit', 'CustomerController@indexEdit');
$router->post('customer/edit', 'CustomerController@indexAction');
$router->post('customer/delete', 'CustomerController@indexDelete');
$router->get('customer/search', 'CustomerController@searchCustomer');

$router->get('users', 'UserController@index');
$router->get('user/add', 'UserController@indexAdd');
$router->post('user/add', 'UserController@indexAction');
$router->get('user/edit', 'UserController@indexEdit');
$router->post('user/edit', 'UserController@indexAction');
$router->post('user/delete', 'UserController@indexDelete');

$router->get('role', 'UserController@userRole');
$router->get('role/add', 'UserController@userRoleAdd');
$router->post('role/add', 'UserController@userRoleAction');
$router->get('role/edit', 'UserController@userRoleEdit');
$router->post('role/edit', 'UserController@userRoleAction');
$router->post('role/delete', 'UserController@userRoleDelete');

$router->get('medicines', 'MedicineController@index');
$router->get('medicine/view', 'MedicineController@indexView');
$router->get('medicine/add', 'MedicineController@indexAdd');
$router->post('medicine/add', 'MedicineController@indexAction');
$router->get('medicine/edit', 'MedicineController@indexEdit');
$router->post('medicine/edit', 'MedicineController@indexAction');
$router->post('medicine/delete', 'MedicineController@indexDelete');
$router->get('getmedicine', 'MedicineController@getMedicine');

$router->get('medicine/purchase', 'MedicineController@medicinePurchaseList');
$router->get('medicine/purchase/view', 'MedicineController@medicinePurchaseView');
$router->get('medicine/purchase/add', 'MedicineController@medicinePurchaseAdd');
$router->post('medicine/purchase/add', 'MedicineController@medicinePurchaseAction');
$router->get('medicine/purchase/edit', 'MedicineController@medicinePurchaseEdit');
$router->post('medicine/purchase/edit', 'MedicineController@medicinePurchaseAction');
$router->post('medicine/purchase/delete', 'MedicineController@medicinePurchaseDelete');
$router->post('purchase/filter', 'MedicineController@purchaseFilter');

$router->get('medicine/stock', 'MedicineController@stockList');
$router->post('medicine/stock', 'MedicineController@stockUpdate');
$router->post('medicine/stock/delete', 'MedicineController@stockDelete');

$router->get('medicine/billing', 'MedicineController@medicineBilling');
$router->get('medicine/billing/view', 'MedicineController@medicineBillingView');
$router->get('medicine/billing/add', 'MedicineController@medicineBillingAdd');
$router->post('medicine/billing/add', 'MedicineController@medicineBillingAction');
$router->get('medicine/billing/edit', 'MedicineController@medicineBillingEdit');
$router->post('medicine/billing/edit', 'MedicineController@medicineBillingAction');
$router->post('medicine/billing/delete', 'MedicineController@medicineBillingDelete');
$router->post('billing/filter', 'MedicineController@billingFilter');

$router->post('getbatch', 'MedicineController@medicineBillingBatch');
$router->post('getbatchdata', 'MedicineController@medicineBillingBatchData');

$router->get('suppliers', 'SettingController@suppliers');
$router->post('supplier/add', 'SettingController@supplierAction');
$router->post('supplier/edit', 'SettingController@supplierAction');
$router->post('supplier/delete', 'SettingController@supplierDelete');

$router->get('medicine/category', 'MedicineController@mCategory');
$router->post('medicine/category/add', 'MedicineController@mCategoryAction');
$router->post('medicine/category/edit', 'MedicineController@mCategoryAction');
$router->post('medicine/category/delete', 'MedicineController@mCategoryDelete');

$router->get('reports', 'ReportController@index');

$router->get('paymentmethod', 'FinanceController@paymentMethod');
$router->post('paymentmethod/add', 'FinanceController@paymentMethodAction');
$router->post('paymentmethod/edit', 'FinanceController@paymentMethodAction');
$router->post('paymentmethod/delete', 'FinanceController@paymentMethodDelete');

$router->get('get/media', 'UploadController@getMedia');
$router->post('media/upload', 'UploadController@uploadMedia');
$router->post('media/delete', 'UploadController@mediaDelete');