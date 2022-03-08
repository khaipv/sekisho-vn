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
//Route::post('/showCitiesInCountry','ProvinceController@showCitiesInCountry');
Route::get('/division123',"DivisionNewController@index");


Route::post('/showWard','ProvinceController@showWard');
Route::post('/showDistrict','ProvinceController@showDistrict');
Route::post('/showDivision','ProvinceController@showDivision');
Route::post('/showAddressByDiv','ProvinceController@showAddressByDiv');
Route::post('/showKind','ProvinceController@showKind');


Route::get('/', "CandidateController@canSearch1");
Route::get('logined', function () {
    return view('logined');
});
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('foo', 'OrderCandiController@orderCandiSearch');
Route::resource('client', 'ClientControler');
Route::resource('division', 'DivisionController');
Route::resource('pic', 'PicController');
Route::resource('order', 'OrderController'); 
Route::resource('ordercandi', 'OrderCandiController'); 
Route::resource('candidate', 'CandidateController');
Route::resource('profile', 'ProfileController');
Route::resource('master', 'MasterController');
Route::resource('education', 'EduController');
Route::resource('hisjob', 'HisjobController');
Route::resource('action', 'ActionController');

Route::resource('dailyreport', 'DailyReportController');


Route::get("addnewpic","PicController@addnew");
Route::get("my-search","ClientControler@mySearch");
Route::get("orderSearch","OrderController@orderSearch");
Route::get("picSearch","PicController@picSearch");
Route::get("divisionSearch","DivisionController@divisionSearch");  
Route::get("masterSearch","MasterController@masterSearch"); 
Route::get("addnew","DivisionController@addnew");
Route::get("canSearch","CandidateController@canSearch");
Route::get("canSearch1","CandidateController@canSearch1");
Route::get("editProfile","ProfileController@editProfile");
Route::get("reportSearch","DailyReportController@reportSearch");  
Route::get('updateOrderCandi', 'OrderCandiController@updateOrderCandi');
Route::get('orderCandiSearch', 'OrderCandiController@orderCandiSearch');
Route::get("addNewEdu","EduController@addnew");
Route::get("editEdu","EduController@editEdu");
Route::get("addNewJob","HisjobController@addnew");
Route::get("editJob","HisjobController@editJob");
Route::get("addAction","ActionController@addnew");
Route::get("editAction","ActionController@editnew");


Route::get('clone/{squirrel}',"OrderController@clone");
Route::get('ordDelete/{squirrel}',"OrderCandiController@destroyCandi");
Route::get('check/{prisw}/{secsw}', 'PicController@check');
Route::resource('clients', 'Showclient');
Route::get('my-form','HomeController@showForm');
Route::get('form-validation', 'HomeController@formValidation');
Route::post('form-validation', 'HomeController@formValidationPost');


Route::get('importExport', 'CSVController@importExport');
// Route for export/download tabledata to .csv, .xls or .xlsx
Route::get('downloadExcel/{type}/{kind}', 'CSVController@downloadExcel');
// Route for import excel data to database.
Route::post('importExcel', 'CSVController@importExcel');
Route::get('downloadCandidate/{type}/{kind}', 'CSVController@downloadCandidate');
Route::get('downloadDiv/{type}/{kind}', 'CSVController@downloadExcelDiv');
Route::get('downloadOrder/{type}/{kind}', 'CSVController@downloadExcelOrder');


//Route::get("order-search/{id}","OrderController@orderSearch");
Route::resource('leave', 'LeaveController');
Auth::routes();

Route::get('/home', "CandidateController@canSearch1");








// Timekeeper
Route::post('/appAproveMan', 'ApproveController@appAproveMan');

Route::get('/approveSearch', 'ApproveController@approveSearch');
Route::post('/showOT','OvertimeController@showOT');
Route::post('/createOT','OvertimeController@createOT');
Route::resource('profile', 'ProfileController');
Route::resource('usrMaster', 'USRController');
Route::resource('usrAMaster', 'USRAController');

Route::resource('detail', 'DetailController');
Route::resource('master', 'MasterController');

Route::post('/createLeave','OvertimeController@createLeave');
Route::post('/createHO','OvertimeController@createHO');
Route::post('/createDayOff','OvertimeController@createDayOff');
Route::post('/dayoffSearch','OvertimeController@dayoffSearch');
Route::get('/appSearch','ApplicationController@appSearch');
Route::get('/user/projects/{id}/{type}', 'ApplicationController@deleteOT');
Route::get('/appEdit/{id}/{type}', 'ApplicationController@editApp');
Route::get('/appAprove/{id}/{type}/{idType}/{idlist}/{typedblist}/{message}', 'ApproveController@appAprove');
Route::get('/appAproveStaff/{id}/{type}/{idType}/{idlist}/{typedblist}/{message}', 'ApplicationController@appAprove');
Route::get('/appBack', 'ApproveController@appBack');
Route::get('/appDetail/{id}/{type}', 'DetailController@appDetail');
Route::get("editProfile","ProfileController@editProfile");
Route::get("usrSearch","USRController@usrSearch"); 
Route::get("usrASearch","USRAController@usrSearch"); 

Route::post('/editOT','ApplicationController@editOT');
Route::post('/appManager','ApproveController@appManager');
Route::get('/downloadPDF','TimeController@downloadPDF');
Route::get("masterSearch","MasterController@masterSearch"); 




Route::get('/leaveDetail', function () {
    return view('overtime.detailLeave');
});
 
///
Route::resource('time', 'TimeController');
Route::resource('overtime', 'OvertimeController');
Route::resource('dayoff', 'DayoffController');
Route::resource('approve', 'ApproveController');
Route::resource('application', 'ApplicationController');
Route::resource('udp', 'UDPController');
Route::resource('timeMan', 'TimeManController');


Route::get('updatePrint', 'UDPController@updatePrint');
////
Route::get('updateOT', 'OvertimeController@updateOT');
//Route::get('updateOT', 'OvertimeController@updateOT');
Route::get('importExport', 'CSVController@importExport');
// Route for export/download tabledata to .csv, .xls or .xlsx
Route::get('downloadExcel/{type}', 'CSVController@downloadExcel');
// Route for import excel data to database.
Route::post('importExcel', 'CSVController@importExcel');
//Search TimeSheet
Route::get("timeSearch","TimeController@timeSearch");
Route::get("apptimeSearch","TimeManController@timeSearch");
Route::get("otDaySearch","UDPController@otDaySearch");

Route::get('/product', function () {
    return view('layouts.home');
});
Route::get('/product', function () {
    return view('layouts.product');
});
Route::get('/news', function () {
    return view('layouts.news');
});
Route::get('/contact', function () {
    return view('layouts.contact');
});