<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string title
 * @property string content
 * @property string author
 */
class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'author', 'content'
    ];

    /**
     * @return bool
     */
    public function validate() {
        return true;
    }

    /**
     * @param array $options
     * @return bool|void
     */
    public function save(array $options = []) {
        if ($this->validate()) {
            parent::save();
        }
    }
}