<?php

namespace App\Models;
use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type',
        'email_verified_at'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


     protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];


    public function getAvatarAttribute()
    {
        return $this->attributes['photo'];
    }
    public function getFullnameAttribute()
    {
        $fullname = $this->attributes['firstname'] . ' ';
        
        // Check if middlename exists and is not empty
        if (!empty($this->attributes['middlename'])) {
            $fullname .= $this->generateMiddleinitial($this->attributes['middlename']). '. ';
        }
        
        $fullname .= $this->attributes['lastname'];
        
        return $fullname;
    }
    public function getMiddleinitialAttribute()
    {
        // Assuming 'middlename' is the attribute for the user's middle name
        // Adjust the attribute name as needed
        $middlename = $this->attributes['middlename'];

        if (!empty($middlename )) {
            // Extract the first character of the middlename and convert it to uppercase
            return strtoupper($this->generateMiddleinitial($middlename )) . '.';
        }

        return null;
    }
    public function generateMiddleinitial($middlename){
        // Take the first character of the middlename as the middle initial
        $middleInitial = substr($middlename, 0, 1);
        return $middleInitial;
    
    }
}
