<div class="flex flex-col bg-base-300 border border-accent rounded-lg w-80 p-3">
    <div class="text-xl font-bold mb">Ranked {{ $queue }}</div>
    <div class="w-full h-0.5 my-2 bg-accent"></div>
    <div class="flex flex-row justify-between">
        <div class="flex flex-col">
            <div class="text-xl font-bold">{{ ucfirst(strtolower($rank->tier)) }} {{ $rank->rank }}</div>
            <div>{{ $rank->lp }} LP</div>
        </div>
        <div class="flex flex-col text-right justify-center">
            <div>{{ $rank->wins }}W {{ $rank->losses }}L</div>
            <div>{{ round(($rank->wins/($rank->wins+$rank->losses))*100) }}% Win Rate</div>
        </div>
    </div>
</div>
