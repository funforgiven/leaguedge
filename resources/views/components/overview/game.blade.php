<div x-data="{ open: false }" class="bg-base-300 border border-accent rounded-lg">
    <button x-on:click="open = !open" class="flex flex-row items-center p-3">
        <div class="flex flex-col items-center w-20 h-20 m-3 ml-5">
            <img src="https://cdn.communitydragon.org/latest/champion/{{ $currentParticipant->championId }}/square">
        </div>
        <div class="flex flex-col items-center text-center w-28">
            <div class="text-2xl font-bold tracking-tight">{{ $game->gameMode }}</div>
            <div class="mb-1 text-xs font-normal">{{ Carbon\Carbon::parse($game->gameStart)->diffForHumans() }}</div>
            <div class="flex flex-row">
                <div class="mr-2 text-lg font-bold tracking-tight {{ $currentParticipant->win ? 'text-green-500' : 'text-red-600' }}">{{ $currentParticipant->win ? "WIN" : "LOSS" }}</div>
                <div class="text-lg font-normal">{{ intval($game->gameDuration/60) }}:{{ $game->gameDuration%60 }}</div>
            </div>
        </div>
        <div class="flex flex-col items-center text-center w-32">
            <div class="text-2xl font-bold tracking-tight">{{ $currentParticipant->kills }}/{{ $currentParticipant->deaths }}/{{ $currentParticipant->assists }}</div>
            <div class="text-lg tracking-tight"><b>{{ number_format(($currentParticipant->kills + $currentParticipant->assists) / ($currentParticipant->deaths == 0 ? 1 : $currentParticipant->deaths), 2) }}</b> KDA</div>
            <div class="mr-2 text-lg tracking-tight">{{ $currentParticipant->cs }} CS ({{ number_format($currentParticipant->cs / ($game->gameDuration/60), 2)}})</div>
            <div class="mr-2 text-lg tracking-tight">{{ $currentParticipant->visionScore }} vision</div>
        </div>
        <div class="grid grid-flow-col grid-cols-2 grid-rows-5 gap-0.5">
            @foreach($game->participants as $index=>$participant)
                <div class="flex {{$index < 5 ? 'flex-row-reverse text-right' : 'flex-row text-left'}}">
                    <img class="w-8 h-8 mx-1" src="https://cdn.communitydragon.org/latest/champion/{{ $participant->championId }}/square">
                    <a href="/lol/summoner/{{ $participant->summoner->region }}/{{ $participant->summoner->name }}" class="w-24 truncate text-lg tracking-tight hover:underline{{$participant->summoner->puuid == $currentParticipant->puuid ? ' font-bold' : ''}}">{{ $participant->summoner->name }}</a>
                </div>
            @endforeach
        </div>
    </button>
    <div x-show="open" class="p-3">
        <div class="flex flex-col border border-accent">
            <div class="flex flex-row items-center justify-between px-4 py-2">
                <div class="w-32 font-bold">Blue Team</div>
                <div class="w-16 ml-2 font-bold tracking-tight text-xs">KDA</div>
                <div class="w-8 font-bold text-xs">CS</div>
                <div class="w-8 font-bold text-xs">Vision</div>
            </div>
            @foreach($game->participants as $index=>$participant)
                @if($index >= 5)
                    @break
                @endif
                <div class="flex flex-row items-center bg-base-200 justify-between px-4 py-2">
                    <div class="flex flex-row gap-2 items-center">
                        <img class="w-8 h-8" src="https://cdn.communitydragon.org/latest/champion/{{ $participant->championId }}/square">
                        <div class="w-24">{{ $participant->summoner->name }}</div>
                    </div>
                    <div class="flex flex-col text-xs w-16">
                        <div class="font-bold tracking-tight">{{ $participant->kills }}/{{ $participant->deaths }}/{{ $participant->assists }}</div>
                        <div class="tracking-tight"><b>{{ number_format(($participant->kills + $participant->assists) / ($participant->deaths == 0 ? 1 : $participant->deaths), 2) }}</b> KDA</div>
                    </div>
                    <div class="w-8 text-xs">{{ $participant->cs }}</div>
                    <div class="w-8 text-xs">{{ $participant->visionScore }}</div>
                </div>
            @endforeach
        </div>
        <div class="my-4"></div>
        <div class="flex flex-col border border-accent">
            <div class="flex flex-row items-center justify-between px-4 py-2">
                <div class="w-32 font-bold">Red Team</div>
                <div class="w-16 ml-2 font-bold tracking-tight text-xs">KDA</div>
                <div class="w-8 font-bold text-xs">CS</div>
                <div class="w-8 font-bold text-xs">Vision</div>
            </div>
            @foreach($game->participants as $index=>$participant)
                @if($index < 5)
                    @continue
                @endif
                <div class="flex flex-row items-center bg-base-200 justify-between px-4 py-2">
                    <div class="flex flex-row gap-2 items-center">
                        <img class="w-8 h-8" src="https://cdn.communitydragon.org/latest/champion/{{ $participant->championId }}/square">
                        <div class="w-24">{{ $participant->summoner->name }}</div>
                    </div>
                    <div class="flex flex-col text-xs w-16">
                        <div class="font-bold tracking-tight">{{ $participant->kills }}/{{ $participant->deaths }}/{{ $participant->assists }}</div>
                        <div class="tracking-tight"><b>{{ number_format(($participant->kills + $participant->assists) / ($participant->deaths == 0 ? 1 : $participant->deaths), 2) }}</b> KDA</div>
                    </div>
                    <div class="w-8 text-xs">{{ $participant->cs }}</div>
                    <div class="w-8 text-xs">{{ $currentParticipant->visionScore }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

