<div class="flex flex-col gap-2">
    @foreach($getState() ?? [] as $feature)
        <div class="flex items-center gap-2">
            @if(!empty($feature['icon_url']))
                <img src="{{ asset('storage/' . $feature['icon_url']) }}" 
                     class="w-6 h-6 rounded" 
                     alt="icon">
            @endif
            <span>{{ $feature['text'] ?? '' }}</span>
        </div>
    @endforeach
</div>