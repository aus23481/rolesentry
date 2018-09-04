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
Route::get('/carousel', 'WelcomeController@loadCarouselRequestedAlert');
Route::get('/automated-emails', 'WelcomeController@automatedEmailDemo')->name("automatedEmailDemo");
Route::get('/', 'WelcomeController@index')->name("welcome");
//Route::get('/{location_id}', 'WelcomeController@index')->name("welcome");
//Route::get('/{location_id}/{job_type_id}', 'WelcomeController@index')->name("welcome");

Route::get('/scrape-google', 'ScrapeGoogleController@searchGoogle');
Route::get('/about', 'WelcomeController@about')->name("about");
Route::get('/faq', 'WelcomeController@faq')->name("faq");
Route::get('/new', 'WelcomeController@new')->name("new");
Route::post('/contact-us', 'WelcomeController@contactUs');
Route::post('/mail-forgot-password', 'WelcomeController@sendEmailForgotPassword');

Route::get('/unsubscribe', 'WelcomeController@unsubscribe')->name("unsubscribe");
Route::post('/stripepost', 'InvoiceController@stripepost');
Route::post('/stripemonthlypost', 'InvoiceController@stripemonthlypost');

Route::post('/sendgrid_post', 'EmailInteractionController@sendgridPost');

Route::get('/invoice', 'InvoiceController@index');
Route::get('/change-password', 'WelcomeController@changePassword');
Route::post('/update-account', 'WelcomeController@updateAccount');
Route::get('/update-user-account', 'WelcomeController@updateUserAccount');
Route::get('/account-info', 'WelcomeController@updateUserAccount');
Route::get('/confirm', 'WelcomeController@confirmAccount');
Route::get('/alert', 'WelcomeController@getAlertDetail');
Route::get('/add-robot-company-by-name', 'RobotController@addRobotCompanyByName');
Route::get('/add-location', 'RobotController@addLocation');
Route::get('/locations', 'RobotController@getLocations');
Route::get('/location', 'RobotController@getLocationDetail');
Route::get('/run-manual-scrape-location', 'RobotController@runManualScrapeLocation');
Route::get('/save-location-detail', 'RobotController@saveLocationDetail');

Route::get('/add-robot-company-by-website', 'RobotController@addRobotCompanyByWebsite');
Route::get('/add-robot-company-by-career-page', 'RobotController@addRobotCompanyBycareerPage');
Route::get('/test1', 'RobotController@test1');


Route::get('/set-rapid-approve-mode/{modeId}','RobotController@setRapidApproveMode');
Route::post('/robotcms/save-company-by-name', 'RobotController@saveRobotCompanyByName');
Route::post('/robotcms/save-company-by-career-page', 'RobotController@saveRobotCompanyByCareerPage');
Route::post('/robotcms/save-company-by-website', 'RobotController@saveRobotCompanyByWebsite');
Route::get('/robot-companies', 'RobotController@getRobotCompanies');
Route::get('/robot-company-progression-status', 'RobotController@getRobotCompanyProgressionStatus');
Route::get('/robot-company', 'RobotController@getRobotCompanyDetail');
Route::get('/robot-company-approval', 'RobotController@getRobotCompanyApproval');
Route::post('/edit-robot-company', 'RobotController@editRobotCompany');
Route::post('/edit-robot-company-approval', 'RobotController@editRobotCompanyForApproval');
Route::post('/find-auto-input', 'RobotController@findAutoInput');
Route::post('/update-robot-company-approval-status', 'RobotController@updateRobotCompanyApprovalStatus');



Route::get('/welcome', 'WelcomeController@index');
Route::get('/unsubscribe-action', 'WelcomeController@unsubscribeAction');
Route::get('/cms', 'CMSController@index');
Route::post('/cms/fileupload', 'CMSController@fileupload');
Route::get('/cms/download', 'CMSController@download');
Route::get('/cms/sendSummaryAlert', 'CMSController@sendSummaryAlert');
Route::get('/cms/embargo', 'CMSController@embargo');
Route::get('/cms/previewSummaryAlert', 'CMSController@previewSummaryAlert');
Route::get('/cms/previewSavedSearchEmail', 'CMSController@previewSavedSearchAlert');
Route::get('/cms/downloadAlertSinceLastPublishedEmailWasCreated','CMSController@downloadAlertSinceLastPublishedEmailWasCreated');
Route::get('/cms/downloadAlert', 'CMSController@downloadAlert');
Route::get('/cms/createNew', 'CMSController@createNew');
Route::get('/cms/delete', 'CMSController@delete');
Route::post('/add-home-data', 'WelcomeController@addSubscriberEmail');
Route::post('/request-trial', 'WelcomeController@requestTrial');

Route::get('/userpref', 'UserPreferencesController@index')->name("userpref");

Route::get('/save-favorite-company', 'UserPreferencesController@saveFavoriteCompany');
Route::get('/save-requested-company', 'UserPreferencesController@saveRequestedCompany');
Route::get('/delete-favorite-company', 'UserPreferencesController@deleteFavoriteCompany');
Route::get('/delete-requested-company', 'UserPreferencesController@deleteRequestedCompany');

Route::get('/platform', 'PlatformController@index')->name("platform");
Route::get('/platform-candidate-activity', 'PlatformController@indexPlatformCandidateActivity')->name("platform-candidate-activity");

Route::get('/platform-candidate', 'PlatformController@indexCandidate')->name("platform-candidate");
Route::get('/platform-hiring-manager', 'PlatformController@indexHiringManager')->name("platform-hiring-manager");

Route::get('/update-user-setting', 'UserPreferencesController@updateUserSetting');
Route::get('/delete-cms-preview-email-alert', 'CMSController@deleteCMSPreviewEmailAlert');
Route::get('/platform-api', 'PlatformController@platformApi');
Route::get('/platform-delete', 'PlatformController@deletePlatformItem');
Route::get('/platform-ban', 'PlatformController@ban');
Route::get('/platform-edit', 'PlatformController@editPlatformItem');
Route::get('/load-platform-edit', 'PlatformController@loadEditPlatformItem');
Route::get('/platform-hide', 'PlatformController@hidePlatformItem');
Route::get('/platform-remove-next-email', 'PlatformController@removeFromNextEmail');
Route::get('/platform-ban', 'PlatformController@banPlatformItem');
Route::get('/platform-approve', 'PlatformController@approvePlatformItem');

Route::get('/add-platform-item-email', 'CMSController@sendEmailToOneUser');
Route::get('/invite-colleague', 'PlatformController@inviteColleague');
Route::get('/user-favorites', 'PlatformController@getUserFavorites');
Route::get('/load-job-subtypes', 'PlatformController@getJobSubTypes');
Route::get('/load-saved-searches', 'PlatformController@loadSavedSearches');
Route::get('/load-saved-search-edit', 'PlatformController@loadSavedSearchItem');

Route::get('/saved-opening-hiring-manager', 'PlatformController@saveOpeningHiringManager');
Route::get('/delete-opening-hiring-manager', 'PlatformController@deleteOpeningHiringManager');

Route::post('/createOrUpdateSS', 'PlatformController@saveSchemeStep');
Route::get('/delete-scheme-step', 'PlatformController@deleteSchemeStep');
Route::get('/get-schemes', 'PlatformController@getSchemes');
Route::get('/get-scheme', 'PlatformController@getScheme');



Route::get('/save-search', 'PlatformController@saveSearchItem');
Route::get('/saved-search-update', 'PlatformController@updateSavedSearchItem');
Route::get('/delete-saved-search', 'PlatformController@deleteSavedSearchItem');
Route::get('/get-saved-search-items', 'PlatformController@getSavedSearchItems');
Route::get('/load-saved-search-history', 'PlatformController@loadSavedSearchHistory');
Route::get('/load-hiring-manager-history', 'PlatformController@loadHiringManagerHistory');

Route::get('/approve-approval', 'PlatformController@approveApproval');
Route::get('/edit-approval', 'PlatformController@editApproval');
Route::get('/reject-approval', 'PlatformController@rejectApproval');
Route::get('/load-approvals', 'PlatformController@loadApprovalModal');
Route::get('/add-user-favorite-item', 'PlatformController@addUserFavoriteItem');
Route::get('/lock-user/{user_id}', 'PlatformController@lockUser');
Route::get('/alert-frequency', 'PlatformController@alertFrequency');
Route::get('/search-location', 'PlatformController@searchLocation');
Route::get('/recruiter-widget/{recruiter_firm_id}/{job_type_ids}/{job_subtype_ids}/{location_ids}', 'WidgetController@recruiterWidget');
Route::get('/widget-send-email', 'WidgetController@sendWidgetEmail');


Route::post('/inbound-parse', 'InboundMailParseController@parseNewInboundMail');

Route::get('/prospect','ProspectController@getProspect');
Route::get('/prospecting_actions','ProspectController@getProspectingActions');
Route::post('/send-prospecting-direct-email','CMSController@sendProspectDirectEmail');
Route::get('/change-prospect-reachable','ProspectController@changeProspectReachable');

//Candidate

Route::get('/candidates', 'CandidateController@getCandidates');
Route::get('/favorite-candidates', 'CandidateController@getFavoriteCandidates');
Route::get('/candidateActivities', 'CandidateActivityController@getCandidateActivities');
Route::post('/add-candidate', 'CandidateController@addCandidate');
Route::get('/get-candidate', 'CandidateController@getCandidate');
Route::post('/edit-candidate', 'CandidateController@editCandidate');
Route::get('/delete-candidate', 'CandidateController@deleteCandidate');
Route::get('/delete-resume', 'CandidateController@deleteResume');
Route::get('/get-resumes', 'CandidateController@getResumes');
Route::post('/import-candidates', 'CandidateController@importCandidates');

//Hiring Manager
Route::get('/add-hiring-manager', 'HiringManagerController@addHiringManager');
Route::get('/get-hiring-manager', 'HiringManagerController@getHiringManager');
Route::get('/edit-hiring-manager', 'HiringManagerController@editHiringManager');
Route::get('/delete-hiring-manager', 'HiringManagerController@deletehiringManager');
Route::post('/import-hiring-managers', 'HiringManagerController@importHiringManagers');
//candidate activities

Route::post('/add-candidate-activity', 'CandidateActivityController@addCandidateActivity');
Route::get('/delete-candidate-activity', 'CandidateActivityController@deleteCandidateActivity');
Route::get('/get-candidate-activity', 'CandidateActivityController@getCandidateActivity');
Route::post('/edit-candidate-activity', 'CandidateActivityController@editCandidateActivity');


Route::post('/upload-file', 'CandidateController@uploadFile');
Route::get('/get-resumes', 'CandidateController@getResumes');



//hiring managers
Route::get('/hiring-managers', 'HiringManagerController@getHiringManagers');

Route::get('/bantext', 'HomeController@getBanText');
Route::get('/add-bantext', 'HomeController@addBanText');
Route::get('/delete-bantext/{id}/{type}', 'HomeController@deleteBanText');

Route::get('/jobtype-definer', 'HomeController@getJobTypeDefiner');
Route::get('/add-jobtype-word', 'HomeController@addJobTypeDefiner');
Route::get('/add-jobsubtype-word', 'HomeController@addJobSubTypeDefiner');
Route::get('/delete-jobtype-word/{id}', 'HomeController@deleteJobTypeDefiner');
Route::get('/openings', 'OpeningController@getOpenings');
Route::get('/opening-edit', 'OpeningController@editOpening');
Route::post('/save-opening-data', 'OpeningController@saveOpeningData');
Route::get('/track-user-action', 'TrackingController@trackUserAction');
Route::get('/auto-marketing', 'TrackingController@trackAutomatedMarketing');
Route::get('/send-mail-to-interaction-user', 'TrackingController@sendMailToInteractionUser');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'HomeController@getLogout');
Route::get('/test', 'HomeController@test');

Route::get('/add-company', 'HomeController@showCompanyForm');
Route::post('/get-company-data', 'HomeController@getCompanyData');
Route::get('/new-alerts', 'HomeController@getNewAlerts');
Route::post('/save-company-data', 'HomeController@saveCompanyData');


Route::get('/scraper/show_activity/{limit}/{offset}', 'ScraperCommandCenterController@showActivity');
Route::get('/scraper/get_company_to_scrape','ScraperCommandCenterController@getScrapeSessionParameters');
Route::get('/owlerScraperCommandCenter','OwlerScraperCommandCenterController@getScrapeSessionParameters');
Route::get('/indeedScraperCommandCenter','IndeedScraperController@getScrapeSessionParameters');
Route::get('/gustScraperCommandCenter','GgustScraperController@getScrapeSessionParameters');
Route::get('/startupslistCommandCenter','startupsListScraperController@getScrapeSessionParameters');
Route::get('/allstartupsCommandCenter','allstartupsScraperController@getScrapeSessionParameters');


Route::get('/companies', 'CompanyController@index');
Route::get('/company', 'CompanyController@getCompanyDetail');
Route::get('/{location}', 'WelcomeController@getLocationAlert');
Route::post('/save-user-preferences', 'UserPreferencesController@saveUserPreferences');
Route::get('/save-user-preferences-api', 'UserPreferencesController@saveUserPreferencesApi');

Route::post('/delete-cms-email-alert', 'CMSController@deleteCMSEmailAlert');
Route::post('/edit-cms-email-alert', 'CMSController@editCMSEmailAlert');




