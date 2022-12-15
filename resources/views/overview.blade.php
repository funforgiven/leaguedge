<x-layout>
    <x-slot:content>
        <div class="flex flex-col gap-4 justify-center items-center">
            <x-overview.profile-header :$summoner></x-overview.profile-header>
            <div class="flex flex-row gap-2">
                <div class="flex flex-col gap-2">
                    @php($rank = $summoner->leagueEntrySolo)
                    @php($queue = 'Solo/Duo')
                    <x-overview.rank :$rank :$queue></x-overview.rank>
                    @php($rank = $summoner->leagueEntryFlex)
                    @php($queue = 'Flex')
                    <x-overview.rank :$rank :$queue></x-overview.rank>
                </div>
                <livewire:game-list :summoner="$summoner"></livewire:game-list>
            </div>

        </div>
    </x-slot:content>
</x-layout>
