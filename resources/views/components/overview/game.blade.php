<div class="flex flex-row items-center bg-base-300 border border-accent rounded-lg">
    <div class="flex flex-col items-center w-20 h-20 m-3 ml-5">
        <img src="https://cdn.communitydragon.org/latest/champion/{{ $currentParticipant->championId }}/square">
    </div>

    <div class="flex flex-col items-center text-center w-28 m-3">
       <div class="text-2xl font-bold tracking-tight">{{ $game->gameMode }}</div>
       <div class="mb-1 text-xs font-normal">{{ Carbon\Carbon::parse($game->gameStart)->diffForHumans() }}</div>
        <div class="flex flex-row">
            <div class="mr-2 text-lg font-bold tracking-tight {{ $currentParticipant->win ? 'text-green-500' : 'text-red-600' }}">{{ $currentParticipant->win ? "WIN" : "LOSS" }}</div>
            <div class="text-lg font-normal">{{ intval($game->gameDuration/60) }}:{{ $game->gameDuration%60 }}</div>
        </div>
    </div>

    <div class="flex flex-col items-center text-center w-32 m-3">
        <div class="text-2xl font-bold tracking-tight">{{ $currentParticipant->kills }}/{{ $currentParticipant->deaths }}/{{ $currentParticipant->assists }}</div>
        <div class="text-lg font tracking-tight"><b>{{ number_format(($currentParticipant->kills + $currentParticipant->assists) / ($currentParticipant->deaths == 0 ? 1 : $currentParticipant->deaths), 2) }}</b> KDA</div>
        <div class="mr-2 text-lg tracking-tight">{{ $currentParticipant->cs }} CS ({{ number_format($currentParticipant->cs / ($game->gameDuration/60), 2)}})</div>
        <div class="mr-2 text-lg tracking-tight">{{ $currentParticipant->visionScore }} vision</div>
    </div>

    <div class="grid grid-flow-col grid-cols-2 grid-rows-5 m-3 gap-0.5">
        @foreach($game->participants as $index=>$participant)
            <div class="flex {{$index < 5 ? 'flex-row-reverse text-right' : 'flex-row text-left'}}">
                <img class="w-8 h-8 mx-1" src="https://cdn.communitydragon.org/latest/champion/{{ $participant->championId }}/square">
                <a href="/lol/summoner/{{ $participant->summoner->region }}/{{ $participant->summoner->name }}" class="w-24 truncate text-lg tracking-tight hover:underline{{$participant->summoner->puuid == $currentParticipant->puuid ? ' font-bold' : ''}}">{{ $participant->summoner->name }}</a>
            </div>
        @endforeach
    </div>
</div>
