<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model {

	//
    protected $table = 'word';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function meaningList() {
        return $this->hasMany('App\Meaning', 'word_id');
    }
}
