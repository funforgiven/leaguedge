<?php

namespace App\Http\Controllers;

use App\Models\Summoner;
use Carbon\Carbon;
use RiotAPI\Base\BaseAPI;
use RiotAPI\Base\Exceptions\GeneralException;
use RiotAPI\Base\Exceptions\RequestException;
use RiotAPI\Base\Exceptions\ServerException;
use RiotAPI\Base\Exceptions\ServerLimitException;
use RiotAPI\Base\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\LeagueAPI;

class SummonerController extends Controller
{
    public function overview($region, $summonerName)
    {
        $summoner = SummonerController::getSummonerByName($summonerName, $region);

        return view('overview', [
            'summoner' => $summoner,
        ]);
    }

    public static function getSummonerByName($summonerName, $region)
    {
        $summoner = Summoner::query()
            ->where('name', 'ilike',"%{$summonerName}%")
            ->where('region', $region)
            ->first();

        $updated = false;

        try {
            app(LeagueAPI::class)->setRegion($region);
        }
        catch(GeneralException $e){
            abort(404);
        }

        if($summoner == null)
        {
            $summoner = SummonerController::createSummonerByName($summonerName);
            $updated = true;
        }
        else
        {
            if($summoner->updated_at->diffInSeconds(Carbon::now()) > 60)
            {
                $summoner = SummonerController::updateSummoner($summoner);
                $updated = true;
            }
        }

        if($updated)
        {
            GameController::createLastGames($summoner, 10);

            $leagueEntries = collect(app(LeagueAPI::class)->getLeagueEntriesForSummoner($summoner->summonerId));
            LeagueEntryFlexController::createOrUpdateLeagueEntry($leagueEntries->where('queueType', 'RANKED_FLEX_SR')->first());
            LeagueEntrySoloController::createOrUpdateLeagueEntry($leagueEntries->where('queueType', 'RANKED_SOLO_5x5')->first());
        }

        return $summoner;
    }

    public static function createSummonerByName($summonerName)
    {
        try {
            $summonerDto = app(LeagueAPI::class)->getSummonerByName($summonerName);
        }
        catch (GeneralException $e)
        {
            abort(404);
        }

        return SummonerController::createSummoner($summonerDto);
    }

    public static function createSummonerByPUUID($puuid)
    {
        try {
            $summonerDto = app(LeagueAPI::class)->getSummonerByPUUID($puuid);
        } catch (RequestException|ServerException|ServerLimitException|SettingsException|GeneralException $e)
        {
            abort(404);
        }

        return SummonerController::createSummoner($summonerDto);
    }

    public static function createSummoner($summonerDto)
    {
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

    public static function updateSummoner($summoner)
    {
        try {
            $summonerDto = app(LeagueAPI::class)->getSummonerByPUUID($summoner->puuid);
        } catch (RequestException|ServerException|ServerLimitException|SettingsException|GeneralException $e)
        {
            abort(404);
        }

        $summoner->accountId = $summonerDto->accountId;
        $summoner->summonerId = $summonerDto->id;
        $summoner->profileIconId = $summonerDto->profileIconId;
        $summoner->revisionDate = Carbon::createFromTimestampMsUTC($summonerDto->revisionDate)->toDate();
        $summoner->name = $summonerDto->name;
        $summoner->summonerLevel = $summonerDto->summonerLevel;
        $summoner->region = app(LeagueAPI::class)->getSetting(BaseAPI::SET_REGION);

        $summoner->save();
        return $summoner;
    }
}
