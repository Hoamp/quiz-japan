<?php

namespace App\Http\Controllers;

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
    
}
