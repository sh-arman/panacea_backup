<?php

/*
* Company admin Dashboard ======================================================
*/
Route::group(['prefix' => 'codes'], function () {

    Route::group(['middleware' => 'checksession'], function () {
        Route::get('/', [
            'as' => 'generationPanel.login',
            'uses' => 'CodeGenerationPanelNewController@showLogin',
        ]);
        Route::post('/verifyLogin', [
            'uses' => 'CodeGenerationPanelNewController@processLogin',
        ]);
        Route::get('/verify', [
            'as' => 'generationPanel.verify',
            'uses' => 'CodeGenerationPanelNewController@showVerify',
        ]);
        Route::post('/confirmLogin', [
            'uses' => 'CodeGenerationPanelNewController@processVerify',
        ]);
        Route::post('/resend', [
            'uses' => 'CodeGenerationPanelNewController@resendLogin',
        ]);
        Route::post('generationPanel/medicines', [
            'as' => 'generation.code.medicines',
            'uses' => 'CodeGenerationPanelNewController@showMedicines',
        ]);
        Route::post('generationPanel/medicineType', [
            'as' => 'generation.code.medicineType',
            'uses' => 'CodeGenerationPanelNewController@showMedicineType',
        ]);
        Route::post('generationPanel/medicineDosage', [
            'as' => 'generation.code.medicineDosage',
            'uses' => 'CodeGenerationPanelNewController@showMedicineDosage',
        ]);
        Route::post('generationPanel/loadMore', [
            'as' => 'generation.code.loadMore',
            'uses' => 'CodeGenerationPanelNewController@showMoreData',
        ]);
        Route::post('generationPanel/loadLog', [
            'as' => 'generation.code.loadLog',
            'uses' => 'CodeGenerationPanelNewController@showMoreLog',
        ]);
        Route::post('generationPanel/searchActivityLog', [
            'as' => 'generation.code.searchActivityLog',
            'uses' => 'CodeGenerationPanelNewController@searchActivityLog',
        ]);

        Route::group(['middleware' => 'auth.codegeneration'], function () {
            Route::get('code/generate', [
                'as' => 'generationPanel.code.order',
                'uses' => 'CodeGenerationPanelNewController@showForm',
            ]);
            Route::post('code/generate', [
                'uses' => 'CodeGenerationPanelNewController@orderCode',
            ]);
            // Arman Working 2.3.2022
            Route::post('code/confirm', [
                'as' => 'generationPanel.code.confirm',
                // 'uses' => 'CodeGenerationPanelNewController@confirmOrderCode',
                'uses' => 'CodeGenerationPanelNewController@ConfrimArman',
            ]);
            Route::get('code/download/{id}', [
                'as' => 'generationPanel.code.download',
                'uses' => 'CodeGenerationPanelNewController@downloadGeneratedCsv',
            ]);
            Route::post('code/orderBack', [
                'as' => 'generationPanel.code.orderBack',
                'uses' => 'CodeGenerationPanelNewController@orderBackForConfirm',
            ]);
            Route::get('logout', [
                'as' => 'generationPanel.logout',
                'uses' => 'CodeGenerationPanelNewController@logout',
            ]);
            Route::get('order', 'CodeGenerationPanelNewController@indexOrder');

            Route::get('log', [
                'as' => 'generationPanel.log',
                'uses' => 'CodeGenerationPanelNewController@showLog',
            ]);
            Route::get('templates', [
                'as' => 'generationPanel.template',
                'uses' => 'CodeGenerationPanelNewController@showTemplate']);
            Route::post('addtemplate', [
                'uses' => 'CodeGenerationPanelNewController@addTemplate']);
            Route::get('confirmAddTemplate', [
                'uses' => 'CodeGenerationPanelNewController@confirmAddTemplate']);
            Route::get('deleteTemplate/{id}', 'CodeGenerationPanelNewController@deleteTemplate');
            Route::get('choosemenu', [
                'as' => 'generationPanel.choosemenu',
                'uses' => 'CodeGenerationPanelNewController@chooseMenu',
            ]);
            Route::get('choose/{company}', [
                'as' => 'generationPanel.choose',
                'uses' => 'CodeGenerationPanelNewController@chooseCompany',
            ]);
        });      
    });
    Route::group(['middleware' => 'auth.companyadmin'], function () {
        Route::get('logout', [
            'as' => 'generationPanel.logout',
            'uses' => 'CodeGenerationPanelNewController@logout',
        ]);
    });
});


// Local-fallback routes for development: expose OTP endpoints on localhost
Route::post('/verifyLogin', [
    'uses' => 'CodeGenerationPanelNewController@processLogin'
]);
Route::post('/confirmLogin', [
    'uses' => 'CodeGenerationPanelNewController@processVerify'
]);
Route::post('/resend', [
    'uses' => 'CodeGenerationPanelNewController@resendLogin'
]);




/*
 * Mobile site live
 */
Route::group(['domain' => 'm.panacea.live'], function () {
    Route::get('/', function () {
       $url = 'https://renata.panacea.live/';
        return Redirect::to($url);
    });
});

/*
 * Panalytics Dashboard ==========================================================================
 */
Route::group(['domain' => 'analytics.panacea.live'], function () {    
    Route::get('/', [
        'as' => 'panalytics_home',
        'uses' => 'PanalyticsController@showLanding',
    ]);
    Route::get('home', [
        'as' => 'panalytics_view',
        'uses' => 'PanalyticsController@index'
    ]);
    Route::post('panalytics_login', 'PanalyticsController@login');
    Route::post('panalytics_registration', 'PanalyticsController@registration');
    Route::get('panalytics_activation/{id}', 'PanalyticsController@activation');
    Route::post('panalytics_activation/{id}', 'PanalyticsController@processActivation');
    Route::post('panalytics_password/forgot', 'PanalyticsController@forgotPassword');
    Route::post('panalytics_password/reset', 'PanalyticsController@resetPassword');
    Route::post('stats', [
        'as' => 'stats',
        'uses' => 'PanalyticsController@analysis'
    ]);
    Route::get('Panalyticslogout', [
        'as' => 'Panalyticslogout',
        'uses' => 'PanalyticsController@processLogout',
    ]);
});

/*
* Public Routes ========================================================================================
*/
// Real homepage route
Route::get('/', [
    'as' => 'home',
    'uses' => 'FrontendController@showLanding',
]);
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
// Prefer real controller if available; otherwise provide a working fallback
// Fallbacks are disabled now that homepage is restored
Route::get('optout/{phone_number}', [
    'as' => 'optout',
    'uses' => 'FrontendController@optoutCampaign',
]);

Route::post('optout/{phone_number}', [
    'as' => 'optout',
    'uses' => 'FrontendController@optoutCampaign',
]);

Route::post('response', [
    'as' => 'response',
    'uses' => 'FrontendController@verifyCode',
]);
Route::get('response', [
    'as' => 'response',
    'uses' => 'FrontendController@verifyCode',
]);

Route::get('report', [
    'as' => 'report',
    'uses' => 'FrontendController@showReport',
]);
Route::post('reportSubmit', [
    'as' => 'submit',
    'uses' => 'FrontendController@submitReport',
]);

Route::get('press', [
    'as' => 'press',
    'uses' => 'FrontendController@showMedia',
]);

Route::get('contact', [
    'as' => 'contact',
    'uses' => 'FrontendController@showContact',
]);

Route::post('contact', [
    'as' => 'contactEmail',
    'uses' => 'FrontendController@sendEmail',
]);

Route::get('logout', [
    'as' => 'logout',
    'uses' => 'AuthController@processLogout',
]);
Route::get('legal', [
    'as' => 'legal',
    'uses' => 'FrontendController@showLegal',
]);

Route::get('faq', [
    'as' => 'faq',
    'uses' => 'FrontendController@showFaq',
]);

Route::get('platforms', [
    'as' => 'platforms',
    'uses' => 'FrontendController@platformLink',
]);
Route::get('probmodel', [
    'as' => 'probmodel',
    'uses' => 'ProbabilisticModelController@index',
]);
Route::get('digitalwarranty', [
    'as' => 'dw',
    'uses' => 'FrontendController@dw',
]);

/*
 * This is a function for sending multiple or dynamic bulk sms when necessary, so should be active only when such is needed.
Route::get('bulksms', [
    'as' => 'bulksms',
    'uses' => 'ApiController@sendBulk',
]);
*/

/*
* User Dashboard ========================================================================
*/
Route::group(['middleware' => 'auth.user', 'prefix' => 'user'], function () {
    Route::get('dashboard', [
        'as' => 'user.dashboard',
        'uses' => 'UserController@showDashboard',
    ]);
    Route::get('profile', [
        'as' => 'user.profile',
        'uses' => 'UserController@showProfile',
    ]);
    Route::get('profile/update', [
        'as' => 'user.profile.form',
        'uses' => 'UserController@showProfileForm',
    ]);
    Route::post('profile/update', [
        'as' => 'user.profile.update',
        'uses' => 'UserController@updateProfile',
    ]);
    Route::get('verify', [
        'as' => 'user.verify',
        'uses' => 'UserController@showVerifyForm',
    ]);
    Route::post('verify', 'UserController@verifyCode');
});



// Disabled Facebook Messenger routes (controllers not present in local env)
// Prevents boot errors when classes are missing
Route::get('testmessenger', [
    'as' => 'messengerTestGet',
    'uses' => 'TestController@QrTest',
]);
Route::post('testmessenger', [
    'as' => 'messengerTestPost',
    'uses' => 'TestController@QrTest',
]);

// Route::get('qrcode', [
//     'as' => 'qrget',
//     'uses' => 'TestController@qrRead'
// ]);

// Route::post('qrsubmit', [
//    'as' => 'qrpost',
//    'uses' => 'TestController@qrPost'
// ]);

//});

/*
* API Route ==============================================================
*/
Route::group(['prefix' => 'api/v1'], function () {
    Route::post('login', 'ApiController@login');
    Route::post('registration', 'ApiController@registration');
    Route::post('password/forgot', 'ApiController@forgotPassword');
    Route::post('password/reset', 'ApiController@resetPassword');
    Route::get('sms/verify', 'ApiController@verifySmsCode');
    Route::post('sms/verifytest', 'ApiController@verifytestSmsCode');
    Route::get('sms/verifytest', 'ApiController@verifytestSmsCode');
    Route::get('activate/{id}', 'ApiController@sendActivation');
    Route::post('activate/{id}', 'ApiController@processActivation');
});

Route::group(['prefix' => 'api/v2'], function () {
    Route::get('sms/verifytest', 'ApiController@verifySSLSmsCode');
});


//-----------Live Check Pro Routes by Ahmed-----------------------
Route::get('v/{code}', 'livecheckproController@urlCode');
Route::post('/codeverify', 'livecheckproController@IsValidCode');
Route::post('/phoneverify', 'livecheckproController@IsValidPhone');
Route::post('/livecheck', 'livecheckproController@LiveCheck');
Route::post('/resendcode', 'livecheckproController@ResendCode');

//-----------MUPS Live Check Pro Routes by Arman------------------
Route::get('mups', 'livecheckproControllerMups@page')->name('mups');
Route::get('mups-leaflet', 'livecheckproControllerMups@leaflet')->name('leaflet');
Route::post('/mupslivecheck', 'livecheckproControllerMups@mlivecheck')->name('mupslivecheck');

//-----------kumarika Live Check Pro Routes by Arman---------------
// Disabled Kumarika livecheck routes: controller not present locally
    
Route::get('set-locale/{locale}', function ($locale) {
    App::setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('locale.setting');