<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['question', 'questionnaire_id'];

    /**
     * Relationship with Questionnaire
     * One to Many Inverse
     */
    public function questionnaire()
    {
        return $this->belongsToMany(Questionnaire::class);
    }

    /**
     * Relationship with Answers
     * One to Many
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
