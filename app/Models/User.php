<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'uni_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function user_team(){
        return $this->hasMany(UserTeam::class, 'user_id');
    }

    public function invite(){
        return $this->hasMany(Invite::class, 'user_id');
    }

    public function question(){
        return $this->hasMany(Question::class, 'user_id');
    }

    public function isInvited($team_id){
        return $this->invite()->where('team_id', $team_id)->exists();
    }

    public function university(){
        return $this->hasOne(University::class, 'id', 'uni_id');
    }

    public function user_report(){
        return $this->hasMany(UserReport::class, 'user_id');
    }

    public function answer(){
        return $this->hasMany(Answer::class, 'user_id');
    }

}
