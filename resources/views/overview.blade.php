<x-layout>
    <x-slot:content>
        <div class="flex flex-col gap-4 justify-center items-center">
            <x-overview.profile-header :$summoner></x-overview.profile-header>
            @foreach($games as $game)
                @php($currentParticipant = $game->participants->firstWhere('puuid', $summoner->puuid))
                <x-overview.game :$game :$currentParticipant></x-overview.game>
            @endforeach
        </div>
    </x-slot:content>
</x-layout>
