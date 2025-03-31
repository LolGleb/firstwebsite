<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class WordController extends Controller
{
    /**
     * The word service instance.
     */
    protected WordService $wordService;

    /**
     * Create a new controller instance.
     */
    public function __construct(WordService $wordService)
    {
        $this->wordService = $wordService;
    }

    /**
     * Display a word translation quiz
     */
    public function index(Request $request): View
    {
        $mode = $request->input('mode', 'german_to_russian');
        $words = $this->wordService->getWords();

        if ($words->isEmpty()) {
            return $this->handleEmptyWords($mode);
        }

        $currentWord = $this->wordService->getRandomWord($words);
        $targetLanguage = $this->wordService->getTargetLanguage($mode);
        $correctTranslation = $currentWord->$targetLanguage;

        $options = $this->wordService->generateOptions($words, $targetLanguage, $correctTranslation);

        return view('words.index', [
            'currentWord' => $currentWord,
            'options' => $options,
            'mode' => $mode,
            'sourceLanguage' => $this->wordService->getSourceLanguage($mode),
            'targetLanguage' => $targetLanguage
        ]);
    }

    /**
     * Check if the selected word matches the correct translation
     */
    public function check(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mode' => 'required|string|in:german_to_russian,russian_to_german',
            'current_word_id' => 'required|exists:word,id',
            'selected_word' => 'required|string',
        ]);

        $mode = $validated['mode'];
        $currentWord = Word::findOrFail($validated['current_word_id']);
        $isCorrect = $this->wordService->checkAnswer(
            $currentWord,
            $mode,
            $validated['selected_word']
        );

        if ($isCorrect) {
            Session::increment('score');
            return redirect()
                ->route('words.index', ['mode' => $mode])
                ->with('success', 'Правильно!');
        }

        // Reset score on incorrect answer
        Session::put('score', 0);

        // Get correct translation for the error message
        $targetLanguage = $this->wordService->getTargetLanguage($mode);
        $correctAnswer = $currentWord->$targetLanguage;

        return redirect()
            ->route('words.index', ['mode' => $mode])
            ->with('error', "Неправильно! Правильный ответ: {$correctAnswer}");
    }

    /**
     * Handle case when no words are available
     */
    private function handleEmptyWords(string $mode): View
    {
        return view('words.index', [
            'currentWord' => null,
            'options' => [],
            'mode' => $mode,
            'message' => 'В базе данных нет слов. Добавьте слова для начала.'
        ]);
    }
}
