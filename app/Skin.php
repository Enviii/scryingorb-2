<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Skin extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'skins';

	public function champion()
    {
        return $this->belongsTo('App\Champion');
    }


}
