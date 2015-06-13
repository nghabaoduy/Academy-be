<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model {

	//
    protected $table = 'set';
    public $timestamps = false;

    public function words() {
        return $this->belongsToMany('App\Word', 'set_word', 'set_id', 'word_id');
    }

    public function scores() {

    }

    public function asset() {
        return $this->belongsTo('App\Asset', 'asset_id');
    }
}
