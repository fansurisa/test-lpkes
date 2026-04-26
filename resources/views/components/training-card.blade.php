<article class="card overflow-hidden hover:shadow-md transition-shadow group">
    {{-- Thumbnail --}}
    <a href="{{ route('catalog.show', $training->slug) }}" class="block">
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
    </a>

    {{-- Content --}}
    <div class="p-4">
        {{-- Category + status --}}
        <div class="flex items-center justify-between gap-2 mb-1.5">
            <div class="text-xs font-medium text-primary-600 truncate">
                {{ $training->category->name }}
            </div>
            @if($training->schedule)
                @php $status = $training->event_status; @endphp
                @if($status === 'selesai')
                    <span class="flex-shrink-0 inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">
                        <span class="w-1 h-1 rounded-full bg-gray-400"></span>Selesai
                    </span>
                @elseif($status === 'ditutup')
                    <span class="flex-shrink-0 inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-600">
                        <span class="w-1 h-1 rounded-full bg-red-500"></span>Reg. Ditutup
                    </span>
                @elseif($status === 'segera_tutup')
                    <span class="flex-shrink-0 inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-orange-50 text-orange-600">
                        <span class="w-1 h-1 rounded-full bg-orange-500 animate-pulse"></span>{{ $training->deadline_countdown_label }}
                    </span>
                @else
                    <span class="flex-shrink-0 inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-700">
                        <span class="w-1 h-1 rounded-full bg-green-500"></span>Akan Datang
                    </span>
                @endif
            @endif
        </div>

        {{-- Title --}}
        <a href="{{ route('catalog.show', $training->slug) }}">
            <h3 class="font-semibold text-gray-900 text-sm leading-snug mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                {{ $training->title }}
            </h3>
        </a>

        {{-- Schedule date --}}
        @if($training->schedule)
            <div class="flex items-center gap-1 text-xs text-gray-400 mb-2">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $training->schedule->format('d M Y') }}</span>
            </div>
        @endif

        {{-- Price + Cart --}}
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
            <span class="{{ $training->is_free ? 'text-green-600 font-bold' : 'text-gray-900 font-bold' }} text-sm">
                {{ $training->formatted_price }}
            </span>
            <div class="flex items-center gap-2">
                @if($training->enrollments_count > 0)
                    <span class="text-xs text-gray-400">
                        {{ number_format($training->enrollments_count) }} peserta
                    </span>
                @endif
                @auth
                    @php $inCart = \App\Models\CartItem::where('user_id', auth()->id())->where('training_id', $training->id)->exists(); @endphp
                    @if(!auth()->user()->isEnrolled($training))
                        <form method="POST" action="{{ $inCart ? route('cart.remove', $training) : route('cart.add', $training) }}">
                            @csrf
                            @if($inCart) @method('DELETE') @endif
                            <button type="submit"
                                    title="{{ $inCart ? 'Hapus dari keranjang' : 'Simpan ke keranjang' }}"
                                    class="w-7 h-7 rounded-full flex items-center justify-center transition-colors border
                                           {{ $inCart ? 'bg-primary-500 border-primary-500 text-white' : 'border-gray-200 text-gray-400 hover:border-primary-400 hover:text-primary-500' }}">
                                <svg class="w-3.5 h-3.5" fill="{{ $inCart ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</article>
