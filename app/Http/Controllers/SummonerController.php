<?php

namespace App\Http\Controllers;

use App\Models\Summoner;
use Carbon\Carbon;
use RiotAPI\Base\Exceptions\GeneralException;
use RiotAPI\LeagueAPI\LeagueAPI;

class SummonerController extends Controller
{
    protected string $region;

    public function overview($region, $summonerName)
    {
        $this->setRegion($region);

        // Get summoner
        $summoner = $this->createOrUpdateSummonerByName($summonerName);

        return view('overview', [
            'summoner' => $summoner,
        ]);
    }

    public function setRegion(string $region)
    {
        try {
            $this->region = $region;
            app(LeagueAPI::class)->setRegion($region);
        }
        catch(GeneralException $e){
            return abort(404);
        }
    }

    public function createOrUpdateSummonerByName($summonerName)
    {
        try {
            $summonerDto = app(LeagueAPI::class)->getSummonerByName($summonerName);
        }
        catch (GeneralException $e)
        {
            return abort(404);
        }

        return Summoner::query()->updateOrCreate([
            'puuid' => $summonerDto->puuid,
        ], [
            'accountId' => $summonerDto->accountId,
            'summonerId' => $summonerDto->id,
            'profileIconId' => $summonerDto->profileIconId,
            'revisionDate' => Carbon::createFromTimestampMsUTC($summonerDto->revisionDate)->toDate(),
            'name'=> $summonerDto->name,
            'summonerLevel' => $summonerDto->summonerLevel,
        ]);
    }
}
