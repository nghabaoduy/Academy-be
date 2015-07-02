<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedData extends Model
{
    protected $table = 'shared_data';
    public $timestamps = false;
    protected $fillable = ['name', 'value'];
    public function SharedData() {

    }
}
