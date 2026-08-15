<x-filament-panels::page>
    <div class="sibatig-module-page">
        <p class="sibatig-module-eyebrow">{{ $eyebrow }}</p>

        <div class="sibatig-module-stats">
            @foreach ($stats as $stat)
                <article class="{{ $stat['tone'] }}">
                    <span>{{ $stat['label'] }}</span>
                    <strong>{{ $stat['value'] }}</strong>
                </article>
            @endforeach
        </div>

        <section class="sibatig-module-list">
            <header>
                <div><h2>{{ $listTitle }}</h2><p>{{ $listDescription }}</p></div>
            </header>

            <div>
                @forelse ($items as $item)
                    <a href="{{ $item['url'] }}" wire:navigate class="sibatig-module-item">
                        <time>{{ $item['date'] }}</time>
                        <span><strong>{{ $item['title'] }}</strong><small>{{ $item['meta'] }}</small></span>
                        <b class="{{ $item['tone'] }}">{{ $item['status'] }}</b>
                        <i>&rarr;</i>
                    </a>
                @empty
                    <p class="sibatig-module-empty">{{ $emptyMessage }}</p>
                @endforelse
            </div>
        </section>
    </div>
</x-filament-panels::page>
