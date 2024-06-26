<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReportCategory;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserTeam;
use App\Models\Team;
use App\Models\University;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;
    private $report_category;
    private $question;
    private $answer;
    private $user_team;
    private $team;
    private $university;

    public function __construct(User $user, ReportCategory $report_category, Question $question, Answer $answer, UserTeam $user_team, Team $team, University $university){
        $this->team = $team;
        $this->user = $user;
        $this->report_category = $report_category;
        $this->question = $question;
        $this->answer = $answer;
        $this->user_team = $user_team;
        $this->university = $university;
    }

    public function view($user_id){
        $detail = $this->user->findOrFail($user_id);
        $report_categories = $this->report_category->get();
        $my_questions = $this->question->where('user_id', $user_id)->latest()->paginate(5);

        return view('user.profile.index')->with('detail', $detail)->with('report_categories', $report_categories)->with('my_questions', $my_questions);
    }

    public function edit(User $detail){
        return view('user.profile.edit')->with('detail', $detail);
    }

    public function myAnswer($user_id){
        $detail = $this->user->findOrFail($user_id);
        $report_categories = $this->report_category->get();
        $my_answers = $this->answer->where('user_id', $user_id)->latest()->paginate(5);

        return view('user.profile.my-answer')->with('detail', $detail)->with('report_categories', $report_categories)->with('my_answers', $my_answers);
    }

    public function myTeam($user_id){
        $detail = $this->user->findOrFail($user_id);
        $report_categories = $this->report_category->get();
        $deleted_teams = $this->team->onlyTrashed()->pluck('id')->toArray();
        $my_teams = $this->user_team->where('user_id', $user_id)->paginate(5);
        foreach($my_teams as $key => $my_team){
            if(in_array($my_team->team_id, $deleted_teams)){
                unset($my_teams[$key]);
            }
        }

        return view('user.profile.my-team')->with('detail', $detail)->with('report_categories', $report_categories)->with('my_teams', $my_teams);
    }

    public function update(Request $request){
        if($request->owner_id == Auth::user()->id){
            $request->validate([
                'update_username' => 'required|string|min:1|max:50',
                'update_avatar' => 'image|mimes:jpeg,png,jpg,gif|max:1048',
                'update_introduction' => 'max:100'
            ],
            [
                'update_username.required' => 'Please write the username.',
            ]);

            $update = $this->user->findOrFail(Auth::user()->id);
            $update->username = $request->update_username;
            $update->introduction = $request->update_introduction;
            if($request->update_avatar){
                $update->avatar = 'data:image/' . $request->update_avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->update_avatar));
            }

            $update->save();
        }

        return redirect()->route('profile.view', $request->owner_id);
    }

    public function changePassword(Request $request){
        $request->validate([
            'old_password' => 'required|string|min:1|max:50',
            'new_password' => 'required|string|min:1|max:50',
            'confirm_password' => 'required|string|min:1|max:50',
        ],
        [
            'old_password.required' => 'Please enter your old password.',
            'new_password.required' => 'Please enter your new password.',
            'confirm_password.required' => 'Please confirm the new password.',
        ]);

        $update = $this->user->findOrFail(Auth::user()->id);

        if(password_verify($request->old_password, Auth::user()->password) && $request->new_password == $request->confirm_password){
            $update->password = password_hash($request->new_password, PASSWORD_DEFAULT);
            $update->save();
            Auth::logout();
            return redirect()->route('login');
        }else{
            return redirect()->back()->with('warning_password','Any of the above is wrong. Please type correct password again.');
        }
    }

    public function setting(){
        $universities = $this->university->get();

        return view('user.setting.index')->with('universities', $universities);
    }

    public function changeUniversity(Request $request){
        $request->validate([
            'new_uni' => 'required',
        ],
        [
            'new_uni.required' => 'Please select your new university.',
        ]);

        $update = $this->user->findOrFail(Auth::user()->id);
        $update->uni_id = $request->new_uni;
        $update->save();
        return redirect()->route('home');
    }

    public function changeEmail(Request $request){
        $request->validate([
            'new_email' => 'required|email|min:1|max:50|unique:users,email',
            'confirm_email' => 'required|email|min:1|max:50',
        ],
        [
            'new_email.required' => 'Please enter your new email.',
            'confirm_email.required' => 'Please confirm your new email.',
        ]);

        $update = $this->user->findOrFail(Auth::user()->id);

        if($request->new_email == $request->confirm_email){
            $update->email = $request->new_email;
            $update->save();
            Auth::logout();
            return redirect()->route('login');
        }else{
            return redirect()->back()->with('warning_email','Any of the above is wrong. Please type correct email again.');
        }
    }

    public function deleteAccount(Request $request){
        $request->validate([
            'password' => 'required',
        ],
        [
            'password.required' => 'Please enter your password.',
        ]);

        if(password_verify($request->password, Auth::user()->password)){

            if($this->user_team->where('user_id', Auth::user()->id)->where('role', 1)->exists()){
                $owning_teams = $this->user_team->where('user_id', Auth::user()->id)->where('role', 1)->get();
                $deleted_teams = $this->team->onlyTrashed()->pluck('id')->toArray();
                foreach($owning_teams as $key => $owning){
                    if(in_array($owning->team_id, $deleted_teams)){
                        unset($owning_teams[$key]);
                    }
                }
                // dd(count($owning_teams));

                if(count($owning_teams) > 0){
                    return redirect()->back()->with('warning_owner', $owning_teams);
                }
            }
            $this->user->destroy(Auth::user()->id);
            return redirect()->route('login');
        }else{
            return redirect()->back()->with('warning_delete','Wrong assword. Please type correct password again.');
        }
    }


}
