<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use GuzzleHttp\Client;


class GetChampionInfo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'getChampionInfo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Make an API call to get champion information';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{

		$client = new Client();
		//19134540
		
		$response = $client->get('https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key='.env('RIOT_API_KEY'));
		$response2 = $client->get('https://na.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/NA1/19134540?api_key=9cbc9547-d4a7-4593-8a0d-ce8e4b5b2798');

		$this->info($response2->getStatusCode());

		//$body = $response->getBody();
		$body = $response2->getBody();
		echo $body;

		$this->comment("hallo");
	}

}
