<?php
//not git update. code is diffrent delete form server or git
//Route::get('clear_cache', function () {
//
//    \Artisan::call('cache:clear');
//    \Artisan::call('config:clear');
//    \Artisan::call('view:clear');
//    dd("Cache is cleared");
//
//});

use App\Http\Controllers\StaffingCompany\WeekStateController;


Route::view('/working', 'empty_page', ['name' => 'working']);
Route::get('/change-language/{lang}', 'LanguageController@changeLanguagelanding');
Route::post('/language-chooser', 'LanguageController@changeLanguage');
Route::post('/language', [
    'before' => 'csrf',
    'as' => 'language-chooser',
    'uses' => 'LanguageController@changeLanguage'
]);

Route::group(['middleware' => 'role:superadmin'], function () {

    Route::group(['middleware' => 'role:superadmin,delete user'], function () {

        Route::get('/check', function (\Illuminate\Http\Request $request) {
            echo "<h1>Delete users</h1>";
        });
    }); // inside role + permission middleware

    Route::get('/check/without', function (\Illuminate\Http\Request $request) {
        echo "<h1>Hello Pakistan</h1>";
    });
}); // inside role middleware





// Route::get('/', function () {
//     // return view('welcome');
//     return view('landing_page.index_dup');
// });

Route::get('/map', '\App\Http\Controllers\StaffingCompany\MapController@index')->name('map');


Route::get('/', 'LanguageController@landingPage')->name('/');

Route::post('/monthly', 'LanguageController@monthlyFormData');

Route::post('/yearly', 'LanguageController@yearlyFormData');

Route::get('privacy', 'LanguageController@privacy')->name('privacy');


Auth::routes();

Route::post('signup', 'Auth\RegisterUserController@register')->name('signup');



// Route::group(['middleware' => ['auth', 'marketing']], function () {
//     Route::resource('/contacts', 'StaffingCompany\ContactController');
//     Route::resource('/customers', 'StaffingCompany\CustomerController');
//     Route::resource('/staffing_projects', 'StaffingCompany\StaffingProjectController');
// });
// Auth middleware
Route::group(['middleware' => 'auth'], function () {
    Route::resource('quotations', 'StaffingCompany\ContainerQuotationController');
    Route::post('/quotations/{id}/status', 'StaffingCompany\ContainerQuotationController@updateStatus')->name('quotations.updateStatus'); // status update
    Route::get('/quotations/{id}/pdf', 'StaffingCompany\ContainerQuotationController@downloadPDF')->name('quotations.viewPdf');
    //staffing company routes
    Route::get('/activity-logs', 'StaffingCompany\ActivityLogController@index')->name('activity-logs');
    Route::get('/user-profile', 'StaffingCompany\UserController@edit')->name('user-profile');
    Route::put('/user-profile', 'StaffingCompany\UserController@update');
    Route::get('/home', 'StaffingCompany\DashboardController@index')->name('home');

    // =====================
    //  CAMP MAINTENANCE
    // =====================
    Route::resource('camp-maintenance', 'StaffingCompany\CampMaintenanceProjectController');
    Route::get('camp-maintenance/{id}/download-pdf', 'StaffingCompany\CampMaintenanceProjectController@downloadPdf')
        ->name('camp-maintenance.download-pdf');


    // =====================
    //  OFFERS (HANDOVER)
    // =====================
    // Email history page
    Route::get('/offers/{type}/{id}/history', 'StaffingCompany\OfferController@emailHistory')->name('offers.history');
    Route::get('/offers/{type}/{id}/send/{mode?}', 'StaffingCompany\OfferController@sendMail')->name('offers.send');
    Route::patch('/offer/{type}/{id}/accept', 'StaffingCompany\OfferController@acceptOffer')->name('offer.accept');



    Route::get('offers', 'StaffingCompany\OfferController@handIndex')->name('offers.index');
    Route::get('offers/create', 'StaffingCompany\OfferController@create')->name('offers.create');
    Route::post('offers', 'StaffingCompany\OfferController@store')->name('offers.store');
    Route::get('offers/{id}/pdf', 'StaffingCompany\OfferController@downloadPDF')->name('offers.downloadPDF');
    Route::delete('offers/{id}', 'StaffingCompany\OfferController@destroy')->name('offers.destroy');


    // =====================
    //  RETAIL OFFERS
    // =====================
    Route::get('retail-offers', 'StaffingCompany\OfferController@retailIndex')->name('retail-offers.index');
    Route::get('retail-offers/create', 'StaffingCompany\OfferController@createRetail')->name('retail-offers.create');
    Route::post('retail-offers/store', 'StaffingCompany\OfferController@storeRetail')->name('retail-offers.store');
    Route::get('retail-offers/{id}/pdf', 'StaffingCompany\OfferController@downloadRetailPDF')->name('retail-offers.downloadPDF');
    Route::delete('retail-offers/{id}', 'StaffingCompany\OfferController@destroyRetail')->name('retail-offers.destroy');


    // =====================
    //  SITE / HUT MAINTENANCE
    // =====================
    Route::get('site-offers', 'StaffingCompany\OfferController@siteIndex')->name('site-offers.index');
    Route::get('site/create', 'StaffingCompany\OfferController@createSite')->name('site.create');
    Route::post('site/store', 'StaffingCompany\OfferController@site_maintance_store')->name('site-offers.store');
    Route::get('site/{id}/pdf', 'StaffingCompany\OfferController@downloadSitePDF')->name('site-offers.downloadPDF');
    Route::delete('site-offers/{id}', 'StaffingCompany\OfferController@destroySite')->name('site-offers.destroy');


    // =====================
    //  MULTI-SERVICE OFFER
    // =====================
    Route::get('multi-service',        'StaffingCompany\OfferController@multiServiceIndex')->name('multiservice-offers.index');
    Route::get('multi-service/create', 'StaffingCompany\OfferController@multiServiceCreate')->name('multi-service.create');
    Route::post('multi-service',       'StaffingCompany\OfferController@multiServiceStore')->name('multi-service.store');
    Route::post('services', 'StaffingCompany\OfferController@storeService')->name('services.store');
    Route::get('multi-service/{id}/pdf', 'StaffingCompany\OfferController@downloadMultiPDF')->name('multiservice-offers.downloadPDF');
    Route::delete('multi-service/{id}', 'StaffingCompany\OfferController@destroyMultiService')->name('multiservice-offers.destroy');

    Route::group(['middleware' => 'user-rights:1'], function () {
        Route::resource('/personnels', 'StaffingCompany\PersonnelController');
        Route::post('/personnels/send-email', 'StaffingCompany\PersonnelController@bulk_update_password')
            ->name('personnels.send-email');

        Route::get('/personnelssss/{personnel}', 'StaffingCompany\PersonnelController@update_password')->name('personnelssss.update_password');


        Route::get('/image', 'StaffingCompany\PersonnelController@iamge');

        Route::get('/personnel-work-history/{personnel}', 'StaffingCompany\PersonnelController@employeeHistory')->name('employee-history');
        Route::get('/personnel-work-history-pdf/{personnel}/{from_week_no}/{to_week_no}', 'StaffingCompany\PersonnelController@employeeWorkHistoryPDF')->name('personnel-work-history-pdf');
        Route::post('/personnel-search-work-history/{personnel}', 'StaffingCompany\PersonnelController@employeeSearchWorkHistory')->name('employee-search-work-history');
    });

    Route::group(['middleware' => 'user-rights:2'], function () {
        Route::resource('/employee_functions', 'StaffingCompany\EmployeeFunctionsController');
    });

    Route::group(['middleware' => 'user-rights:3'], function () {
        Route::resource('/customers', 'StaffingCompany\CustomerController');
    });

    Route::group(['middleware' => 'user-rights:4'], function () {
        Route::resource('/departments', 'StaffingCompany\DepartmentController');
    });

    Route::group(['middleware' => 'user-rights:5'], function () {
        Route::resource('/staffing_projects', 'StaffingCompany\StaffingProjectController');
    });

    Route::group(['middleware' => 'user-rights:6'], function () {
        Route::resource('/project_plannings', 'StaffingCompany\ProjectPlanningController');
        Route::post('/copy_project_planning/{id}', 'StaffingCompany\ProjectPlanningController@copyProjectPlanning')->name('copy-project-planning');
        Route::post('/planning/{id}/create-week-state', 'StaffingCompany\ProjectPlanningController@createWeekState')->name('planning.create-week-state');

    });

    Route::group(['middleware' => 'user-rights:7'], function () {
        Route::resource('/contacts', 'StaffingCompany\ContactController');
    });

    Route::group(['middleware' => 'user-rights:8'], function () {
        Route::resource('/request_personnels', 'StaffingCompany\RequestPersonnelController');
        Route::post('/request-personnels/{id}/approve', 'StaffingCompany\RequestPersonnelController@approve')->name('request_personnels.approve');
    });

    Route::group(['middleware' => 'user-rights:9'], function () {
        Route::resource('/container-supplier', 'StaffingCompany\ContainerSupplierController');
    });

    Route::group(['middleware' => 'user-rights:10'], function () {
        Route::resource('/order-waste-container', 'StaffingCompany\OrderContainerController');
        Route::get('/order-containers/{status?}', 'StaffingCompany\OrderContainerController@index')->name('order_containers.index');

        Route::post('/order-waste-container/{id}/status', 'StaffingCompany\OrderContainerController@updateStatus');
    });

    Route::group(['middleware' => 'user-rights:11'], function () {
        Route::any('/week-state/{id}/edit', [WeekStateController::class, 'edit_week'])->name('week-state.edit');
        Route::resource('/week-state', 'StaffingCompany\WeekStateController');
        Route::get('/week-weekly-state', 'StaffingCompany\WeekStateController@weekWiseProjects')->name('week-weekly-state');
        Route::get('/search-week-weekly-state/{week_no}', 'StaffingCompany\WeekStateController@searchWeekWiseData')->name('search-week-wise-data');
        Route::get('week-state-pdf/{id}/project/{project_id}/directing/{directing}', 'StaffingCompany\WeekStateController@WeekCardPDF');
        Route::get('/weekcard-pdf/{weekStateId}/{projectId}/{personnelId}', [WeekStateController::class, 'singleWeekCardPDF']);
        Route::post('week-states-report', 'StaffingCompany\WeekStateController@weekStatesReport')->name('week-states-report');
    });

    Route::group(['middleware' => 'user-rights:12'], function () {
        Route::resource('/comments', 'StaffingCompany\CommentController');
    });

    Route::group(['middleware' => 'user-rights:13'], function () {
        Route::resource('/employment-agencies', 'StaffingCompany\EmploymentAgencyController');
    });

    Route::group(['middleware' => 'user-rights:14'], function () {
        Route::resource('/user-options', 'StaffingCompany\UserRightsController');
    });

    // marketing route
    Route::group(['middleware' => ['user-rights:13']], function () {
        Route::get('/contacts', 'StaffingCompany\ContactController@index')->name('contacts.index');
        Route::get('/contacts/{id}', 'StaffingCompany\ContactController@show')->name('contacts.show');
        Route::get('/customers', 'StaffingCompany\CustomerController@index')->name('customers.index');
        Route::get('/customers/{id}', 'StaffingCompany\CustomerController@show')->name('customers.show');
        Route::get('/customers/{id}', 'StaffingCompany\CustomerController@view')->name('customers.view');

        Route::get('/staffing_projects', 'StaffingCompany\StaffingProjectController@index')->name('staffing_projects.index');
        Route::get('/staffing_projects/{id}', 'StaffingCompany\StaffingProjectController@show')->name('staffing_projects.show');
    });


    //    pdf of container to send supplier
    Route::get('/pdf/{id}', 'StaffingCompany\OrderContainerController@pdf')->name('order-container-pdf');
    //  end  pdf of container to send supplier 

    Route::resource('/employment-agency-overview', 'StaffingCompany\EmploymentAgencyOverviewController');
    Route::get('/employment-agency/{id}/week-state/{week_no}', 'StaffingCompany\EmploymentAgencyOverviewController@agencyWeekState')->name('agency-week-state');
    Route::get('/employment-agency-overview/{ids}/create/{agency_id}/week-no/{week_no}', 'StaffingCompany\EmploymentAgencyOverviewController@create')->name('employment-agency-overview.create');
    Route::get('/week-state-pdf', 'StaffingCompany\EmploymentAgencyOverviewController@WeekCardPDF')->name('week-state-pdf');
    Route::get('/employment-agency-overview-email', 'StaffingCompany\EmploymentAgencyOverviewController@agencyEmail')->name('week-state-email');
    Route::get('/project-planning-pdf/{id}', 'StaffingCompany\ProjectPlanningController@planningPDFF')->name('project-planning-pdf');
    Route::get('/request-personnel-pdf/{id}', 'StaffingCompany\RequestPersonnelController@downloadPDF')->name('request-personnel-pdf');

    Route::post('/user-rights/{user_right_id}', 'StaffingCompany\UserRightsController@saveUserRights')->name('user-rights.save');
    Route::get('/customer/{customer}/show', 'StaffingCompany\CustomerController@view')->name('customer.view');
    Route::get('/show-user/{user}', 'StaffingCompany\UserController@show');
    Route::get('/customers/{customer_id}/departments', 'StaffingCompany\DepartmentController@getDepartmentsForCustomer');
    Route::get('/departments/{department_id}/contacts', 'StaffingCompany\ContactController@getContactsForDepartment');
    Route::get('/employment-agency/{agency}/show', 'StaffingCompany\EmploymentAgencyController@view')->name('employment-agency.view');
    Route::get('/department/{department}/show', 'StaffingCompany\DepartmentController@view')->name('department.view');
    Route::get('/order-waste-container/{order_waste}/show', 'StaffingCompany\OrderContainerController@view')->name('order-container.view');
    Route::get('/week-state/{week}/show', 'StaffingCompany\WeekStateController@view')->name('week-state.view');
    Route::post('/search-week-state', 'StaffingCompany\WeekStateController@search')->name('week-state.search');
    Route::get('/comments/{comment}/show', 'StaffingCompany\CommentController@view')->name('comments.show');
    Route::get('/personnels/{personnel}/show', 'StaffingCompany\PersonnelController@view')->name('personnels.show');
    Route::get('/staffing_project/{project}/show', 'StaffingCompany\StaffingProjectController@view')->name('staffing-project.view');
    Route::get('/contacts/{contact}/show', 'StaffingCompany\ContactController@view')->name('contacts.show');
    Route::delete('/remove_planning_project/{date}/{id}', 'StaffingCompany\ProjectPlanningController@removeProject')->name('remove-planning-project');
    Route::resource('/employee_plannings', 'StaffingCompany\EmployeeProjectPlanningController');
Route::post('/daily-planning/project/{id}/inactive', 'StaffingCompany\EmployeeProjectPlanningController@toggleProjectInactive')
    ->name('toggle-project-inactive');
    Route::get('/employee_functions/{employee_function}/show', 'StaffingCompany\EmployeeFunctionsController@view')->name('employee-functions.show');
    Route::get('/request-personnel/{personnel}/show', 'StaffingCompany\RequestPersonnelController@view')->name('request-personnel.view');
    Route::get('/container-supplier/{container_supplier}/show', 'StaffingCompany\ContainerSupplierController@view')->name('container-supplier.view');
    Route::post('/search-employee-agency-overview', 'StaffingCompany\EmploymentAgencyOverviewController@search')->name('search-employee-agency-overview');

    Route::group(['namespace' => 'Common'], function () {
        // All routes that are common for both admin and super-admin
        ////Area routes
        Route::resource('area', 'AreaController');
        Route::get('area/deleteArea/{area}', 'AreaController@deleteRecord')->name('area.delete');
        Route::get('/area/getAreas/{id}', 'AreaController@getAreas');
        ////Customer routes
        Route::resource('customer', 'CustomerController');
        Route::get('supcustomer', 'CustomerController@sup_index')->name('sup_customer.index');
        Route::get('sup_customer/create', 'CustomerController@sup_create')->name('sup_customer.create');
        Route::get('sup_customer/edit/{customer}', 'CustomerController@sup_edit')->name('sup_customer.edit');
        Route::get('sup_customer/show/{customer}', 'CustomerController@sup_show')->name('sup_customer.show');

        Route::get('customer/deleteCustomer/{customer}', 'CustomerController@deleteRecord')->name('customer.delete');

        Route::post(
            'getAddressFromPostCode',
            'CustomerController@getAddressFromPostCode'
        );
    });

    Route::group(['namespace' => 'SuperAdmin', 'middleware' => 'role:superadmin'], function () {

        Route::get('/superAdmin', 'SuperAdminController@index')->name('sup_admin.dashboard');
        Route::get('/adminDetail', 'SuperAdminController@adminDetail')->name('sup_admin.adminDetail');
        Route::get('/adminProfile', 'SuperAdminController@adminProfile')->name('sup_admin.profile');
        Route::post('/adminUpdateDetail', 'SuperAdminController@adminUpdateDetail')->name('sup_admin.updatedetail');

        /////////////// Migrated Routes from Customer routes starts here ///////////////
        Route::resource('modulePrice', 'ModulePriceController');

        ////Social Insurance routes
        Route::resource('socialInsurance', 'SocialInsuranceController');
        Route::get('insuranceDetail/{id}', 'SocialInsuranceController@insuranceDetail')->name('insuranceDetail');
        Route::get('hourlyRateIndex', 'SocialInsuranceController@hourlyRateIndex')->name('hourlyRateIndex');

        ////Employee Group routes
        Route::resource('employeeGroup', 'EmployeeGroupController');
        Route::get('emplGroupDetail/{id}', 'EmployeeGroupController@empGroupDetail');
        Route::get('emplInsuranceDetail', 'EmployeeGroupController@emplInsuranceDetail');

        ////workable days calculation routes
        Route::resource('workableDaysCalculation', 'WorkableDaysCalculationController');
        Route::get('workableDaysDetail/{workableDaysCalculation}', 'WorkableDaysCalculationController@workableDaysDetail');

        ////Floor routes
        // Route::resource('floor','FloorController');
        // Route::get('floor/deleteFloor/{floor}', 'FloorController@deleteRecord')->name('floor.delete');
        // Route::get('/area/getAreas/{id}','AreaController@getAreas');

        ////FloorType routes
        Route::resource('floorType', 'FloorTypeController');
        Route::get('floorType/deleteFloorType/{floorType}', 'FloorTypeController@deleteFloorType')->name('floorType.delete');

        ////Room Types routes
        Route::resource('roomType', 'RoomTypesController');
        Route::get('deleteRoomType/{id}', 'RoomTypesController@deleteType')->name('deleteRoomType');

        ////Staff Type routes
        Route::resource('staffType', 'StaffTypeController');
        Route::get('staffType/deleteType/{id}', 'StaffTypeController@deleteRecord')->name('staffType.delete');

        ////Task routes
        Route::resource('task', 'TaskController');
        Route::get('task/deleteTask/{task}', 'TaskController@deleteRecord')->name('task.delete');
        Route::get('task/getProjects/{id}', 'TaskController@getProjects');
        Route::get('task/getAreas/{id}', 'TaskController@getAreas');

        /*element routes*/
        Route::resource('element', 'ElementController');
        Route::get('element/deleteElement/{element}', 'ElementController@deleteRecord')->name('element.delete');


        /////////////// Migrated Routes from Customer routes ends here ///////////////

        Route::get('/companyManage', 'CompanyController@index')->name('supadmin.companiesIndex');
        Route::get('/createCompany', 'CompanyController@create')->name('supadmin.createCompany');
        Route::post('/saveCompany', 'CompanyController@store')->name('supadmin.saveCompany');
        Route::get('/edit/{id}', 'CompanyController@edit')->name('supadmin.edit');
        Route::post('/updateCompany/{id}', 'CompanyController@update')->name('supadmin.updateCompany');
        Route::get('/deleteCompany/{id}', 'CompanyController@destroy')->name('supadmin.deleteCompany');

        ///////////permissions
        Route::get('/permissionManage', 'PermissionController@index')->name('sup_admin.permissionsIndex');
        Route::get('/createPermission', 'PermissionController@create')->name('sup_admin.createPermission');
        Route::post('/savePermission', 'PermissionController@store')->name('sup_admin.savePermission');
        Route::get('/editPermission/{permission}', 'PermissionController@edit')->name('sup_admin.editPermission');
        Route::post('/updatePermission/{id}', 'PermissionController@update')->name('sup_admin.updatePermission');
        Route::get('/deletePermission/{permission}', 'PermissionController@destroy')->name('sup_admin.deletePermission');

        //////////roles
        Route::get('/roleManage', 'RoleController@index')->name('sup_admin.rolesIndex');
        Route::get('/createRole', 'RoleController@create')->name('sup_admin.createRole');
        Route::post('/saveRole', 'RoleController@store')->name('sup_admin.saveRole');
        Route::get('/editRole/{role}', 'RoleController@edit')->name('sup_admin.editRole');
        Route::post('/updateRole/{id}', 'RoleController@update')->name('sup_admin.updateRole');
        Route::get('/deleteRole/{role}', 'RoleController@destroy')->name('sup_admin.deleteRole');

        Route::get('/paymentManage', 'SuperAdminController@paymentManage')->name('sup_admin.payments');
        Route::get('/rolesManage', 'SuperAdminController@rolesManage')->name('sup_admin.roles');

        ////////// WorkerTypes
        Route::resource('workerTypes', 'WorkerTypesController');
        Route::get('workerTypes/deleteThisRecord/{id}', 'WorkerTypesController@deleteThisRecord')->name('workerTypes.delete');

        ////////////////users
        Route::get('/workersIndex', 'UserController@index')->name('supadmin.workersIndex');
        Route::get('/createUser', 'UserController@create')->name('supadmin.createUser');
        Route::post('/saveUser', 'UserController@store')->name('supadmin.saveUser');
        Route::get('/viewUser/{user}', 'UserController@show')->name('supadmin.viewUser');
        Route::get('/editUser/{id}', 'UserController@edit')->name('supadmin.editUser');
        Route::post('/updateUser/{id}', 'UserController@update')->name('supadmin.updateUser');
        Route::get('/deleteUser/{id}', 'UserController@destroy')->name('supadmin.deleteUser');
        Route::get('/statusChange/{user}', 'UserController@statusChange')->name('supadmin.statusChange');
        // Route::get('/usersIndex','UserController@allUsers')->name('supadmin.usersIndex');
        // Route::get('/allusers','UserController@allUsers')->name('allusers');
        Route::get('/allusers', 'UserController@allUsers')->name('supadmin.allUsers');
        Route::get('/viewAllUser/{user}', 'UserController@viewAllUser')->name('supadmin.viewAllUser');

        Route::resource('users', 'UserController');
    });

    //CompanyAdmin Routes in this block
    Route::group(['namespace' => 'CompanyAdmin', 'middleware' => 'role:admin'], function () {

        Route::get('/inspectionIndex', 'InspectionReportController@index')->name('inspection.index');
        Route::get('/inspectionView/{id}', 'InspectionReportController@view')->name('inspection.view');
        Route::get('/downloadPdfReport/{id}', 'InspectionReportController@downloadPdfReport')->name('inspection.download');
        Route::post('/uploadreport', 'InspectionReportController@uploadReport')->name('uploadReport');
        Route::get('/getCustomerProjects/{id}', 'InspectionReportController@getCustomerProjects')->name('getCustomerProjects');
        Route::get('/externalreport', 'InspectionReportController@externalreportIndex')->name('externalreport');
        Route::get('/externalreportdata', 'InspectionReportController@externalreportdata')->name('externalreportdata');
        Route::get('/companyProjects', 'InspectionReportController@companyProjects');
        Route::post('/projectInspectionReport', 'InspectionReportController@projectInspectionReport');

        Route::get('/companyAdmin', 'CompanyAdminController@index')->name('com_admin.dashboard');
        Route::get('/companyProfile', 'CompanyAdminController@companyProfile')->name('com_admin.profile');
        Route::get('/companyDetail', 'CompanyAdminController@companyDetail')->name('com_admin.detail');
        Route::post('/companyUpdateDetail', 'CompanyAdminController@companyUpdateDetail')->name('com_admin.updatedetail');

        /////Supplier routes
        Route::resource('supplier', 'SupplierController');
        Route::get('supplier/deleteSupplier/{supplier}', 'SupplierController@deleteRecord')->name('supplier.delete');
        ////material routes
        Route::resource('material', 'MaterialController');
        Route::get('material/deleteMaterial/{material}', 'MaterialController@deleteRecord')->name('material.delete');


        ////Employment Agency routes
        Route::resource('employ_agency', 'EmployAgencyController');
        Route::get('employ_agency/deleteAgency/{employAgency}', 'EmployAgencyController@deleteRecord')->name('employ_agency.delete');

        ////Customer routes
        // Route::resource('customer','CustomerController');
        // Route::get('customer/deleteCustomer/{customer}', 'CustomerController@deleteRecord')->name('customer.delete');

        ////Project routes
        Route::resource('project', 'ProjectController');
        Route::get('project/deleteProject/{project}', 'ProjectController@deleteRecord')->name('project.delete');
        Route::get('project/removeLocation/{id}', 'ProjectController@removeLocation')->name('project.removeLocation');
        Route::get('project/deleteJob/{id}/{project_id}', 'ProjectController@deleteJob')->name('project.deleteJob');
        Route::post('project/updateProject/{project}', 'ProjectController@updateProject')->name('project.updateProject');
        Route::get('projectDetails/{id}', 'ProjectController@projectDetails');
        Route::get('dayDetails/{id}', 'ProjectController@dayDetails');
        Route::post('saveEditJob/{id}', 'ProjectController@saveEditJob');
        Route::get('addDayDetails', 'ProjectController@addDayDetails'); //
        Route::get('getRelatedAreas/{id}', 'ProjectController@getRelatedAreas');
        Route::get('getRelatedWorkers/{id}', 'ProjectController@getRelatedWorkers');
        Route::post('updateProjectDetails/{id}', 'ProjectController@updateProjectDetails');
        // add project new routes
        Route::get('getDetails', 'ProjectController@getDetails');
        Route::post('addProject', 'ProjectController@addProject');
        Route::post('getFloorAreaJobs', 'ProjectController@getFloorAreaJobs');
        Route::post('getFloorAreas', 'ProjectController@getFloorAreas');
        Route::get('projectAllJobPdf/{project_id}', 'ProjectController@projectAllJobPdf')->name('projectAllJobPdf');
        Route::get('projectJobPdf/{project_id}/{job_id}/{area_id}', 'ProjectController@projectJobPdf')->name('projectJobPdf');
        // add project new routes

        // project material quotes routes starts here
        Route::get('quotesIndex', 'MaterialQuotesController@quotesIndex')->name('quotesIndex');
        Route::get('quoteRequest/{id}', 'MaterialQuotesController@quoteRequest')->name('quoteRequest');
        Route::get('quoteList/{id}', 'MaterialQuotesController@quoteList')->name('quoteList');
        Route::get('projectList', 'MaterialQuotesController@projectList')->name('projectList');
        Route::get('materialList', 'MaterialQuotesController@materialList')->name('materialList');
        Route::post('placeOrder', 'MaterialQuotesController@placeOrder')->name('placeOrder');
        // project material quotes routes ends here

        ////Floor routes
        Route::resource('floor', 'FloorController');
        Route::get('floor/deleteFloor/{floor}', 'FloorController@deleteRecord')->name('floor.delete');

        //Project cost estimate routes starts here
        // Route::resource('ProjectCostEstimate', 'ProjectCostEstimateController');
        Route::resource('projectcostestimate', 'ProjectCostEstimateController');
        Route::get('deleteProjectCostEstimate/{id}', 'ProjectCostEstimateController@deleteEstimate')->name('deleteEstimate');
        Route::get('getprojectcostestiamte/{id}', 'ProjectCostEstimateController@getProjectCostEstimateDetail')->name('getprojectcostestiamte');
        Route::get('getAreaTaskElementTables/{id}/{area_id}', 'ProjectCostEstimateController@getAreaTaskElementTables')->name('getAreaTaskElementTables');
        Route::get('downloadEstimatePDF/{id}', 'ProjectCostEstimateController@downloadEstimatePDF')->name('downloadEstimatePDF');
        Route::get('/customersList', 'ProjectCostEstimateController@customerList');
        Route::get('/commentsList/{id}', 'ProjectCostEstimateController@commentsList');
        Route::get('/selectedRoomTypes/{id}', 'ProjectCostEstimateController@selectedRoomTypes');
        Route::post('/correctionStand', 'ProjectCostEstimateController@correctionStand');
        //Project cost estimate routes ends here
        //
        // ////Area routes
        // Route::resource('area','AreaController');
        // Route::get('area/deleteArea/{area}', 'AreaController@deleteRecord')->name('area.delete');

        ////Staff routes
        Route::resource('staff', 'StaffController');
        Route::get('staff/deleteArea/{user}', 'StaffController@deleteRecord')->name('staff.delete');
        Route::get('staff/statusChange/{user}', 'StaffController@statusChange')->name('staff.statusChange');
        Route::get('getStaffEditDetails/{id}', 'StaffController@getStaffEditDetails')->name('getStaffEditDetails');


        //Worker Reports
        Route::resource('workerReport', 'WorkerReportController');
        //        Route::get('getWorkerTaskDetails/{id}', 'WorkerReportController@getWorkerTaskDetails')->name('getWorkerTaskDetails');


        Route::post('getWorkerTaskDetails', 'WorkerReportController@getWorkerTaskDetails')->name('getWorkerTaskDetails');
    });

    Route::group(['namespace' => 'Workers', 'middleware' => 'role:user'], function () {
        Route::get('/index', 'WorkerController@index')->name('workerIndex');
        Route::get('/workerDetails', 'WorkerController@workerDetails')->name('workerDetails');
        Route::get('/myJobs', 'WorkerController@myJobs')->name('myJobs');
        Route::get('/workerAllJobs', 'WorkerController@workerAllJobs')->name('workerDetails');
        Route::get('/startJob/{id}', 'WorkerController@startJob')->name('startJob');
        Route::get('/endJob/{id}', 'WorkerController@endJob')->name('endJob');
        Route::get('/checkJobStatus/{id}', 'WorkerController@checkJobStatus')->name('checkJobStatus');
        Route::get('/getWeekCards/{id}', 'WorkerController@getWeekCards')->name('getWeekCards');
    });

    Route::group(['namespace' => 'CustomerAdmin', 'middleware' => 'role:customer'], function () {
        Route::get('/myProjects', 'CustomerAdmin@myProjects')->name('customer.myProjects');
        Route::get('/viewProject/{project}', 'CustomerAdmin@projectDetail')->name('customer.projectDetail');
        Route::get('/myTasks', 'CustomerAdmin@myTasks')->name('customer.myTasks');
    });
});

// Route::resource('supplier','SupplierController');
//Route::get('/order-container/{id}','StaffingCompany\OrderContainerController@pdf')->name('order-container-pdf');
//Route::view('/pdf','StaffingCompany/OrderWasteContainer/pdf')->name('order-container-pdf');
