<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\BookNotFoundException;
use App\Http\Requests\Book\CreateBookRequest;
use App\Http\Requests\Book\EditBookRequest;
use App\Http\Resources\V1\BookResource;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return response()->json([

            'success' => true,
            'books' => BookResource::collection(Book::paginate(40)),


        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        $this->authorize('create', Book::class);

        Book::create($request->validated());

        return response()->json([

            'success' => true,
            'message' => 'Book has been created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {

        return response()->json([
            'success' => true,
            'book' => new BookResource($book)
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditBookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $book->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Book has been updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();
        return response()->json([
            
            'success' => true,
            'message' => 'Book has been deleted successfully'
        ]);
    }

    public function search(Request $request)
    {

        $search_parameter = "%$request->search%";


        $searched_book = Book::where('title', 'LIKE', $search_parameter)->orWhere('author', 'LIKE', $search_parameter)->orWhere('genre', 'LIKE', $search_parameter)->get();


        if ($searched_book->isEmpty()) {

            throw new BookNotFoundException("There aren't any existing matching books", 404);
        }


        if ($searched_book instanceof Book) {


            return response()->json([
                'success' => true,
                'matching_books' => new BookResource($searched_book)
            ], 201);
        } else {

            return response()->json([
                'success' => true,
                'matching_books' =>  BookResource::collection($searched_book)
            ], 201);
        }
    }
}
