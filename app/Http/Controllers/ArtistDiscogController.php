<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArtistDiscogModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArtistDiscogController extends Controller
{
    
    public function getAll()
    {
        $salesGetAll = ArtistDiscogModel::get();

        return response()->json([
            'message' => 'Get All successful',
            'code' => 200,
            'status' => 'OK',
            'results' => $salesGetAll,
        ]);
    }

    // combined sales of all albums of an artist
    public function albumsSoldPerArtist(Request $request){
        $salesByArtist = ArtistDiscogModel::select('artist')
            ->selectRaw('SUM(2022_sales) as total_sales')
            ->groupBy('artist')
            ->get();

        return response()->json([
            'message' => 'Albums sold per artist displayed successfully',
            'code' => 200,
            'status' => 'OK',
            'results' => $salesByArtist,
        ]);
    }

    // get all artists and their albums
    public function AllArtistAlbums(){
        $salesByArtist = ArtistDiscogModel::select('artist', 'album', DB::raw('2022_sales as sales'))
            ->get();
        $result = [];

        foreach ($salesByArtist as $sale) {
            $artist = $sale->artist;
            $album = $sale->album;
            $sales = $sale->sales;

            if (!isset($result[$artist])) {
                $result[$artist] = [];
            }

            $result[$artist][$album] = $sales;
        }

        return response()->json([
            'message' => 'All artists and albums displayed successfully',
            'code' => 200,
            'status' => 'OK',
            'results' => $result,
        ]);
    }

    // get top one artist based on total sales
    // public function getTopOne(Request $request)
    // {
    //     $salesByArtist = ArtistDiscogModel::select('artist')
    //         ->selectRaw('SUM(2022_sales) as total_sales')
    //         ->groupBy('artist')
    //         ->get()->sortByDesc('total_sales')->first();
        
    //     return response()->json([
    //         'message' => 'Top one artist displayed successfully',
    //         'code' => 200,
    //         'status' => 'OK',
    //         'results' => $salesByArtist,
    //     ]);
    // }
    public function getTopOne(Request $request)
    {
        $topArtistData = ArtistDiscogModel::select('artist', DB::raw('MAX(artist_description) as artist_description'))
            ->selectRaw('SUM(2022_sales) as total_sales')
            ->groupBy('artist')
            ->orderByDesc('total_sales')
            ->first();

        if (!$topArtistData) {
            abort(
                response()->json([
                    'message' => 'No artist found',
                    'code' => 404,
                    'status' => 'NOT FOUND',
                ], 404)
            );
        }

        return response()->json([
            'message' => 'Top artist displayed successfully',
            'code' => 200,
            'status' => 'OK',
            'result' => [
                'artist' => $topArtistData->artist,
                'artist_description' => $topArtistData->artist_description,
                'total_sales' => $topArtistData->total_sales,
            ],
        ]);
    }


    // get top ten artists based on total sales
    public function getTopTen(Request $request)
    {
        $salesByArtist = ArtistDiscogModel::select('artist', 'album', DB::raw('2022_sales as sales'))
        ->get()
        ->sortByDesc('sales')
        ->take(10);
        
        return response()->json([
            'message' => 'Top ten artist based on album sale displayed successfully',
            'code' => 200,
            'status' => 'OK',
            'results' => $salesByArtist,
        ]);
    }

    // get albums of artist
    public function allAlbumsByArtist($artist)
    {
        $artistData = ArtistDiscogModel::select('artist', 'artist_description')
            ->where('artist', 'LIKE', $artist)
            ->first();

        if (!$artistData) {
            abort(
                response()->json([
                    'message' => 'Artist not found',
                    'code' => 404,
                    'status' => 'NOT FOUND',
                ], 404)
            );
        }

        $albumsByArtist = ArtistDiscogModel::select('album', DB::raw('2022_sales as sales'), 'album_description')
            ->where('artist', 'LIKE', $artist)
            ->get();

        $result = [
            'artist' => $artistData->artist,
            'artist_description' => $artistData->artist_description,
            'albums' => [],
        ];

        foreach ($albumsByArtist as $data) {
            $album = $data->album;
            $sales = $data->sales;
            $albumDescription = $data->album_description;

            $result['albums'][] = [
                'album' => $album,
                'sales' => $sales,
                'album_description' => $albumDescription,
            ];
        }

        return response()->json([
            'message' => 'Artist and albums information displayed successfully',
            'code' => 200,
            'status' => 'OK',
            'result' => $result,
        ]);
    }



    public function updateArtist(Request $request, $id){
        $artistToBeUpdated = ArtistDiscogModel::findOrFail($id);
        if (!$artistToBeUpdated) {
            abort(
                response()->json([
                    'message' => "Artist not found!",
                    'status' => 'NOT FOUND',
                    'status_code' => 404,
                ], 404)
            );
        } 
        $update = $request->all();
        $artistToBeUpdated -> update($update);
        return response([
            'message' => "Artist update successfully",
            'status' => 'OK',
            'status_code' => 200,
            'results' => $artistToBeUpdated
        ], 200);
        
    }

    public function deleteArtist($id){
        $artistToBeDeleted = ArtistDiscogModel::findOrFail($id);

        if(!$artistToBeDeleted){
            abort(
                response()->json([
                    'message' => 'Artist not Found',
                    'code' => 404,
                    'status' => 'NOT FOUND',
                ], 404)
            );
        }

        $artistToBeDeleted->delete();
        return response()->json([
            'message' => 'Artist deleted successfully!',
            'status' => 'OK',
            'code' => 200,
        ]);
    }
}
