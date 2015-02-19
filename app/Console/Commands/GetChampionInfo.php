<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;


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

		$today = new \DateTime("today");
		$today = $today->format("Y-m-d");

		$urlStartDate = new \DateTime("today");
		$urlStartDate->add(new \DateInterval('P1D'));
		$urlShortStartDate = $urlStartDate->format("md");
		$urlFullStartDate = $urlStartDate->format("Y-m-d");
		//echo $urlShortStartDate." ".$urlFullStartDate."<br>";

		$urlEndDate = new \DateTime("today");
		$urlEndDate->add(new \DateInterval('P4D'));
		$urlShortEndDate = $urlEndDate->format("md");
		$urlFullEndDate = $urlEndDate->format("Y-m-d");
		//echo $urlShortEndDate." ".$urlFullEndDate."<br>";
		//
		//echo $urlShortStartDate." ".$urlShortEndDate;

		//$url="http://na.leagueoflegends.com/en/news/store/sales/champion-and-skin-sale-0217-0220";
		$url="http://na.leagueoflegends.com/en/news/store/sales/champion-and-skin-sale-".$urlShortStartDate."-".$urlShortEndDate;

		//$client = new GuzzleHttp\Client();
		$res = $client->get($url);

		// Create DOM from URL or file
		$html = HtmlDomParser::str_get_html($res->getBody());

		//$skins = array();
		$champions = array();

		// Find all images 
		foreach($html->find('img') as $element) :
		   	preg_match('@^(?:http://)(\w+)-(\w+)-(\w+).(\w+).(\w+).(\w+)/(\w+)/(\w+)/(\w+)/([a-zA-Z]+)_([a-zA-Z]+)_([a-zA-Z]+)_([a-zA-Z]+).(\w+)@i', $element->src, $matches);
			if ($matches) {
				$skins[$matches[10]] = $matches;
			}

			preg_match('@^(?:http://)(\w+).(\w+).(\w+)/(\w+)/(\w+)/(\w+)/(\w+)/([a-zA-Z]+)@i', $element->src, $matches2);
			if ($matches2) {
				$champions[$matches2[8]] = $matches2;
			}
		endforeach;

		function getSkins($html, $today, $urlFullEndDate, $urlFullStartDate) {

			//find prices by parsing html body
			foreach($html->find('div[class=field-type-text-with-summary]') as $element) :
				$skin1Name = $element->children(5)->plaintext;
				$skin2Name = $element->children(8)->plaintext;
				$skin3Name = $element->children(11)->plaintext;
				//preg_match_all('/(\d{3})/', $element->plaintext, $matches3);
				preg_match_all('/(\d+)/', $element->plaintext, $matches3);
			endforeach;

			//delete last element which is a duplicate
			array_pop($matches3);

			$skinPriceArray = array(260, 520, 975, 487, 1350, 675, 750, 375);
			//make sure full_price and sale_price match the $skinPriceArray;

			//set arrays
			$skin1 = array('name'=>$skin1Name, 'full_price'=>$matches3[0][1], 'sale_price'=>$matches3[0][2]);
			$skin2 = array('name'=>$skin2Name, 'full_price'=>$matches3[0][3], 'sale_price'=>$matches3[0][4]);
			$skin3 = array('name'=>$skin3Name, 'full_price'=>$matches3[0][5], 'sale_price'=>$matches3[0][6]);

			//combine arrays
			$skinsOnSale = array($skin1, $skin2, $skin3);

			print_r($skinsOnSale);

			$int0 = 0;
			$int1 = 1;

			foreach ($skinsOnSale as $key => $value) {
				//SELECT id, champion_id FROM skins WHERE skin=?
				$getSkinID = \App\Skin::where('skin_name', '=', $value['name'])->take(1)->get();

				foreach ($getSkinID as $key2 => $value2) {

					$count = \App\SkinSale::where('skin_id', '=', $value2->id)->where('start_date', '=', $urlFullStartDate)->where('end_date', '=', $urlFullEndDate)->get();

					if (!count($count)) {
						$skinInsert = new \App\SkinSale;
						//$skinInsert->name = $value['name'];
						$skinInsert->original_price = $value['full_price'];
						$skinInsert->sale_price = $value['sale_price'];
						$skinInsert->start_date = $urlFullStartDate;
						$skinInsert->end_date = $urlFullEndDate;
						$skinInsert->champion_id = $value2->champion_id;
						$skinInsert->skin_id = $value2->id;
						$skinInsert->active = 0;
						$skinInsert->save();
					}
				}
			}

			return array($matches3, $skinsOnSale);
		}

		function getChampions($champions, $html, $today, $urlFullEndDate, $urlFullStartDate) {

			//find prices by parsing html body
			foreach($html->find('div[class=field-type-text-with-summary]') as $element) :
				$skin1Name = $element->children(5)->plaintext;
				$skin2Name = $element->children(8)->plaintext;
				$skin3Name = $element->children(11)->plaintext;
				preg_match_all('/(\d+)/', $element->plaintext, $matches3);
			endforeach;

			//delete last element which is a duplicate
			array_pop($matches3);

			//match html champion name (without spaces, special chars ex DrMundo) to db champion name
			$queryChampions = array();
			foreach ($champions as $key => $value) {
				
				$results = \DB::select("SELECT DISTINCT champion_name, champion_id 
					FROM skins 
					WHERE REPLACE(`champion_name`, '. ', '') = ? OR REPLACE(`champion_name`, ' ', '') = ? OR REPLACE(`champion_name`, ' \'', '') = ?", 
					array($value[8], $value[8], $value[8])
				);

				foreach ($results as $key => $value) {
					$queryChampions[] = $value->champion_name;
				}
			}

			//add to array
			$champion1 = array('name'=>$queryChampions[0], 'full_price'=>$matches3[0][7], 'sale_price'=>$matches3[0][8]);
			$champion2 = array('name'=>$queryChampions[1], 'full_price'=>$matches3[0][9], 'sale_price'=>$matches3[0][10]);
			$champion3 = array('name'=>$queryChampions[2], 'full_price'=>$matches3[0][11], 'sale_price'=>$matches3[0][12]);

			//combine
			$championsOnSale = array($champion1, $champion2, $champion3);

			print_r($championsOnSale);

			foreach ($championsOnSale as $key => $value) {

				$getChampID = \App\Champion::where('name', '=', $value['name'])->take(1)->get();
				
				foreach ($getChampID as $key2 => $value2) {

					$count = \App\ChampionSale::where('champion_id', '=', $value2->id)->where('start_date', '=', $urlFullStartDate)->where('end_date', '=', $urlFullEndDate)->get();
					
					//echo $count."<br>";

					if (!count($count)) {
						$championInsert = new \App\ChampionSale;
						//$championInsert->name = $value['name'];
						$championInsert->original_price = $value['full_price'];
						$championInsert->sale_price = $value['sale_price'];
						$championInsert->start_date = $urlFullStartDate;
						$championInsert->end_date = $urlFullEndDate;
						$championInsert->champion_id = $value2->id;
						$championInsert->active = 0;
						$championInsert->save();
					}
				}
			}

			return $championsOnSale;
		}

		getSkins($html, $today, $urlFullEndDate, $urlFullStartDate); 
		getChampions( $champions, $html, $today, $urlFullEndDate, $urlFullStartDate);











	}

}
