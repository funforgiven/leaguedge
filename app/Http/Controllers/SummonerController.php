<?php

namespace App\Http\Controllers;

use App\Models\Summoner;
use Carbon\Carbon;
use RiotAPI\Base\BaseAPI;
use RiotAPI\Base\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\LeagueAPI;

class SummonerController extends Controller
{
    public function overview($region, $summonerName)
    {
        $summoner = SummonerController::getSummonerByName($summonerName, $region);
        GameController::createGames($summoner, 10);

        return view('overview', [
            'summoner' => $summoner,
        ]);
    }

    public static function getSummonerByName($summonerName, $region)
    {
        try {
            app(LeagueAPI::class)->setRegion($region);
        }
        catch(GeneralException $e){
            abort(404);
        }

        try {
            $summonerDto = app(LeagueAPI::class)->getSummonerByName($summonerName);
        }
        catch (GeneralException $e)
        {
            abort(404);
        }

        return Summoner::query()->updateOrCreate([
            'puuid' => $summonerDto->puuid,
        ], [
            'accountId' => $summonerDto->accountId,
            'summonerId' => $summonerDto->id,
            'profileIconId' => $summonerDto->profileIconId,
            'revisionDate' => Carbon::createFromTimestampMsUTC($summonerDto->revisionDate)->toDate(),
            'name' => $summonerDto->name,
            'summonerLevel' => $summonerDto->summonerLevel,
            'region' => app(LeagueAPI::class)->getSetting(BaseAPI::SET_REGION),
        ]);
    }

    public static function createSummoner($puuid)
    {
        $summonerDto = app(LeagueAPI::class)->getSummonerByPUUID($puuid);

        $summoner = new Summoner([
            'puuid' => $summonerDto->puuid,
            'accountId' => $summonerDto->accountId,
            'summonerId' => $summonerDto->id,
            'profileIconId' => $summonerDto->profileIconId,
            'revisionDate' => Carbon::createFromTimestampMsUTC($summonerDto->revisionDate)->toDate(),
            'name'=> $summonerDto->name,
            'summonerLevel' => $summonerDto->summonerLevel,
            'region' => app(LeagueAPI::class)->getSetting(BaseAPI::SET_REGION),
        ]);
        $summoner->save();

        return $summoner;
    }
}
