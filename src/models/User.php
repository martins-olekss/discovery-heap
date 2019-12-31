<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function validate() {

        if (empty($this->email)) {
            return false;
        }
        if (empty($this->password)) {
            return false;
        }

        return true;
    }

    public function save(array $options = []) {
        if ($this->validate()) {
            parent::save();
        }
    }
}