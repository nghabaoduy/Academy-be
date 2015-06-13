<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Meaning extends Model {

	//
    protected $table = 'meaning';
    protected $fillable = ['language', 'content', 'word_id', 'word_sub_dict'];
    public $timestamps = false;
}
