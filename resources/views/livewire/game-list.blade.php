<div class="flex flex-col gap-4 justify-center items-center">
    @foreach ($games as $index=>$game)
        @if($index >= $perPage * $pageCount)
            @break
        @endif
        @php($currentParticipant = $game->participants->firstWhere('puuid', $summoner->puuid))
        <x-overview.game :$game :$currentParticipant></x-overview.game>
    @endforeach

    <button wire:click="showMore" wire:loading.remove class="btn btn-outline bg-base-300 border-accent">
        Show more
    </button>
    <button wire:loading class="btn btn-outline bg-base-300 border-accent">
        Loading...
    </button>
</div>
