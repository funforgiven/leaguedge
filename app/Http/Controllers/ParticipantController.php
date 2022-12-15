<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Summoner;

class ParticipantController extends Controller
{
    public static function createParticipants($matchDto, $gameId)
    {
        $summonerIds = collect();

        foreach($matchDto->info->participants as $participantDto)
        {
            $summonerIds->add($participantDto->puuid);
        }

        $existingSummoners = collect(Summoner::whereIn('puuid', $summonerIds)->get());

        foreach($matchDto->info->participants as $participantDto)
        {
            if($existingSummoners->where('puuid', $participantDto->puuid)->isEmpty())
            {
                SummonerController::createSummonerByPUUID($participantDto->puuid);
            }

            ParticipantController::createParticipant($participantDto, $gameId);
        }
    }

    public static function createParticipant($participantDto, $gameId)
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
