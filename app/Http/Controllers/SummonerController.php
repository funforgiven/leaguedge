<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Participant;
use App\Models\Summoner;
use Carbon\Carbon;
use RiotAPI\Base\BaseAPI;
use RiotAPI\Base\Exceptions\GeneralException;
use RiotAPI\Base\Exceptions\RequestException;
use RiotAPI\Base\Exceptions\ServerException;
use RiotAPI\Base\Exceptions\ServerLimitException;
use RiotAPI\Base\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects\MatchDto;
use RiotAPI\LeagueAPI\Objects\SummonerDto;

class SummonerController extends Controller
{
    protected LeagueAPI $lapi;
    protected string $region;

    public function __construct()
    {
        try {
            $this->lapi = new LeagueAPI([
                BaseAPI::SET_KEY => env('RGAPI'),
                BaseAPI::SET_REGION => 'tr',
            ]);
        } catch (SettingsException|GeneralException $e) {
            dd($e);
        }
    }

    public function overview($region, $summonerName)
    {
        $this->setRegion($region);

        // Get summoner
        $summoner = $this->getSummoner($summonerName);

        // Create Games
        $games = $this->createGames($summoner, 5);

        return view('overview', [
            'summoner' => $summoner,
            'games' => $games,
        ]);
    }

    public function setRegion(string $region)
    {
        try {
            $this->region = $region;
            $this->lapi->setRegion($region);
        } catch (SettingsException|GeneralException $e) {
            dd($e);
        }
    }

    public function getSummoner(string $summonerName)
    {
        try {
            return $this->createOrUpdateSummoner($this->lapi->getSummonerByName($summonerName));
        } catch (RequestException|GeneralException|SettingsException|ServerLimitException|ServerException $e) {
            dd($e);
        }
    }

    public function createOrUpdateSummoner(SummonerDto $summonerDto)
    {
        return Summoner::query()->updateOrCreate([
            'puuid' => $summonerDto->puuid,
        ], [
            'accountId' => $summonerDto->accountId,
            'summonerId' => $summonerDto->id,
            'profileIconId' => $summonerDto->profileIconId,
            'revisionDate' => Carbon::createFromTimestampMsUTC($summonerDto->revisionDate)->toDate(),
            'name'=> $summonerDto->name,
            'summonerLevel' => $summonerDto->summonerLevel,
            'region' => $this->region,
        ]);
    }

    public function createGames($summoner, int $count = 100)
    {
        try {
            $matchIds = $this->lapi->getMatchIdsByPUUID($summoner->puuid, count: $count);
        } catch (RequestException|ServerException|ServerLimitException|SettingsException|GeneralException $e) {
            dd($e);
        }

        $games = collect();

        foreach($matchIds as $matchId)
        {
            $games->add($this->createOrGetGame($matchId));
        }

        return $games;
    }
    public function createOrGetGame($matchId)
    {
        $game = Game::query()->where([
            'gameId' => $matchId,
        ])->first();

        if($game == null)
        {
            try {
                $matchDto = $this->lapi->getMatch($matchId);
            } catch (RequestException|ServerException|ServerLimitException|SettingsException|GeneralException $e) {
                dd($e);
            }

            $game = new Game([
                'gameId' => "$matchId",
                'gameMode' => $matchDto->info->gameMode,
                'gameType' => $matchDto->info->gameType,
                'mapId' => $matchDto->info->mapId,
                'gameCreation' => Carbon::createFromTimestampMsUTC($matchDto->info->gameCreation)->toDate(),
                'gameStart' => Carbon::createFromTimestampMsUTC($matchDto->info->gameStartTimestamp)->toDate(),
                'gameEnd' => Carbon::createFromTimestampMsUTC($matchDto->info->gameStartTimestamp + $matchDto->info->gameDuration * 1000)->toDate(),
            ]);

            $game->save();

            foreach($matchDto->info->participants as $participantDto)
            {
                Participant::query()->firstOrCreate([
                    'puuid' => $participantDto->puuid,
                    'gameId' => "{$matchDto->info->platformId}_{$matchDto->info->gameId}",
                ],
                [
                    'participantId' => $participantDto->participantId,
                    'championId' => $participantDto->championId,
                    'kills' => $participantDto->kills,
                    'deaths' => $participantDto->deaths,
                    'assists' => $participantDto->assists,
                    'cs' => $participantDto->totalMinionsKilled,
                    'visionScore' => $participantDto->visionScore,
                    'item0' => $participantDto->item0,
                    'item1' => $participantDto->item1,
                    'item2' => $participantDto->item2,
                    'item3' => $participantDto->item3,
                    'item4' => $participantDto->item4,
                    'item5' => $participantDto->item5,
                    'item6' => $participantDto->item6,
                    'win' => $participantDto->win,
                ]);
            }
        }

        return $game;
    }
}
