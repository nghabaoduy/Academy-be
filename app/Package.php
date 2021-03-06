<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model {

	//
    protected $table = 'package';
    public $timestamps = false;

    public function sets() {
        return $this->hasMany('App\Set');
    }

    public function asset() {
        return $this->belongsTo('App\Asset', 'asset_id');
    }
}
