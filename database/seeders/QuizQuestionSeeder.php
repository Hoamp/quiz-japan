<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;

class QuizQuestionSeeder extends Seeder {
    public function run(): void {
        QuizQuestion::insert([
            ['kanji' => '日', 'meaning' => 'matahari', 'reading' => 'nichi'],
            ['kanji' => '月', 'meaning' => 'bulan', 'reading' => 'getsu'],
            ['kanji' => '火', 'meaning' => 'api', 'reading' => 'ka'],
            ['kanji' => '水', 'meaning' => 'air', 'reading' => 'sui'],
            ['kanji' => '木', 'meaning' => 'pohon', 'reading' => 'moku'],
            ['kanji' => '金', 'meaning' => 'emas', 'reading' => 'kin'],
            ['kanji' => '土', 'meaning' => 'tanah', 'reading' => 'do'],
            ['kanji' => '空', 'meaning' => 'langit', 'reading' => 'sora'],
            ['kanji' => '山', 'meaning' => 'gunung', 'reading' => 'yama'],
        ]);
    }
}
