<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['question', 'answer', 'questionnaire_id','help'];

    /**
     * Relationship with Questionnaire
     * One to Many Inverse
     */
    public function questionnaire()
    {
        return $this->belongsToMany(Questionnaire::class);
    }
}
