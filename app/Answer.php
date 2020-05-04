<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['answer', 'question_id'];

    /**
     * Relationship with Question
     * One to Many Inverse
     */
    public function question()
    {
        return $this->belongsToMany(Question::class);
    }
}
