<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model {

	//
    protected $table = 'order_history';
    protected $fillable = ['user_id', 'order_ref', 'package_name', 'package_price', 'last_credit', 'after_buy_credit'];
}
