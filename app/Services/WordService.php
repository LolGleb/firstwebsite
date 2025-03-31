<?php

namespace App\Services;

use App\Models\Word;
use Illuminate\Support\Collection;

class WordService
{
    /**
     * Get all available words, ensuring at least one default word exists
     */
    public function getWords(): Collection
    {
        $wordsCount = Word::count();

        if ($wordsCount === 0) {
            Word::factory()->create(['german' => 'Haus', 'russian' => 'Дом']);
        }

        return Word::all();
    }

    /**
     * Determine the target language field based on mode
     */
    public function getTargetLanguage(string $mode): string
    {
        return $mode === 'german_to_russian' ? 'russian' : 'german';
    }

    /**
     * Get the source language field based on mode
     */
    public function getSourceLanguage(string $mode): string
    {
        return $mode === 'german_to_russian' ? 'german' : 'russian';
    }

    /**
     * Generate quiz options for the word translation
     */
    public function generateOptions(Collection $words, string $targetLanguage, string $correctTranslation, int $optionsCount = 4): array
    {
        // Ensure we have enough words for options
        if ($words->count() < $optionsCount) {
            $optionsCount = $words->count();
        }

        // Get unique options from the words collection
        $options = $words->pluck($targetLanguage)
            ->unique()
            ->shuffle()
            ->take($optionsCount)
            ->toArray();

        // Ensure the correct answer is included in the options
        if (!in_array($correctTranslation, $options)) {
            if (!empty($options)) {
                $options[array_rand($options)] = $correctTranslation;
            } else {
                $options[] = $correctTranslation;
            }
        }

        // Shuffle again to randomize the position of the correct answer
        shuffle($options);

        return $options;
    }

    /**
     * Check if the provided answer is correct
     */
    public function checkAnswer(Word $word, string $mode, string $selectedAnswer): bool
    {
        $targetLanguage = $this->getTargetLanguage($mode);
        return $word->$targetLanguage === $selectedAnswer;
    }

    /**
     * Get a random word from the collection
     */
    public function getRandomWord(Collection $words): ?Word
    {
        if ($words->isEmpty()) {
            return null;
        }

        return $words->random();
    }
}
