<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getQuestion($ordering, Request $request) {
        $count = $request->query('count'); // Ambil jumlah soal dari query parameter
    
        $query = QuizQuestion::where('ordering', $ordering)->inRandomOrder();
    
        if ($count && is_numeric($count)) {
            $query->limit($count); // Ambil hanya sebanyak jumlah yang diminta
        }
    
        $questions = $query->get();
    
        return response()->json($questions);
    }
    
    public function historyData(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        $attempts = QuizAttempt::whereYear('attempt_date', $year)
            ->whereMonth('attempt_date', $month)
            ->with('ordering')
            ->get();

        $events = $attempts->map(function ($attempt) {
            return [
                'title' => $attempt->ordering->nama,
                'start' => $attempt->attempt_date,
                'correct' => $attempt->correct_answers,
                'incorrect' => $attempt->incorrect_answers
            ];
        });

        return response()->json($events);
    }

}
