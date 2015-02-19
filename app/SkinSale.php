<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SkinSale extends Model {

	protected $table = 'skin_sales';

	public function champion()
    {
        return $this->belongsTo('App\Champion', 'id', 'champion_id');
    }

	public function skin()
    {
        return $this->belongsTo('App\Skin', 'id', 'skin_id');
    }

}
