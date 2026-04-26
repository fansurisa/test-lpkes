<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                Daftar Pelatihan
            </h2>

            {{-- Year picker --}}
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" @click.outside="open = false"
                        class="inline-flex items-center gap-2 text-sm font-medium border border-gray-200 dark:border-gray-700
                               rounded-lg px-3 py-1.5 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300
                               hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <span>{{ $selectedYear }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition
                     class="absolute right-0 mt-1 z-50 bg-white dark:bg-gray-900 border border-gray-200
                            dark:border-gray-700 rounded-lg shadow-lg overflow-hidden min-w-[80px]">
                    @foreach($this->getYearOptions() as $year)
                        <button type="button"
                                wire:click="setYear({{ $year }})"
                                @click="open = false"
                                class="w-full text-left px-4 py-2 text-sm transition-colors
                                       {{ $selectedYear == $year
                                           ? 'bg-primary-50 text-primary-700 font-semibold dark:bg-primary-900/30 dark:text-primary-400'
                                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            {{ $year }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        @php $counts = $this->getTabCounts(); @endphp
        <div class="flex gap-1 border-b border-gray-200 dark:border-gray-700 mb-4 overflow-x-auto">
            @foreach([
                'all'       => ['label' => 'Semua',           'count' => $counts['all']],
                'running'   => ['label' => 'Sedang Berjalan', 'count' => $counts['running']],
                'completed' => ['label' => 'Sudah Selesai',   'count' => $counts['completed']],
                'upcoming'  => ['label' => 'Akan Datang',     'count' => $counts['upcoming']],
            ] as $key => $tab)
                <button wire:click="setTab('{{ $key }}')"
                        class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium whitespace-nowrap transition-colors border-b-2 -mb-px
                               {{ $activeTab === $key
                                   ? 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400'
                                   : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                    {{ $tab['label'] }}
                    @if($tab['count'] > 0)
                        <span class="text-xs px-1.5 py-0.5 rounded-full font-medium
                                     {{ $activeTab === $key
                                         ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300'
                                         : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                            {{ $tab['count'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Table --}}
        @php $trainings = $this->getTrainings(); @endphp

        @if($trainings->isEmpty())
            <div class="py-10 text-center text-gray-400 dark:text-gray-500 text-sm">
                @if($activeTab === 'upcoming')
                    Tidak ada pelatihan yang akan datang.
                @elseif($activeTab === 'running')
                    Tidak ada pelatihan yang sedang berjalan di tahun {{ $selectedYear }}.
                @elseif($activeTab === 'completed')
                    Belum ada pelatihan yang selesai di tahun {{ $selectedYear }}.
                @else
                    Belum ada pendaftaran di tahun {{ $selectedYear }}.
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pelatihan</th>
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tipe</th>
                            @if($activeTab === 'upcoming')
                                <th class="text-left py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jadwal</th>
                            @endif
                            <th class="text-center py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Aktif</th>
                            <th class="text-center py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Selesai</th>
                            <th class="text-center py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total</th>
                            <th class="text-left py-2 px-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Harga</th>
                            <th class="py-2 px-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($trainings as $training)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ Str::limit($training->title, 50) }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $training->category->name }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                 {{ $training->type === 'event'
                                                     ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300'
                                                     : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                        {{ $training->type_label }}
                                    </span>
                                </td>
                                @if($activeTab === 'upcoming')
                                    <td class="py-3 px-3 text-gray-700 dark:text-gray-300">
                                        {{ $training->schedule?->format('d M Y') ?? '—' }}
                                    </td>
                                @endif
                                <td class="py-3 px-3 text-center">
                                    @if($training->active_count > 0)
                                        <span class="inline-flex items-center justify-center min-w-[1.5rem] px-1.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                            {{ $training->active_count }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($training->completed_count > 0)
                                        <span class="inline-flex items-center justify-center min-w-[1.5rem] px-1.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                            {{ $training->completed_count }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600">—</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($training->total_enrollments > 0)
                                        <span class="inline-flex items-center justify-center min-w-[1.5rem] px-1.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $training->total_enrollments }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600">0</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if($training->is_free)
                                        <span class="text-xs font-medium text-green-600 dark:text-green-400">Gratis</span>
                                    @else
                                        <span class="text-xs text-gray-600 dark:text-gray-400">Rp {{ number_format($training->price, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <a href="{{ route('catalog.show', $training->slug) }}" target="_blank"
                                       class="text-xs text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                                        Lihat →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
