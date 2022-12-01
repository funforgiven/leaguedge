<?php

namespace App\Http\Controllers;

use App\Models\Summoner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use RiotAPI\Base\BaseAPI;
use RiotAPI\Base\Definitions\Region;
use RiotAPI\Base\Exceptions\GeneralException;
use RiotAPI\Base\Exceptions\RequestException;
use RiotAPI\Base\Exceptions\ServerException;
use RiotAPI\Base\Exceptions\ServerLimitException;
use RiotAPI\Base\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects\SummonerDto;

class SummonerController extends Controller
{
    /*        $matchIds = $this->lapi->getMatchIdsByPUUID($summoner->puuid, count: 5);

        $matches = collect($matchIds)->map(function($matchId) {
            return $this->lapi->getMatch($matchId);
        });

        $match = $this->lapi->getMatch($matchIds[0]);

        dd($match);
*/

    protected LeagueAPI $lapi;

    public function __construct()
    {
        try {
            $this->lapi = new LeagueAPI([
                BaseAPI::SET_KEY => 'RGAPI-20c5d677-1d30-4f4b-ac6d-eb96c280d810',
                BaseAPI::SET_REGION => 'tr',
            ]);
        } catch (SettingsException|GeneralException $e) {
        }
    }

    public function overview($region, $summonerName)
    {
        try {
            $this->lapi->setRegion($region);
        } catch (SettingsException|GeneralException $e) {
            return dd('Region not found');
        }

        try {
            $summoner = $this->storeOrUpdate($this->lapi->getSummonerByName($summonerName), $region);
        } catch (RequestException|GeneralException|SettingsException|ServerLimitException|ServerException $e) {
            return dd('Summoner not found');
        }

        return view('overview', [
            'summoner' => $summoner,
        ]);
    }

    public function storeOrUpdate(SummonerDto $summonerRequest, string $region)
    {
        $summoner = Summoner::query()->firstOrNew([
            'puuid' => $summonerRequest->puuid
        ]);

        $summoner->accountId = $summonerRequest->accountId;
        $summoner->summonerId = $summonerRequest->id;
        $summoner->profileIconId = $summonerRequest->profileIconId;
        $summoner->revisionDate = Carbon::createFromTimestampMsUTC($summonerRequest->revisionDate)->toDate();
        $summoner->name = $summonerRequest->name;
        $summoner->summonerLevel = $summonerRequest->summonerLevel;
        $summoner->region = $region;
        $summoner->save();

        return $summoner;
    }
}
