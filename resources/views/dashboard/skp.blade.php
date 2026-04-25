@extends('layouts.dashboard')

@section('title', 'Riwayat SKP')

@section('content')
    <div class="mb-8 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat SKP</h1>
            <p class="text-gray-500 text-sm mt-1">Satuan Kredit Profesi yang telah Anda kumpulkan</p>
        </div>
        {{-- Total SKP badge --}}
        <div class="card px-5 py-3 text-center">
            <p class="text-xs text-gray-500">Total SKP</p>
            <p class="text-3xl font-extrabold text-purple-600">{{ $totalSkp }}</p>
            <p class="text-xs text-gray-400">SKP</p>
        </div>
    </div>

    <div class="card overflow-hidden">
        @if($skpRecords->isEmpty())
            <div class="py-16 text-center">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada riwayat SKP</p>
                <p class="text-gray-400 text-sm mt-1">SKP akan dicatat setelah Anda menyelesaikan pelatihan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelatihan</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">SKP</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($skpRecords as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $record->training->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $record->training->category->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $record->completed_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-700 font-bold">
                                        {{ $record->skp_earned }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $record->notes ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-100">
                        <tr>
                            <td colspan="2" class="px-6 py-3 text-sm font-semibold text-gray-900">Total</td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-600 text-white font-bold">
                                    {{ $totalSkp }}
                                </span>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
@endsection
