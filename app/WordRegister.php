<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WordRegister extends Model {

	//
    protected $table = 'word_register';

    protected $fillable = ['name', 'freelancer_name'];
}
