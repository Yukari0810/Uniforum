<?php

use App\Http\Controllers\Admin\AnswersController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\QuestionsController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\UniversitiesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AnswerLikeController;
use App\Http\Controllers\AnswerReportController;
use App\Http\Controllers\ApplyController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostscriptController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionLikeController;
use App\Http\Controllers\QuestionReportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamReportController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\UserTeamController;
use App\Http\Middleware\Admin;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/register/user', [RegisterController::class, 'store'])->name('register-user');

Route::post('/logout/user', [LogoutController::class, 'logout'])->name('logout-user');

Route::post('/login/user', [LoginController::class, 'login'])->name('login-user');


Auth::routes();

Route::group(["middleware" => "auth"], function(){
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::post('/question/store', [QuestionController::class, 'store'])->name('question.store');

    Route::get('/question/show/{q_id}', [QuestionController::class, 'show'])->name('question.show');

    Route::post('/answer/store/{q_id}', [AnswerController::class, 'store'])->name('answer.store');

    Route::post('/ps/store/{q_id}', [PostscriptController::class, 'store'])->name('ps.store');

    Route::post('/question/report/store/{q_id}', [QuestionReportController::class, 'store'])->name('question.report.store');

    Route::post('/answer/report/store/{a_id}', [AnswerReportController::class, 'store'])->name('answer.report.store');

    Route::delete('/question/delete/{q_id}', [QuestionController::class, 'destroy'])->name('question.delete');

    Route::delete('/answer/delete/{a_id}', [AnswerController::class, 'destroy'])->name('answer.delete');

    Route::post('/question/like/{q_id}', [QuestionLikeController::class, 'store'])->name('question.like.store');

    Route::delete('/question/like/delete/{q_id}', [QuestionLikeController::class, 'destroy'])->name('question.like.delete');

    Route::post('/answer/like/{a_id}', [AnswerLikeController::class, 'store'])->name('answer.like.store');

    Route::delete('/answer/like/delete/{a_id}', [AnswerLikeController::class, 'destroy'])->name('answer.like.delete');

    Route::controller(TeamController::class)->group(function () {
        Route::get('/team', 'index')->name('team');
        Route::post('/team/store', 'store')->name('team.store');
        Route::get('/team/view/{t_id}', 'view')->name('team.view');
        Route::get('/team/view/member/{t_id}', 'viewMember')->name('team.view.member');
        Route::get('/team/setting/{team}', 'setting')->name('team.setting');
        Route::get('/team/edit/{team}', 'edit')->name('team.edit');
        Route::get('/team/manage-members/{team}', 'manageMembers')->name('team.manage-members');
        Route::get('/team/invite-members/{team}', 'inviteMembers')->name('team.invite-members');
        Route::patch('/team/update/{team}', 'update')->name('team.update');
        Route::delete('/team/manage-members/kick/{team}', 'kickMember')->name('team.manage-members.kick');
        Route::patch('/team/manage-members/promote/{team}', 'promoteMember')->name('team.manage-members.promote');
        Route::patch('/team/manage-members/demote/{team}', 'demoteMember')->name('team.manage-members.demote');
        Route::delete('/team/invite-members/decline/{team}', 'declineApply')->name('team.invite-members.decline');
        Route::post('/team/invite-members/accept/{team}', 'acceptApply')->name('team.invite-members.accept');
        Route::get('/team/invite-members/search/{team}', 'inviteSearch')->name('team.invite-members.search');
        Route::post('/team/invite-members/invite/{team}', 'invite')->name('team.invite-members.invite');
        Route::post('/team/accept-invite', 'acceptInvite')->name('team.acceptInvite');
        Route::post('/team/decline-invite', 'declineInvite')->name('team.declineInvite');
        Route::delete('/team/delete/{team}', 'delete')->name('team.delete');
        Route::get('/team/search', 'searchTeam')->name('team.search');
        Route::get('/team/give-ownership/view/{team}', 'viewOwnership')->name('team.give-ownership.view');
        Route::patch('/team/give-ownership/give/{team}', 'giveOwnership')->name('team.give-ownership.give');
    });

    Route::post('/team/report/store/{t_id}', [TeamReportController::class, 'store'])->name('team.report.store');

    Route::get('/profile/view/{user_id}', [UserController::class, 'view'])->name('profile.view');

    Route::post('/user/report/store/{user_id}', [UserReportController::class, 'store'])->name('user.report.store');

    Route::get('/profile/myanswer/{user_id}', [UserController::class, 'myAnswer'])->name('profile.myanswer');

    Route::get('/profile/myteam/{user_id}', [UserController::class, 'myTeam'])->name('profile.myteam');

    Route::get('/profile/edit/{detail}', [UserController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile/update', [UserController::class, 'update'])->name('profile.update');

    Route::post('/team/join', [UserTeamController::class, 'join'])->name('team.join');

    Route::post('/team/apply', [ApplyController::class, 'apply'])->name('team.apply');

    Route::delete('/team/leave', [UserTeamController::class, 'leave'])->name('team.leave');

    Route::post('/question/ask-team', [QuestionController::class, 'askTeam'])->name('question.ask-team');

    Route::patch('/setting/change-password', [UserController::class, 'changePassword'])->name('setting.change-password');

    Route::get('/setting', [UserController::class, 'setting'])->name('setting');

    Route::patch('/setting/change-email', [UserController::class, 'changeEmail'])->name('setting.change-email');

    Route::patch('/setting/change-university', [UserController::class, 'changeUniversity'])->name('setting.change-university');

    Route::delete('/setting/delete-account', [UserController::class, 'deleteAccount'])->name('setting.delete-account');

    Route::get('/search', [HomeController::class, 'searchQuestion'])->name('search.question');

    Route::middleware([Admin::class])->group(function () {
        Route::get('/super-admin', [UsersController::class, 'index'])->name('super-admin');
        Route::patch('/super-admin/users/activate/{user_id}', [UsersController::class, 'activate'])->name('super-admin.users.activate');
        Route::delete('/super-admin/users/deactivate/{user_id}', [UsersController::class, 'deactivate'])->name('super-admin.users.deactivate');
        Route::get('/super-admin/questions', [QuestionsController::class, 'index'])->name('super-admin.questions');
        Route::patch('/super-admin/questions/activate/{q_id}', [QuestionsController::class, 'activate'])->name('super-admin.questions.activate');
        Route::delete('/super-admin/questions/deactivate/{q_id}', [QuestionsController::class, 'deactivate'])->name('super-admin.questions.deactivate');
        Route::get('/super-admin/answers', [AnswersController::class, 'index'])->name('super-admin.answers');
        Route::patch('/super-admin/answers/activate/{a_id}', [AnswersController::class, 'activate'])->name('super-admin.answers.activate');
        Route::delete('/super-admin/answers/deactivate/{a_id}', [AnswersController::class, 'deactivate'])->name('super-admin.answers.deactivate');
        Route::get('/super-admin/teams', [TeamsController::class, 'index'])->name('super-admin.teams');
        Route::patch('/super-admin/teams/activate/{team_id}', [TeamsController::class, 'activate'])->name('super-admin.teams.activate');
        Route::delete('/super-admin/teams/deactivate/{team_id}', [TeamsController::class, 'deactivate'])->name('super-admin.teams.deactivate');
        Route::get('/super-admin/categories', [CategoriesController::class, 'index'])->name('super-admin.categories');
        Route::patch('/super-admin/categories/update/{category_id}', [CategoriesController::class, 'update'])->name('super-admin.categories.update');
        Route::delete('/super-admin/categories/delete/{category_id}', [CategoriesController::class, 'delete'])->name('super-admin.categories.delete');
        Route::post('/super-admin/categories/store', [CategoriesController::class, 'store'])->name('super-admin.categories.store');
        Route::get('/super-admin/users/report/{user_id}', [UsersController::class, 'report'])->name('super-admin.users.report');
        Route::get('/super-admin/questions/report/{q_id}', [QuestionsController::class, 'report'])->name('super-admin.questions.report');
        Route::get('/super-admin/answers/report/{a_id}', [AnswersController::class, 'report'])->name('super-admin.answers.report');
        Route::get('/super-admin/teams/report/{team_id}', [TeamsController::class, 'report'])->name('super-admin.teams.report');
        Route::get('/super-admin/universities', [UniversitiesController::class, 'index'])->name('super-admin.universities');
        Route::patch('/super-admin/universities/update/{university_id}', [UniversitiesController::class, 'update'])->name('super-admin.universities.update');
        Route::delete('/super-admin/universities/delete/{university_id}', [UniversitiesController::class, 'delete'])->name('super-admin.universities.delete');
        Route::post('/super-admin/universities/store', [UniversitiesController::class, 'store'])->name('super-admin.universities.store');
    });

});

Route::post('/customer-support', [CustomerSupportController::class, 'store'])->name('customer-support');


