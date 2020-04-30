<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Relationship with User
     * One to One Inverse
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Questions
     * One to Many
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
