<article class="card overflow-hidden hover:shadow-md transition-shadow group">
    <a href="{{ route('catalog.show', $training->slug) }}" class="block">
        {{-- Thumbnail --}}
        <div class="relative h-44 bg-gray-100 overflow-hidden">
            <img src="{{ $training->thumbnail_url }}"
                 alt="{{ $training->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            {{-- Type badge --}}
            <div class="absolute top-3 left-3 flex gap-1.5">
                <span class="{{ $training->type === 'event' ? 'badge-event' : 'badge-ecourse' }}">
                    {{ $training->type_label }}
                </span>
                @if($training->hasSkp())
                    <span class="badge-skp">
                        {{ $training->skp_value }} SKP
                    </span>
                @endif
            </div>
            @if($training->is_free)
                <div class="absolute top-3 right-3">
                    <span class="badge-free">GRATIS</span>
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="p-4">
            {{-- Category --}}
            <div class="text-xs font-medium text-primary-600 mb-1.5">
                {{ $training->category->name }}
            </div>

            {{-- Title --}}
            <h3 class="font-semibold text-gray-900 text-sm leading-snug mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                {{ $training->title }}
            </h3>

            {{-- Price --}}
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                <span class="{{ $training->is_free ? 'text-green-600 font-bold' : 'text-gray-900 font-bold' }} text-sm">
                    {{ $training->formatted_price }}
                </span>
                @if($training->enrollments_count > 0)
                    <span class="text-xs text-gray-400">
                        {{ number_format($training->enrollments_count) }} peserta
                    </span>
                @endif
            </div>
        </div>
    </a>
</article>
