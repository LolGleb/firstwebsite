@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center my-4">Выберите правильный перевод</h1>

        {{-- Alert Messages --}}
        <x-alert type="success" />
        <x-alert type="error" />

        @if(isset($message))
            <div class="alert alert-info text-center">
                {{ $message }}
            </div>
        @elseif($currentWord)
            {{-- Word Translation Quiz --}}
            <form action="{{ route('words.check') }}" method="POST" x-data="{ showHint: false }">
                @csrf
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary">
                                {{ $mode === 'german_to_russian' ? 'DE → RU' : 'RU → DE' }}
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-success">Счет: {{ session('score', 0) }}</span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="mb-4 display-4">
                            {{ $currentWord->{$sourceLanguage} }}
                        </h2>

                        <div class="d-flex justify-content-center flex-wrap">
                            @foreach ($options as $option)
                                <button type="submit"
                                        name="selected_word"
                                        value="{{ $option }}"
                                        class="btn btn-lg btn-outline-primary m-2 px-4 py-2">
                                    {{ $option }}
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <button type="button"
                                    @click="showHint = !showHint"
                                    class="btn btn-sm btn-outline-secondary mt-3">
                                {{ __('Подсказка') }}
                            </button>

                            <div x-show="showHint" x-cloak class="alert alert-warning mt-2">
                                <p class="mb-0">
                                    <strong>{{ __('Первая буква') }}:</strong>
                                    {{ mb_substr($currentWord->{$targetLanguage}, 0, 1) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="current_word_id" value="{{ $currentWord->id }}">
                <input type="hidden" name="mode" value="{{ $mode }}">
            </form>
        @endif

        {{-- Mode Selection --}}
        <div class="d-flex justify-content-center mt-4">
            <a href="{{ route('words.index', ['mode' => 'german_to_russian']) }}"
               class="btn btn-lg {{ $mode === 'german_to_russian' ? 'btn-primary' : 'btn-outline-primary' }} mx-2">
                <i class="fas fa-language"></i> Немецкий → Русский
            </a>
            <a href="{{ route('words.index', ['mode' => 'russian_to_german']) }}"
               class="btn btn-lg {{ $mode === 'russian_to_german' ? 'btn-primary' : 'btn-outline-primary' }} mx-2">
                <i class="fas fa-language"></i> Русский → Немецкий
            </a>
        </div>

        {{-- Additional Actions --}}
        <div class="d-flex justify-content-center my-4">
            <a href="{{ route('words.index', ['mode' => $mode]) }}"
               class="btn btn-outline-success mx-2">
                <i class="fas fa-sync"></i> {{ __('Новое слово') }}
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('wordQuiz', () => ({
            showHint: false
        }));
    });
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .btn-lg { transition: all 0.2s ease; }
    .btn-lg:hover { transform: translateY(-2px); }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('wordQuiz', () => ({
            showHint: false
        }));
    });
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .btn-lg { transition: all 0.2s ease; }
    .btn-lg:hover { transform: translateY(-2px); }
</style>
@endpush
