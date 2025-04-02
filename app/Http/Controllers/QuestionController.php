<?php

namespace App\Http\Controllers;

use App\Models\Ordering;
use Illuminate\Http\Request;
use App\Models\QuizQuestion;

class QuestionController extends Controller
{    
    public function index($id)
    {
        $quiz = Ordering::findOrFail($id);
        $questions = QuizQuestion::where('ordering', $id)->paginate(10);
        
        return view('questions.index', compact('quiz', 'questions'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'ordering' => 'required|exists:orderings,id',
            'kanji' => 'required|string|max:255',
            'meaning' => 'required|string|max:255',
            'reading' => 'required|string|max:255',
        ]);

        QuizQuestion::create([
            'ordering' => strtolower($request->ordering),
            'kanji' => strtolower($request->kanji),
            'meaning' => strtolower($request->meaning),
            'reading' => strtolower($request->reading),
        ]);    

        return response()->json(['success' => 'Soal berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kanji' => 'required|string|max:255',
            'meaning' => 'string',
            'reading' => 'string',
        ]);

        $quiz = QuizQuestion::findOrFail($id);
        $quiz->update($request->all());

        return response()->json(['success' => 'Kuis berhasil diperbarui!']);
    }


    public function destroy($id)
    {
        QuizQuestion::findOrFail($id)->delete();
        return response()->json(['success' => 'Soal berhasil dihapus']);
}

}
