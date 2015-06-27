<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model {

	//
    protected $table = 'user_package';
    protected $fillable = ['user_id', 'package_id', 'purchase_type', 'expired_at'];
    //public $timestamps = false;

    public function package() {
        return $this->belongsTo('App\Package', 'package_id');
    }
}
