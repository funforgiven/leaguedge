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
        $summoner = $this->createOrUpdateSummonerByName($summonerName);

        // Create Games
        $games = $this->createOrGetGames($summoner, 5);

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

    public function createOrUpdateSummonerByName($summonerName)
    {
        try {
            $summonerDto = $this->lapi->getSummonerByName($summonerName);
        }
        catch (RequestException|ServerLimitException|SettingsException|GeneralException $e) {
            dd($e);
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
            'region' => $this->region,
        ]);
    }

    public function createOrGetGames($summoner, int $count = 100)
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
                'gameId' => $matchId,
                'gameMode' => $matchDto->info->gameMode,
                'gameType' => $matchDto->info->gameType,
                'mapId' => $matchDto->info->mapId,
                'gameDuration' => $matchDto->info->gameDuration,
                'gameCreation' => Carbon::createFromTimestampMsUTC($matchDto->info->gameCreation)->toDate(),
                'gameStart' => Carbon::createFromTimestampMsUTC($matchDto->info->gameStartTimestamp)->toDate(),
            ]);
            $game->save();

            $this->addParticipants($matchDto, $matchId);
        }

        return $game;
    }

    public function addParticipants($matchDto, $matchId)
    {
        foreach($matchDto->info->participants as $participantDto)
        {
            $summoner = Summoner::query()->where([
                'puuid' => $participantDto->puuid,
            ])->first();

            if($summoner == null)
            {
                $this->createSummoner($participantDto);
            }

            $this->createParticipant($participantDto, $matchId);
        }
    }

    public function createSummoner($participantDto)
    {
        try {
            $summonerDto = $this->lapi->getSummonerByPUUID($participantDto->puuid);
        } catch (RequestException|ServerException|ServerLimitException|SettingsException|GeneralException $e) {
            dd($e);
        }

        $summoner = new Summoner([
            'puuid' => $summonerDto->puuid,
            'accountId' => $summonerDto->accountId,
            'summonerId' => $summonerDto->id,
            'profileIconId' => $summonerDto->profileIconId,
            'revisionDate' => Carbon::createFromTimestampMsUTC($summonerDto->revisionDate)->toDate(),
            'name'=> $summonerDto->name,
            'summonerLevel' => $summonerDto->summonerLevel,
            'region' => $this->region,
        ]);
        $summoner->save();
    }

    public function createParticipant($participantDto, $matchId)
    {
        $participant = new Participant([
            'puuid' => $participantDto->puuid,
            'gameId' => $matchId,
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
        $participant->save();
    }
}
