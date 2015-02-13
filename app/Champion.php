<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'champions';

	public function skins()
    {
        return $this->hasMany('App\Skin');
    }

}
