<x-layout>
    <x-slot:content>
        <div class="flex flex-col gap-4 justify-center items-center">
            <x-overview.profile-header :$summoner></x-overview.profile-header>
            <livewire:game-list :summoner="$summoner"></livewire:game-list>
        </div>
    </x-slot:content>
</x-layout>
