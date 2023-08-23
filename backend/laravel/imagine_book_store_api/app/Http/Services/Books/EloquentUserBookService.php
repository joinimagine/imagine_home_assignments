<?php


namespace App\Http\Services\Books;


use App\Models\Book;
use Illuminate\Support\Facades\Config;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EloquentUserBookService implements BookQueryService
{
    public function index($perPage, $page)
    {
        return QueryBuilder::for(Book::class)
                    ->where('quantity', '>', 0)
                    ->allowedFilters(Book::getAllowedFilters())
                    ->allowedIncludes(Book::getAllowedIncludes())
                    ->defaultSort('-id')
                    ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show($id)
    {
        $book = Book::where('quantity', '>', 0)->find($id);

        if(!$book) {

            throw new NotFoundHttpException(Config::get('messages.api.books.not_found'));
        }

        return $book->load(Book::getAllowedIncludes());
    }
}
