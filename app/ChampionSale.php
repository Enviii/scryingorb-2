<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ChampionSale extends Model {

	protected $table = 'champion_sales';

	public function champion()
    {
        return $this->belongsTo('App\Champion');
    }

}
