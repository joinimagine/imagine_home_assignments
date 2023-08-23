<?php


namespace App\Http\Services\Books;


use App\Models\Book;
use App\Models\Role;
use Illuminate\Support\Facades\Config;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EloquentAdminBookService implements BookQueryService, BookModificationService
{
    public function index($perPage, $page)
    {
        return QueryBuilder::for(Book::class)
                ->allowedFilters(Book::getAllowedFilters())
                ->allowedIncludes(Book::getAllowedIncludes())
                ->defaultSort('-id')
                ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show($id) {

        $book = Book::find($id);

        if(!$book) {

            throw new NotFoundHttpException(Config::get('messages.api.books.not_found'));
        }

        return $book->load(Book::getAllowedIncludes());
    }

    public function store($data) {

        return Book::create($data);
    }

    public function update($data, $id) {

        $book = Book::find($id);

        if(!$book) {

            throw new NotFoundHttpException(Config::get('messages.api.books.not_found'));
        }

        $book->update($data);

        return $book;
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if(!$book) {

            throw new NotFoundHttpException(Config::get('messages.api.books.not_found'));
        }

        return $book->delete();
    }
}
