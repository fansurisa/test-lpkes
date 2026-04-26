@extends('layouts.app')

@section('title', 'Katalog Pelatihan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="catalogFilter()">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="section-title">Katalog Pelatihan</h1>
            <p class="text-gray-500 mt-1">Event dan E-Course untuk tenaga kesehatan dan masyarakat umum</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Filter --}}
            <aside class="w-full lg:w-64 flex-shrink-0">

                {{-- Mini Calendar Widget --}}
                <div class="card p-4 mb-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Kalender Event</p>

                    {{-- Month navigation --}}
                    <div class="flex items-center justify-between mb-2">
                        <button type="button" @click="calPrev()"
                                class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <span class="text-sm font-semibold text-gray-700" x-text="calMonthLabel"></span>
                        <button type="button" @click="calNext()"
                                class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Day headers (Mon–Sun) --}}
                    <div class="grid grid-cols-7 mb-0.5">
                        <template x-for="h in ['Sn','Sl','Rb','Km','Jm','Sb','Mg']" :key="h">
                            <div class="text-center text-[9px] font-medium text-gray-400 py-1" x-text="h"></div>
                        </template>
                    </div>

                    {{-- Day cells --}}
                    <div class="grid grid-cols-7">
                        <template x-for="(day, idx) in calDays" :key="idx">
                            <div class="flex flex-col items-center py-px">
                                <button
                                    type="button"
                                    @click="day && calSelect(day)"
                                    class="w-7 h-7 text-xs rounded-full flex items-center justify-center transition-colors"
                                    :class="{
                                        'bg-primary-600 text-white font-semibold shadow-sm': day && calIsSelected(day),
                                        'ring-1 ring-primary-400 text-primary-700 font-semibold': day && calIsToday(day) && !calIsSelected(day),
                                        'hover:bg-gray-100 cursor-pointer text-gray-700': day && !calIsSelected(day),
                                        'invisible pointer-events-none': !day
                                    }"
                                    x-text="day || ''">
                                </button>
                                <div class="h-2 mt-px flex items-center justify-center">
                                    <template x-if="day && calCount(day) > 0">
                                        <span class="inline-flex items-center gap-0.5">
                                            <span class="block w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            <span class="text-[8px] font-bold text-red-600 leading-none"
                                                  x-show="calCount(day) > 1"
                                                  x-text="calCount(day)"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Legend + clear --}}
                    <div class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            <span class="block w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            <span class="text-[10px] text-gray-400">Ada event</span>
                        </div>
                        <button type="button"
                                x-show="selectedCalDate !== ''"
                                @click="calClear()"
                                class="text-[10px] text-primary-600 hover:underline">
                            Hapus pilihan
                        </button>
                    </div>
                </div>

                <form x-ref="form" method="GET" action="{{ route('catalog.index') }}" id="filterForm">
                    <div class="card p-5 space-y-5">
                        {{-- Search --}}
                        <div>
                            <label class="label">Cari Pelatihan</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" name="search" class="input pl-9" placeholder="Kata kunci..."
                                       value="{{ request('search') }}"
                                       @input.debounce.500ms="doFetch()">
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="label">Kategori</label>
                            <select name="category" class="input" @change="doFetch()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipe --}}
                        <div>
                            <label class="label">Tipe</label>
                            <div class="space-y-2">
                                @foreach(['Semua' => '', 'Event' => 'event', 'E-Course' => 'ecourse'] as $label => $value)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="type" value="{{ $value }}"
                                               {{ request('type', '') === $value ? 'checked' : '' }}
                                               class="text-primary-500 border-gray-300"
                                               @change="doFetch()">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="label">Harga</label>
                            <div class="space-y-2">
                                @foreach(['Semua' => '', 'Gratis' => 'free', 'Berbayar' => 'paid'] as $label => $value)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="price" value="{{ $value }}"
                                               {{ request('price', '') === $value ? 'checked' : '' }}
                                               class="text-primary-500 border-gray-300"
                                               @change="doFetch()">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- SKP --}}
                        <div>
                            <label class="label">SKP</label>
                            <div class="space-y-2">
                                @foreach(['Semua' => '', 'Ada SKP' => 'yes', 'Tanpa SKP' => 'no'] as $label => $value)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="skp" value="{{ $value }}"
                                               {{ request('skp', '') === $value ? 'checked' : '' }}
                                               class="text-primary-500 border-gray-300"
                                               @change="doFetch()">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label class="label">Tanggal</label>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Dari</p>
                                    <input type="date" name="date_from" x-ref="dateFrom"
                                           class="input text-sm"
                                           value="{{ request('date_from') }}"
                                           @change="selectedCalDate = $el.value; if ($el.value) { const p = $el.value.split('-'); calYear = parseInt(p[0]); calMonth = parseInt(p[1]) - 1; } doFetch()">
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Sampai</p>
                                    <input type="date" name="date_to" x-ref="dateTo"
                                           class="input text-sm"
                                           value="{{ request('date_to') }}"
                                           @change="doFetch()">
                                </div>
                            </div>
                        </div>

                        @if(request()->anyFilled(['search', 'category', 'type', 'price', 'skp', 'date_from', 'date_to']))
                            <a href="{{ route('catalog.index') }}" class="btn-secondary w-full text-center text-sm">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>
            </aside>

            {{-- Results area --}}
            <div class="flex-1 min-w-0">

                {{-- Skeleton loading --}}
                <div x-show="loading"
                     x-transition:enter="transition-opacity duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="h-5 w-44 bg-gray-200 rounded-full animate-pulse mb-4"></div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        @for($i = 0; $i < 6; $i++)
                            <div class="card overflow-hidden animate-pulse">
                                <div class="h-44 bg-gray-200"></div>
                                <div class="p-4 space-y-2">
                                    <div class="h-3 bg-gray-200 rounded-full w-1/3"></div>
                                    <div class="h-4 bg-gray-200 rounded-full w-full"></div>
                                    <div class="h-4 bg-gray-200 rounded-full w-3/4"></div>
                                    <div class="flex justify-between items-center pt-3 mt-1 border-t border-gray-100">
                                        <div class="h-4 bg-gray-200 rounded-full w-1/4"></div>
                                        <div class="h-3 bg-gray-200 rounded-full w-1/4"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Actual results --}}
                <div x-show="!loading"
                     x-transition:enter="transition-opacity duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     id="catalog-results">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm text-gray-500">
                            Menampilkan <strong>{{ $trainings->total() }}</strong> pelatihan
                        </p>
                    </div>

                    @if($trainings->isEmpty())
                        <div class="card p-16 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada pelatihan ditemukan</p>
                            <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach($trainings as $training)
                                @include('components.training-card', ['training' => $training])
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $trainings->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        function catalogFilter() {
            const _now = new Date();
            const _months = ['Januari','Februari','Maret','April','Mei','Juni',
                             'Juli','Agustus','September','Oktober','November','Desember'];
            const _initDate = '{{ request('date_from', '') }}';

            return {
                loading: false,

                // Calendar state
                calYear:  _initDate ? parseInt(_initDate.substr(0,4)) : _now.getFullYear(),
                calMonth: _initDate ? parseInt(_initDate.substr(5,2)) - 1 : _now.getMonth(),
                eventDates: @json($allSchedules),
                selectedCalDate: _initDate,

                get calMonthLabel() {
                    return _months[this.calMonth] + ' ' + this.calYear;
                },

                get calDays() {
                    // Monday-first: shift Sunday (0) → position 6
                    const firstDow = (new Date(this.calYear, this.calMonth, 1).getDay() + 6) % 7;
                    const total    = new Date(this.calYear, this.calMonth + 1, 0).getDate();
                    const days = [];
                    for (let i = 0; i < firstDow; i++) days.push(null);
                    for (let d = 1; d <= total; d++) days.push(d);
                    return days;
                },

                calPrev() {
                    if (this.calMonth === 0) { this.calMonth = 11; this.calYear--; }
                    else this.calMonth--;
                },

                calNext() {
                    if (this.calMonth === 11) { this.calMonth = 0; this.calYear++; }
                    else this.calMonth++;
                },

                calKey(day) {
                    return `${this.calYear}-${String(this.calMonth + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                },

                calCount(day) {
                    return this.eventDates[this.calKey(day)] || 0;
                },

                calIsSelected(day) {
                    return this.selectedCalDate !== '' && this.selectedCalDate === this.calKey(day);
                },

                calIsToday(day) {
                    return day === _now.getDate()
                        && this.calMonth === _now.getMonth()
                        && this.calYear  === _now.getFullYear();
                },

                calSelect(day) {
                    const key = this.calKey(day);
                    if (this.selectedCalDate === key) {
                        this.calClear();
                    } else {
                        this.selectedCalDate = key;
                        this.$refs.dateFrom.value = key;
                        this.$refs.dateTo.value   = key;
                        this.doFetch();
                    }
                },

                calClear() {
                    this.selectedCalDate = '';
                    this.$refs.dateFrom.value = '';
                    this.$refs.dateTo.value   = '';
                    this.doFetch();
                },

                async doFetch() {
                    this.loading = true;

                    const form   = this.$refs.form;
                    const params = new URLSearchParams(new FormData(form));

                    for (const [key, val] of [...params.entries()]) {
                        if (!val) params.delete(key);
                    }

                    const url = form.action + (params.toString() ? '?' + params : '');

                    try {
                        const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const html = await res.text();
                        const doc  = new DOMParser().parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('catalog-results');
                        if (newContent) {
                            document.getElementById('catalog-results').innerHTML = newContent.innerHTML;
                        }
                        history.replaceState({}, '', url);
                    } catch (e) {
                        console.error('Filter fetch error:', e);
                    }

                    this.loading = false;
                }
            }
        }
    </script>
@endsection
