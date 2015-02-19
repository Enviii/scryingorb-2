<?php namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		//$champions = Champion::all();
		
		$champions = \App\Champion::where('id', '=', 1)->get();

		//list 3 champs on sale next?
		$champsOnSale = \App\ChampionSale::take(3)->get();

		return view('home')->with('champions', $champions)->with('champsOnSale', $champsOnSale);

	}

}
