<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('signin.login');
});

Route::get('searchEmployee', function () {
    return view('employee.searchEmployee');
});

Route::get('searchEmployeeResult', function () {
    return view('employee.searchEmployeeResult');
});

//zz
Route::get('/allParts', 'MeetingRoomController@mngPartsIndex');
Route::get('/vehicle/list','ControllerVehicle@listAll');
Route::get('/vehicle/view_details/{id}','ControllerVehicle@viewDetail');
Route::get('/vehicle/add_new','ControllerVehicle@addNewRenderForm');
Route::post('/vehicle/add_new_save','ControllerVehicle@addNewSaveToDB');

//booking meeting room

Route::get('/roomPicker', 'MeetingRoomController@roomPickerIndex');
Route::get('/dateTimePicker', function () {
    return view('bookingMeetingRoom.dateTimePicker');
});
Route::get('/bookingMeetingRoom3', function () {
    return view('bookingMeetingRoom.bookingDetails');
});
Route::get('/mngMRsPopup', function () {
    return view('bookingMeetingRoom.mngMRsPopup');
});
Route::get('/manageMeetingRooms', 'MeetingRoomController@mngMRsIndex');
Route::get('/mngMRsPopupSubmit', 'MeetingRoomController@mngMRsInsert');
Route::get('/mngMRsDelete/{id}', 'MeetingRoomController@mngMRsDelete');
//Route::get('dateTimePicker/next', 'MeetingRoomController@dateTimePickerNext');
//Route::get('dateTimePicker/previous', 'MeetingRoomController@dateTimePickerPrevious');
Route::get('/dateTimePicker/{nextFirst}', 'MeetingRoomController@dateTimePickerNext');
Route::get('/dateTimePicker/{previousFirst}', 'MeetingRoomController@dateTimePickerPrevious');
Route::get('/dateTimePickerIndex/{mr_id}', 'MeetingRoomController@dateTimePickerIndex');
//dateTimePickerSave($mr_id, $date, $time_session, $empid)
Route::get('/dateTimePickerSave/{bookingBufferItem}', 'MeetingRoomController@dateTimePickerSave');
Route::get('/bookingDetailsIndex', 'MeetingRoomController@bookingDetailsIndex');
Route::get('/bookingDPrev', 'MeetingRoomController@bookingDPrev');
Route::get('/bookingDNext', 'MeetingRoomController@bookingDNext');

//announcements:
Route::get('/announcements', 'AnnouncemtsController@anncIndex');
Route::get('/manageAnnc', 'AnnouncemtsController@mngAnncIndex');
Route::get('/mngAnncSubmit', 'AnnouncemtsController@mngAnncInsert');
Route::get('/mngAnncDelete/{annc_id}', 'AnnouncemtsController@mngAnncDelete');


Route::get('/signup', 'SignupController@index');
Route::post('/signup', 'SignupController@store');

