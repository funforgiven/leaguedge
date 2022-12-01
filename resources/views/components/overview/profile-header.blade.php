<div class="flex gap-4">
    <img class="w-24 h-24" src="https://cdn.communitydragon.org/latest/profile-icon/{{ $summoner->profileIconId }}" alt="Profile Icon">

    <div class="flex flex-col justify-center">
        <div class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ $summoner->name }}
        </div>
        <div class="text-gray-900 dark:text-white">
            Level {{ $summoner->summonerLevel }}
        </div>
    </div>
</div>

