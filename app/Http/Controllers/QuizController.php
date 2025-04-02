<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getQuestion($ordering) {
        $question = QuizQuestion::inRandomOrder()->where('ordering', $ordering)->get();
        return response()->json($question);
    }
}
