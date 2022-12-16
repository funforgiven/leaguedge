<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Carbon\Carbon;
use RiotAPI\LeagueAPI\LeagueAPI;

class GameController extends Controller
{
    public static function createLastGames($summoner, $count)
    {
        $gameIds = collect(app(LeagueAPI::class)->getMatchIdsByPUUID($summoner->puuid, count: $count));

        $existingGames = collect(Game::query()->whereIn('gameId', $gameIds)->get());

        foreach($gameIds as $gameId)
        {
            if($existingGames->where('gameId', $gameId)->isEmpty())
            {
                GameController::createGame($gameId);
            }
        }
    }

    public static function createMoreGames($summoner, $count)
    {
        $gameIds = collect(app(LeagueAPI::class)->getMatchIdsByPUUID($summoner->puuid, count: 100));

        $existingGames = collect(Game::query()->whereIn('gameId', $gameIds)->get());

        $addedCount = 0;
        foreach($gameIds as $gameId)
        {
            if($addedCount >= $count)
            {
                break;
            }

            if($existingGames->where('gameId', $gameId)->isEmpty())
            {
                GameController::createGame($gameId);
                $addedCount++;
            }
        }
    }

    public static function createGame($gameId)
    {
        $matchDto = app(LeagueAPI::class)->getMatch($gameId);

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

        ParticipantController::createParticipants($matchDto, $gameId);

        return $game;
    }
}
