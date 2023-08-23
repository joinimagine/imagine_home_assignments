<?php


namespace App\Http\Services\BookGenres;


use App\Http\Resources\BookGenreResource;
use App\Models\BookGenre;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EloquentBookGenreService implements BookGenreService
{

    public function index($perPage, $page)
    {
        return QueryBuilder::for(BookGenre::class)
                        ->allowedFilters(BookGenre::getAllowedFilters())
                        ->allowedIncludes(BookGenre::getAllowedIncludes())
                        ->defaultSort('-id')
                        ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show($id)
    {
        $bookGenre = BookGenre::find($id);

        if(!$bookGenre) {

            throw new NotFoundHttpException(Config::get('messages.api.book_genres.not_found'));
        }

        return $bookGenre->load(BookGenre::getAllowedIncludes());
    }

    public function store($data)
    {
        return BookGenre::create($data);
    }

    public function update($id, $data)
    {
        $bookGenre = BookGenre::find($id);

        if(!$bookGenre) {

            throw new NotFoundHttpException(Config::get('messages.api.book_genres.not_found'));
        }

        $bookGenre->update($data);

        return $bookGenre;
    }

    public function destroy($id)
    {
        $bookGenre = BookGenre::find($id);

        if(!$bookGenre) {

            throw new NotFoundHttpException(Config::get('messages.api.book_genres.not_found'));
        }

        return $bookGenre->delete();
    }
}
