<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizAttemptController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'ordering_id' => 'required|exists:orderings,id',
            'correct_answers' => 'required|integer|min:0',
            'incorrect_answers' => 'required|integer|min:0',
        ]);

        QuizAttempt::create([
            'user_id' => Auth::id(),
            'ordering_id' => $request->ordering_id,
            'attempt_date' => now()->toDateString(),
            'correct_answers' => $request->correct_answers,
            'incorrect_answers' => $request->incorrect_answers,
        ]);

        return response()->json(['success' => 'Riwayat kuis berhasil disimpan']);
    }

    public function index(Request $request) {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attempts = QuizAttempt::where('user_id', Auth::id())
            ->whereYear('attempt_date', $year)
            ->whereMonth('attempt_date', $month)
            ->with('ordering')
            ->get();

        return view('quiz.history', compact('attempts', 'month', 'year'));
    }
}
