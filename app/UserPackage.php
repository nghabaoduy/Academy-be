<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model {

	//
    protected $table = 'user_package';
    protected $fillable = ['user_id', 'package_id'];

    public function package() {
        return $this->belongsTo('App\Package', 'package_id');
    }
}
