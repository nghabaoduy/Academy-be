<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SetScore extends Model {

	//
    protected $table = 'set_score';

    protected $fillable = ['set_id', 'user_id', 'score'];
}
