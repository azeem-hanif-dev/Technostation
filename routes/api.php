<?php



Route::get('auth/availability/{id}', 'APIs\StaffingCompany\WorkerAvailabilityController@index');
Route::post('auth/personnel/availability/respond', 'APIs\StaffingCompany\WorkerAvailabilityController@respond');
Route::group([
    'prefix' => 'auth',
    'namespace' => 'APIs'

], function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');


    // AuthController for staffing company
    Route::post('signup', 'StaffingCompany\Auth\AuthController@signup');
    Route::post('signin', 'StaffingCompany\Auth\AuthController@signin');
    Route::post('signinBySupervisor', 'StaffingCompany\Auth\AuthController@signinBySupervisor');
    Route::post('signinBySupplier', 'StaffingCompany\Auth\AuthController@signinBySupplier');
    Route::get('signout', 'StaffingCompany\Auth\AuthController@signout');
    Route::get('me', 'StaffingCompany\Auth\AuthController@me');
    Route::post('SetFcmToken', 'StaffingCompany\Auth\AuthController@setFCM');
});

Route::group(
    [
        'middleware' => 'JWT',
        'namespace' => 'APIs'
    ],
    function () {


        Route::post('/update-password', 'StaffingCompany\Auth\AuthController@password');
        Route::post('/update-supervisor', 'StaffingCompany\Profile@updateSupervisorInfo');
        Route::post('/update-worker', 'StaffingCompany\Profile@updateBasicInfo');

        Route::post('/update-superviosor-image', 'StaffingCompany\Profile@supervisor_picture_update');
        Route::post('/update-personnel-image', 'StaffingCompany\Profile@personnel_update_picture');

        Route::post('/profile', 'StaffingCompany\Profile@update');
        Route::get('/dailyJobsCount', 'EmployeeController@home');
        Route::get('/allWeeklyJobs', 'EmployeeController@allWeeklyJobs');
        Route::get('/allDailyJobs', 'EmployeeController@allDailyJobs');

        Route::get('/projectsList', 'ApiProjectController@projectsList');

        Route::post('/projectDetail/{id}', 'ApiProjectController@projectDetail');
        Route::get('/projectWorkers/{id}/location/{location_id}', 'ApiProjectController@projectWorkers');
        Route::get('/myTodayTasks/{project_id}/locationId/{location_id}', 'ApiProjectController@myTodayTasks')->name('myTodayTasks');

        Route::get('/myNotifications', 'ApiNotificationController@myNotifications')->name('myNotifications');
        Route::get('/markread/{id}', 'ApiNotificationController@markread')->name('markread');

        Route::get('/customersList', 'ApiCustomerController@customerList');
        Route::post('/customerDetail/{id}', 'ApiCustomerController@customerDetail');

        Route::post('/jobsList/{id}', 'ApiJobsController@projectJobsList');
        Route::post('/jobDetail/{id}', 'ApiJobsController@jobDetail');
        Route::post('/jobTasksList/{id}', 'ApiJobsController@jobTasksList');
        Route::post('/taskDetail/{id}', 'ApiJobsController@jobTaskDetail');
        Route::post('/startJobTime', 'ApiJobsController@startJobTime');
        Route::post('/endJobTime', 'ApiJobsController@endJobTime');

        ///////APIs for Leaves starts from here /////////
        Route::post('/leaverequest', 'ApiLeaveController@leaverequest')->name('leaverequest');
        Route::get('/myleaves', 'ApiLeaveController@myleaves')->name('myleaves');

        Route::get('/myrejectedleaves', 'ApiLeaveController@myrejectedleaves')->name('myrejectedleaves');
        Route::get('/leavepermission', 'ApiLeaveController@leavePermission')->name('leavepermission');
        ///////APIs for Leaves ends from here /////////

        /////////APIs for Supervisor starts from here ////////
        Route::get('projects-lists', 'StaffingCompany\ApiRequestPersonnelController@projectsList');
        Route::get('request-personnels', 'StaffingCompany\ApiRequestPersonnelController@index');


        Route::get('employee-functions', 'StaffingCompany\ApiRequestPersonnelController@employeeFunctions');
        Route::post('request-personnel', 'StaffingCompany\ApiRequestPersonnelController@store');
        Route::get('/myworkers', 'ApiSupervisorController@myworkers')->name('myworkers');
        Route::get('/supervisorworkers/{id}', 'ApiSupervisorController@supervisorworkers')->name('supervisorworkers');
        Route::get('/projectworkers/{id}', 'ApiSupervisorController@projectworkers')->name('projectworkers');
        Route::get('/supervisorProjects', 'ApiSupervisorController@supervisorProjects')->name('supervisorProjects');
        Route::post('/supleavemark', 'ApiSupervisorController@supleavemark')->name('supleavemark');
        Route::post('/manleavemark', 'ApiSupervisorController@manleavemark')->name('manleavemark');
        Route::post('/completedtasksofday', 'ApiSupervisorController@completedtasksofday')->name('completedtasksofday');
        Route::post('/ratetask', 'ApiSupervisorController@ratetask')->name('ratetask');
        Route::post('/inspectionreview', 'ApiSupervisorController@inspectionreview')->name('inspectionreview');
        Route::post('/getinspectionreview', 'ApiSupervisorController@getinspectionreview')->name('getinspectionreview');
        Route::get('/workerleavelist/{id}', 'ApiSupervisorController@workerleavelist')->name('workerleavelist');
        Route::post('/replacementRequest', 'ReplacementRequestController@makeRequest');
        /////////APIs for Supervisor ends here ///////////////

        //new build
        Route::get('/time_tracking_last_2_weeks', 'ApiSupervisorController@tracking2weeks')->name('tracking2weeks1');
        //    Route::get('/time_tracking_last_2_weeks','ApiSupervisorController@tracking2weeks')->name('supervisorProjects');

        Route::group([
            'prefix' => 'auth',
            'namespace' => 'StaffingCompany'

        ], function () {


            // EmployeeController
            Route::get('/instructional-videos', 'EmployeeController@video_link');
            Route::get('last-four-week-planning-history', 'EmployeeController@last_four_week_Planning_History');
            Route::post('updateHours', 'EmployeeController@updateHours');
            Route::post('{id}/document-upload', 'EmployeeController@Document_upload');
            Route::get('employeeProjects/{id}', 'EmployeeController@getEmployeeProjects');
            Route::get('getEmployeePlanningHistory', 'EmployeeController@getEmployeePlanningHistory');
            Route::get('getWeekStates', 'EmployeeController@getWeekStates');
            Route::get('getEmployeePlanning', 'EmployeeController@getEmployeePlanning');
            Route::post('employeeCheckIn', 'EmployeeController@employeeCheckIn');
            Route::post('employeeCheckOut', 'EmployeeController@employeeCheckOut');
            Route::get('employeeHistory/{id}','EmployeeController@getEmployeeHistory');
            // SupervisorController
            Route::get('/projects/today', 'SupervisorController@dailyProjects');
            Route::get('projectsInfo/{id}', 'SupervisorController@getProjectsInfo');



            Route::post('/supervisor/approve-time-card', 'SupervisorController@approveOrReject');
            Route::get('approved-time-card', 'SupervisorController@getapprovedtimecard')->name('approved-time-card');

            // Route::post('/update-supervisor', 'StaffingCompany\Profile@updateContactName');

            Route::get('getProjectsList/{customer_id}', 'SupervisorController@getProjectsList');
            Route::get('projectPlanning/{project_id}', 'SupervisorController@projectPlanning');
            Route::get('employees_list/{week_no}/{project_id}', 'SupervisorController@employees_list');
            Route::get('checkEmployeeAttendance', 'SupervisorController@checkEmployeeAttendance');
            Route::post('approveEmployeeAttendance', 'SupervisorController@approveEmployeeAttendance');
            Route::post('approveAllEmployeeAttendance', 'SupervisorController@approveAllEmployeeAttendance');
            Route::get('getWeekwiseAttendance', 'SupervisorController@getSupervisorAttendanceHistory');
            Route::post('fixHoursBySupervisor/{id}', 'SupervisorController@fixHoursBySupervisor');
            Route::get('employeeWorkHoursApprovedBySupervisor/{id}', 'SupervisorController@employeeWorkHoursApprovedBySupervisor');
            Route::post('aprroveWeeklyAttendance', 'SupervisorController@aprroveWeeklyAttendance');

            // leaves
            Route::post('addleaves', 'LeavesController@addleave');
            Route::get('AllLeaves', 'LeavesController@all_leave');
            Route::post('UpdateEmail', 'EmployeeController@SendEmail');
            Route::post('Verifytoken', 'EmployeeController@UpdateEmail');
            Route::post('ChangePassword', 'EmployeeController@ChangePassword');
            Route::post('ChangeName', 'EmployeeController@ChangeName');
            Route::get('WorkingHistory', 'EmployeeController@EmployeeWorkHistory');
            Route::post('GetProjectManager', 'EmployeeController@GetProjectManager');
            // new
            Route::get('last_four_week_leave', 'LeavesController@last_four_week_leave');
        });
    }
);


//    supervisor  for order container routes
Route::group(
    [
        'prefix' => 'auth',
        'namespace' => 'APIs\StaffingCompany',
        'middleware' => 'JWT'
    ],
    function () {

        Route::get('/order-waste-container/index', 'OrderContainerController@index');
        Route::get('/order-waste-container/create', 'OrderContainerController@create');
        Route::post('/order-waste-container', 'OrderContainerController@store');
        Route::post('/order-waste-container/{id}/status', 'OrderContainerController@updateStatus');
        //list of orders
        Route::get('/order-waste-container/user/{id}', 'OrderContainerController@listOfOrders');
        Route::get('/list-of-orders', 'StaffingCompany\Supplier\SupplierController@listOfOrders');
        Route::get('performer/{id}/availability', 'WorkerAvailabilityController@getAvailabilityByPerformer');


        Route::get('/order-waste-container/pdf/{id}', 'OrderContainerController@pdf');
    }
);

//suplier mobile app routes

Route::group(
    [
        'middleware' => 'JWT',
        'namespace' => 'APIs'
    ],
    function () {

        Route::post('/update-supplier', 'StaffingCompany\Supplier\SupplierController@updateProfile');
        Route::get('/list-of-orders', 'StaffingCompany\Supplier\SupplierController@listOfOrders');
    }
);

Route::post('/editworkerhour', 'APIs\ApiSupervisorController@edit_worker_hours');
