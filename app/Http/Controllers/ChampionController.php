<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChampionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//$champions = \App\Champion::get();

		/*foreach ($champions as $key => $value) {
			//$value->$name = preg_replace('/[^A-Za-z0-9\-]/', '', $value->name);
			$value->name = preg_replace('/[^A-Za-z0-9\-]/', '', $value->name);
			$value->name = ucfirst(strtolower($value->name));
		}*/

		//check if file exist that contains json response for skin ids, names etc
		if (\Storage::exists('static-champData-images.json')) {

			//make sure file is not null or missing data
			$size = \Storage::size('static-champData-images.json');
			if ($size <= 500) {
				//rerun curl to get json response and save to file
				$json = $this->getChampionImageAPI();
			} else {
				//fetch file, decode
				$champData = \Storage::get('static-champData-images.json');
		    	$json = json_decode($champData, true);
			}

		} else {
			$json = $this->getChampionImageAPI();
		}

		return view('all_champions')->with('champions', $json);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string $champion
	 * @return Response
	 */
	public function show($champion)
	{
		
		/*$championQuery = \App\Champion::with('skins')->where('name', '=', $champion)->get();
		$skin = \App\Skin::with('champion')->where('champion_name', '=', $champion)->get();*/

		//check if file exist that contains json response for skin ids, names etc
		if (\Storage::exists('static-champData-skins.json')) {

			//make sure file is not null or missing data
			$size = \Storage::size('static-champData-skins.json');
			if ($size <= 10000) {
				//rerun curl to get json response and save to file
				$json = $this->getChampionDataAPI();
			} else {
				//fetch file, decode
				$champData = \Storage::get('static-champData-skins.json');
		    	$json = json_decode($champData, true);
			}

		} else {
			$json = $this->getChampionDataAPI();
		}

		//array to store data for $champion
		$skinsArray = array();

		//loop to find matching $champion and store $value in $skinsArray
		foreach ($json['data'] as $key => $value) {
			if ($key == $champion) {
				$skinsArray[] = $value;
			}
		}

		return view('champion')->with('champion', $skinsArray);
	}

	/**
	 * Run API call to static-data for champion skins data. Store data in file /storage/app
	 * @return json
	 */
	public function getChampionDataAPI() {
		//https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?dataById=true&champData=skins&api_key=
		//https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?champData=skins&api_key=
		$getAllSkinsURL = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?dataById=true&champData=skins&api_key=".env('RIOT_API_KEY');
		$client = new Client();
		$res = $client->get($getAllSkinsURL);
		$json = $res->json();

		//https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?champData=image&api_key=

		//\Debugbar::info($json);
		\Storage::put('static-champData-skins.json', json_encode($json));

		return $json;
	}

	/**
	 * Run API call to static-data for champion images. Store in /storage/app
	 * @return json
	 */
	public function getChampionImageAPI() {
		$getAllSkinsURL = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?champData=image&api_key=".env('RIOT_API_KEY');
		$client = new Client();
		$res = $client->get($getAllSkinsURL);
		$json = $res->json();

		//\Debugbar::info($json);
		\Storage::put('static-champData-images.json', json_encode($json));

		return $json;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
