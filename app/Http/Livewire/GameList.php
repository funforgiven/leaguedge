<?php

namespace App\Http\Livewire;

use App\Models\Game;

use App\Models\Participant;
use App\Models\Summoner;
use Carbon\Carbon;
use Livewire\Component;
use RiotAPI\Base\Exceptions\GeneralException;
use RiotAPI\Base\Exceptions\RequestException;
use RiotAPI\Base\Exceptions\ServerException;
use RiotAPI\Base\Exceptions\ServerLimitException;
use RiotAPI\Base\Exceptions\SettingsException;
use RiotAPI\LeagueAPI\LeagueAPI;

class GameList extends Component
{
    public $summoner;
    public $gameIds;

    public $perPage = 5;
    public $pageCount = 1;

    public function mount()
    {
        $this->gameIds = app(LeagueAPI::class)->getMatchIdsByPUUID($this->summoner->puuid, count: 100);
    }
    public function render()
    {
        $games = $this->createOrGetGames();

        return view('livewire.game-list', [
            'games' => $games,
        ]);
    }

    public function loadMore()
    {
        $this->pageCount += 1;
    }

    public function createOrGetGames()
    {
        $games = collect();

        $length = $this->perPage * $this->pageCount;
        foreach(array_slice($this->gameIds, 0, $length) as $gameId)
        {
            $games->add($this->createOrGetGame($gameId));
        }

        return $games;
    }

    public function createOrGetGame($gameId)
    {
        $game = Game::query()->where([
            'gameId' => $gameId,
        ])->first();

        if ($game == null) {
            try {
                $matchDto = app(LeagueAPI::class)->getMatch($gameId);
            } catch (RequestException|ServerException|ServerLimitException|SettingsException|GeneralException $e) {
                dd($e);
            }

            $game = new Game([
                'gameId' => $gameId,
                'gameMode' => $matchDto->info->gameMode,
                'gameType' => $matchDto->info->gameType,
                'mapId' => $matchDto->info->mapId,
                'gameDuration' => $matchDto->info->gameDuration,
                'gameCreation' => Carbon::createFromTimestampMsUTC($matchDto->info->gameCreation)->toDate(),
                'gameStart' => Carbon::createFromTimestampMsUTC($matchDto->info->gameStartTimestamp)->toDate(),
            ]);
            $game->save();

            $this->addParticipants($matchDto, $gameId);
        }

        return $game;
    }

    public function addParticipants($matchDto, $gameId)
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

            $this->createParticipant($participantDto, $gameId);
        }
    }

    public function createSummoner($participantDto)
    {
        try {
            $summonerDto = app(LeagueAPI::class)->getSummonerByPUUID($participantDto->puuid);
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
        ]);
        $summoner->save();
    }

    public function createParticipant($participantDto, $gameId)
    {
        $participant = new Participant([
            'puuid' => $participantDto->puuid,
            'gameId' => $gameId,
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
