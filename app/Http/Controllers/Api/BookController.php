<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{

    protected $imageUrls = [
        'https://ebook.bungatujuh.sch.id/lib/minigalnano/createthumb.php?filename=images/docs/cover_atomic_habits_perubahan_kecil_yang_memberikan_hasil_luar_biasa.jpg&width=200',
        'https://cdn.gramedia.com/uploads/items/9786020386201_Harry-Potter-.jpg',
    ];

    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'author' => 'required|string',
            'release_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); 
        }

        $imageUrl = $this->imageUrls[array_rand($this->imageUrls)];

        $book = Book::create([
            'image' => $imageUrl,
            'name' => $request->name,
            'author' => $request->author,
            'release_date' => $request->release_date,
        ]);

        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'author' => 'required|string',
            'release_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::findOrFail($id);

        $book->update($request->except('image'));

        return response()->json($book);
    }

    public function destroy($id)
    {
        Book::destroy($id);
        return response()->json(['message' => 'Book deleted']);
    }
}
