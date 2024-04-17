<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/view', function () {
    return view('user.view-question');
})->name('view');

Route::get('/profile', function () {
    return view('user.profile.index');
})->name('profile');

Route::get('/profile/myanswer', function () {
    return view('user.profile.my-answer');
})->name('profile.myanswer');

Route::get('/profile/myteam', function () {
    return view('user.profile.my-team');
})->name('profile.myteam');

Route::get('/profile/edit', function () {
    return view('user.profile.edit');
})->name('profile.edit');

Route::get('/team', function () {
    return view('user.team.index');
})->name('team');

Route::get('/team/view', function () {
    return view('user.team.view');
})->name('team.view');

Route::get('/team/view/member', function () {
    return view('user.team.view-member');
})->name('team.view.member');

Route::get('/team/setting', function () {
    return view('user.team.setting');
})->name('team.setting');

Route::get('/team/edit', function () {
    return view('user.team.edit');
})->name('team.edit');

Route::get('/team/manageMembers', function () {
    return view('user.team.manage-members');
})->name('team.manage');

Route::get('/team/inviteMembers', function () {
    return view('user.team.invite-members');
})->name('team.invite');

Route::get('/team/inviteMembers/result', function () {
    return view('user.team.invite-search-result');
})->name('team.invite.result');

Route::get('/setting', function () {
    return view('user.setting.index');
})->name('setting');

Route::get('/result', function () {
    return view('search-result');
})->name('result');

Route::get('/super-admin', function () {
    return view('super-admin.users.index');
})->name('super-admin');

Route::get('/super-admin/questions', function () {
    return view('super-admin.questions.index');
})->name('super-admin.questions');

Route::get('/super-admin/answers', function () {
    return view('super-admin.answers.index');
})->name('super-admin.answers');

Route::get('/super-admin/teams', function () {
    return view('super-admin.teams.index');
})->name('super-admin.teams');

Route::get('/super-admin/categories', function () {
    return view('super-admin.categories.index');
})->name('super-admin.categories');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/register/user', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register-user');

Route::get('/super-admin/questions/report', function () {
    return view('super-admin.questions.report');
})->name('super-admin.questions.report');

Route::get('/super-admin/teams/report', function () {
    return view('super-admin.teams.report');
})->name('super-admin.teams.report');

Route::get('/super-admin/users/report', function () {
    return view('super-admin.users.report');
})->name('super-admin.users.report');

Route::get('/super-admin/answers/report', function () {
    return view('super-admin.answers.report');
})->name('super-admin.answers.report');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

