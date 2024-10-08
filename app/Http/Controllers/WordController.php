<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WordController extends Controller
{
    public function index(Request $request)
    {
        //Я знаю что это полная ерунда. Надо будет переписать как-нибудь
        $mode = $request->input('mode', 'german_to_russian');
        Word::factory()->create(['german' => 'Haus', 'russian' => 'Дом']);
        $words = Word::all();
        $currentWord = $words->random();
        if ($words->isEmpty()) {
            return view('words.index', [
                'currentWord' => $currentWord,
                'message' => 'В базе данных нет слов. Добавьте слова для начала.',
                'mode' => $mode
            ]);
        }
        $currentWord = $words->random();
        $correctTranslation = ($mode === 'german_to_russian') ? $currentWord->russian : $currentWord->german;

        $options = $words->pluck($mode === 'german_to_russian' ? 'russian' : 'german')->shuffle()->take(4)->toArray();
        if (!in_array($correctTranslation, $options)) {
            $options[array_rand($options)] = $correctTranslation;
        }

        return view('words.index', [
            'currentWord' => $currentWord,
            'options' => $options,
            'mode' => $mode
        ]);
    }

    public function check(Request $request)
    {
        $mode = $request->input('mode');
        $currentWord = Word::find($request->input('current_word_id'));
        $correctTranslation = ($mode === 'german_to_russian') ? $currentWord->russian : $currentWord->german;
        $selectedWord = $request->input('selected_word');

        if ($correctTranslation === $selectedWord) {
            Session::increment('score');
            return redirect()->route('words.index', ['mode' => $mode])->with('success', 'Правильно!');
        } else {
            Session::put('score', 0);
            return redirect()->route('words.index', ['mode' => $mode])->with('error', 'Неправильно! Попробуйте ещё раз.');
        }
    }
}
