<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;

class QuizQuestionSeeder extends Seeder {
    public function run(): void {
        QuizQuestion::insert([
            ['kanji' => '日', 'meaning' => 'Matahari', 'reading' => 'nichi'],
            ['kanji' => '月', 'meaning' => 'Bulan', 'reading' => 'getsu'],
            ['kanji' => '火', 'meaning' => 'Api', 'reading' => 'ka'],
            ['kanji' => '水', 'meaning' => 'Air', 'reading' => 'sui'],
            ['kanji' => '木', 'meaning' => 'Pohon', 'reading' => 'moku'],
            ['kanji' => '金', 'meaning' => 'Emas', 'reading' => 'kin'],
            ['kanji' => '土', 'meaning' => 'Tanah', 'reading' => 'do'],
            ['kanji' => '空', 'meaning' => 'Langit', 'reading' => 'sora'],
            ['kanji' => '山', 'meaning' => 'Gunung', 'reading' => 'yama'],
        ]);
    }
}
