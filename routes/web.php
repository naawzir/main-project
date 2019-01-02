<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('app.url_prefix')
], function () {

    // this will eventually be removed as the root URL will be the front-end/customer facing site
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index',]);

    /** BRANCHES **/
    Route::prefix('/branch')->group(function () {
        Route::get('/', 'BranchPerformanceController@index');
        Route::get('/branches-records', 'BranchPerformanceController@branchesRecords');
        Route::get('/monthly-targets-records', 'BranchPerformanceController@monthlyTargetsRecords');
        Route::get('/kpis-ajax', 'BranchPerformanceController@kpisAjax');
        Route::get('/kpis-ajax-business-owner', 'BranchPerformanceController@kpisAjaxBusinessOwner');
        Route::get('/branch-notes-records/{branch}', 'AgencyBranchesController@branchNotesRecords');
        Route::get('/edit-kpi-target', 'BranchPerformanceController@editKPITarget');
        Route::get('/{branch}', 'BranchPerformanceController@show');
        Route::get('/case-records/{branchId}', 'ConveyancingCasesController@getCaseRecordsForBranch');
        Route::post('/add-branch-contact-note', 'AgencyBranchesController@addBranchContactNote');
        Route::post('/update-kpi-target', 'BranchPerformanceController@updateKpiTarget');
        Route::post('/create-target', 'BranchPerformanceController@createTarget');
    });

    /** CASES **/
    Route::prefix('/cases')->group(function () {
        Route::get('/', 'ConveyancingCasesController@index');
        Route::get('/create', 'ConveyancingCasesController@create')->name('createcase'); // Create Case
        Route::get('/cases-records', 'ConveyancingCasesController@casesRecords');
        Route::get('/users/find', 'UsersController@find');
        Route::get('/{case}/request-update/', 'CasesRequestUpdateController@create');
        Route::get('/generate-excel', 'ConveyancingCasesController@generateCasesReport');
        Route::get('/{case}/destroy', 'ConveyancingCasesController@destroy'); // Delete Case record
        Route::get('/get-all-branches-for-agency', 'ConveyancingCasesController@getAllBranchesForAgency');
        Route::get('/get-all-users-for-branch', 'ConveyancingCasesController@getAllUsersForBranch');
        Route::get('/get-distinct-types', 'ConveyancingCasesController@getDistinctTypes');
        Route::get('/get-distinct-statuses', 'ConveyancingCasesController@getDistinctStatuses');
        Route::get('/get-distinct-agents', 'ConveyancingCasesController@getDistinctAgents');
        Route::get('/get-distinct-account-managers', 'ConveyancingCasesController@getDistinctAccountManagers');
        Route::get('/remove-client', 'ConveyancingCasesController@removeClient');
        Route::get('/update-requests', 'CasesRequestUpdateController@show');
        Route::post('/case-overview', 'ConveyancingCasesController@caseOverviewDetails');
        Route::post('/update-transaction-customers', 'ConveyancingCasesController@updateTransactionCustomers');
        Route::post('/user/create', 'ConveyancingCasesController@createuser'); // Create user for new case
        Route::post('/user/{user}', 'ConveyancingCasesController@updateuser'); // Update user for new case
        Route::post('/case/create', 'ConveyancingCasesController@createcase'); // Create case
        Route::post('/case/{case}', 'ConveyancingCasesController@updatecase'); // Update case
        Route::post('/update', 'ConveyancingCasesController@update');
        Route::post('/get-branches-for-agency', 'ConveyancingCasesController@getBranchesForAgency'); // Details
        Route::post('/update-case-address', 'ConveyancingCasesController@updateCaseAddress');
        Route::post('/create-new-client-address', 'ConveyancingCasesController@createNewClientAddress');
        Route::post('/update-client-address', 'ConveyancingCasesController@updateClientAddress');
        Route::post('/delete-client-address', 'ConveyancingCasesController@deleteClientAddress');
        Route::post('/add-note', 'ConveyancingCasesController@addNote');
        Route::post('/save-instruction-note', 'ConveyancingCasesController@saveInstructionNote');
        Route::post('/save-agency', 'ConveyancingCasesController@saveAgency');
        Route::post('/save-solicitor', 'ConveyancingCasesController@saveSolicitor');
        Route::post('/get-users-for-branch', 'ConveyancingCasesController@getUsersForBranch'); // Details
        Route::post('/get-agents-email-address', 'ConveyancingCasesController@getAgentsEmailAddress'); // Details
        Route::post('/{case}/request-update/', 'CasesRequestUpdateController@store');
        Route::get('/update-requests-records', 'CasesRequestUpdateController@getUpdateRequests');
    });

    /** CUSTOMERS **/
    Route::prefix('/customers')->group(function () {
        Route::get('/', 'CustomersController@index');
        Route::get('/show', 'CustomersController@show');
    });

    /** DISBURSEMENTS **/
    Route::prefix('/disbursements')->group(function () {
        Route::get('/', 'DisbursementsController@index')->name('disbursement-home');
        Route::post('/', 'DisbursementsController@store');
        Route::get('/{disbursementId}/destroy/', 'DisbursementsController@destroy');
        Route::get('/edit/', function () {
            return redirect()->route('disbursement-home');
        });
        Route::get('/{disbursement}/edit/', 'DisbursementsController@edit');
        Route::post('/{disbursement}/edit/', 'DisbursementsController@update');
        Route::get('/get-disbursements', 'DisbursementsController@getDisbursements');
    });

    /** DOCUMENTS **/
    Route::prefix('/document')->group(function () {
        Route::get('/{caseslug}/get-documents-for-case/', 'DocumentsController@getDocumentsForCase'); // Show all documents for a case
        Route::get('/{document}', 'DocumentsController@show'); // Download a specific document
    });

    /** FAKE USERS **/
    if (app()->environment() !== 'production') {
        Route::get('/fake-user', 'FakeUserController@index')->name('fake-users');
        Route::post('/fake-user/activate', 'FakeUserController@activate')->name('fake-users.activate');
        Route::post('/fake-user/create', 'FakeUserController@create')->name('fake-users.create');
    }

    /** FEEDBACK **/
    Route::prefix('/feedback')->group(function () {
        Route::get('/service', 'ServiceFeedbackController@index');
        Route::get('/', 'FeedbackController@index');
        Route::get('/get-low-scoring-customer-feedback', 'FeedbackController@lowScoringCustomerFeedback');
        Route::post('/store', 'FeedbackController@store');
    });

    /** GLOBAL **/
    Route::get('/login', 'Auth\UserLoginController@create')->name('login');
    Route::post('/login', 'Auth\UserLoginController@login');
    Route::get('/logout', 'Auth\UserLoginController@destroy')->name('logout');
    Route::get('/userroles', 'UserRolesController@index');
    Route::get('/service-feedback', 'FeedbackController@serviceFeedback');
    Route::get('/training', 'TrainingController@index');
    Route::post('/training', 'TrainingController@store');
    Route::get('/contact-us', 'ContactUsController@index');
    Route::post('/contact-us', 'ContactUsController@store');
    Route::prefix('global')->group(function () {
        Route::get('/find-houses-by-postcode', 'GlobalController@findHousesByPostcode');
        Route::get('/search', 'GlobalController@search');
        Route::post('/update', 'GlobalController@update');
    });

    /** MY SOLICITORS **/
    Route::prefix('/my-solicitors/')->group(function () {
        Route::get('', 'MySolicitorController@index');
        Route::get('/{office}', 'MySolicitorController@show')->name('panel.office');
    });

    /** SOLICITORS **/
    Route::prefix('/solicitors')->group(function () {
        Route::get('/', 'SolicitorsController@index')->name('solicitor-home'); // Index
        Route::get('/create/', 'SolicitorsController@create')->name('solicitors.onboarding.create');
        Route::post('/create/', 'SolicitorsController@store');
        Route::get('/{solicitor}/edit/', 'SolicitorsController@edit');
        Route::post('/{solicitor}/edit/', 'SolicitorsController@update');
        Route::get('{solicitor}/office/create', 'SolicitorOfficesController@create');
        Route::post('/{solicitor}/office/create', 'SolicitorOfficesController@store');
        Route::get('/destroy/{solicitor}', 'SolicitorsController@destroy');
        Route::get('/get-offices-for-solicitor', 'SolicitorsController@getOfficesForSolicitor');
        Route::get('/get-records', 'SolicitorsController@getRecords');
        Route::get('/update-status-kpis', 'SolicitorsController@updateKpis');
        Route::prefix('/office')->group(function () {
            Route::get('/{officeid}/add-to-panel/', 'MySolicitorController@addToPanel');
            Route::get('/{officeid}/remove-from-panel/', 'MySolicitorController@removeFromPanel');
            Route::get('/{office}/additional-fees/create/', 'Solicitors\AdditionalFeesController@create');
            Route::post('/{office}/additional-fees/create/', 'Solicitors\AdditionalFeesController@store');
            Route::get('/{office}/additional-fees/edit/', 'Solicitors\AdditionalFeesController@edit');
            Route::post('/{office}/additional-fees/edit/', 'Solicitors\AdditionalFeesController@update');
            Route::get('/{office}/disbursements', 'SolicitorsDisbursementsController@index');
            Route::post('/{office}/disbursements', 'SolicitorsDisbursementsController@update');
            Route::get('/{solicitorOffice}/user/create/', 'SolicitorUsersController@create');
            Route::post('/{solicitorOffice}/user/create/', 'SolicitorUsersController@store');
            Route::get('/{solicitorOffice}/edit/', 'SolicitorOfficesController@edit');
            Route::post('/{solicitorOffice}/edit/', 'SolicitorOfficesController@update');
            Route::get('/fee-structure/{feeStructureId}/destroy/', 'SolicitorFeeStructuresController@destroy');
            Route::get('/{solicitorOfficeId}/fee-structures/create/', 'SolicitorFeeStructuresController@create');
            Route::post('/{solicitorOfficeId}/fee-structures/create/', 'SolicitorFeeStructuresController@store');
            Route::get('/{solicitorOfficeId}/fee-structures/edit/', 'SolicitorFeeStructuresController@edit');
            Route::post('/{solicitorOfficeId}/fee-structures/edit/', 'SolicitorFeeStructuresController@update');
            Route::get('/user/{solicitorUser}/edit/', 'SolicitorUsersController@edit');
            Route::post('/user/{solicitorUser}/edit/', 'SolicitorUsersController@update');
            Route::get('/{solicitorOfficeId}/get-fee-structures', 'SolicitorFeeStructuresController@getFeeStructures');
            Route::get('/{solicitorOffice}/get-users-for-office', 'SolicitorUsersController@getUsersForOffice');
            Route::get('/{office}', 'SolicitorOfficesController@show');
            Route::get('/{solicitorOffice}/panel-manager-submission', 'SolicitorOfficesController@panelManagerSubmission');
            Route::get('/{solicitorOffice}/tm-submission', 'SolicitorOfficesController@submitToTM');
            Route::get('/{solicitorOffice}/add-to-market/', 'SolicitorOfficesController@submitToMarketplace');
            Route::get('/user/check-email', 'SolicitorUsersController@checkEmail');
        });
        Route::prefix('/onboarding')->group(function () {
            Route::get('/', 'SolicitorOnboardingController@index');
            Route::get('/{solicitor}/offices/', 'SolicitorOnboardingController@show');
            Route::get('/get-onboarding/', 'SolicitorOnboardingController@getOnboarding');
        });
        Route::prefix('/performance')->group(function () {
            Route::get('/', 'SolicitorPerformanceController@index');
            Route::get('/get-solicitors-stats-records', 'SolicitorPerformanceController@getSolicitorsStatsRecords');
            Route::get('/get-solicitor-stats-records/{solicitor}', 'SolicitorPerformanceController@getSolicitorStatsRecords');
            Route::get('/{solicitor}', 'SolicitorPerformanceController@show');
        });
    });

    /** STAFF **/
    Route::prefix('/staff')->group(function () {
        Route::get('/', 'StaffPerformanceController@index');
        Route::post('/update-stats', 'StaffPerformanceController@update');
    });

    /** TASKS **/
    Route::prefix('/tasks')->group(function () {
        Route::post('/create', 'TasksController@createTask');
        Route::get('/get-tasks-for-user/{type}', 'TasksController@getTasks');
        Route::get('/{task}', 'TasksController@show');
        Route::post('/update/{task}', 'TasksController@update');
    });
});
