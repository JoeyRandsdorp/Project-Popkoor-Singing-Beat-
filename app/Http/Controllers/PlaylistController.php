<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Playlist;
use App\Models\PlaylistSong;

class PlaylistController extends Controller
{
    public static function getSongData($id)
    {
        $song = DB::table('songs')
            ->where('id', '=', $id)
            ->first();
        return $song;
    }

    public function index()
    {
        $user_id = auth()->user()?->id;
        $playlists = DB::table('playlists')
            ->where('user_id', '=', $user_id)
            ->orderByRaw('title ASC')
            ->get();
        return view('playlists.playlists', ['playlists' => $playlists]);
    }

    public function create()                                                                    //stuurt naar create playlist formulier
    {
        return view('playlists.create');
    }

    public function store(Request $request)                                                     //create playlist function
    {
        $request->validate([
            'title' => 'required', 'string', 'max:255'
        ]);

        $user_id = auth()->user()?->id;

        Playlist::create([
            'title' => $request['title'],
            'user_id' => $user_id
        ]);

        return redirect()->route('playlists.index');
    }

    public function show($id)                                                               //playlist details function, show
    {
        $user_id = auth()->user()?->id;

        $playlist = Playlist::find($id);

        if($user_id !== $playlist->user_id){
            return redirect()->route('playlists.index');
        }
        else {
            $playlist_songs = DB::table('playlist_songs')
                ->where('playlist_id', '=', $id)
                ->get();

            return view('playlists.details', compact('playlist'), ['playlist_songs' => $playlist_songs]);
        }
    }

    public function edit($id)                                                                                       //hier wordt een specifieke playlist gevonden
    {
        $user_id = auth()->user()?->id;                                                                             //met de user_id

        $playlist = Playlist::find($id);

        if($user_id !== $playlist->user_id){
            return redirect()->route('playlists.index');
        }
        else {
            return view('playlists.edit', compact('playlist'));
        }
    }

    public function update(Request $request, $id)                                                           //Hier wordt de edit gevalideerd.
    {
        $request->validate([
            'title' => 'required', 'string', 'max:255'
        ]);

        $user_id = auth()->user()?->id;

        $playlist = Playlist::find($id);

        if($user_id !== $playlist->user_id){
            return redirect()->route('playlists.index');
        }
        else {
            $playlist->title = $request->get('title');
            $playlist->save();
            return redirect()->route('playlists.index');
        }
    }

    public function destroy($id)                                                                        //playlist delete function
    {
        $user_id = auth()->user()?->id;

        $playlist = Playlist::find($id);

        if($user_id !== $playlist->user_id){
            return redirect()->route('playlists.index');
        }
        else {
            $playlist_id = $playlist->id;

            $playlist_songs = PlaylistSong::where('playlist_id', $playlist_id);
            $playlist_songs->delete();

            $playlist->delete();

            return redirect()->route('playlists.index');
        }
    }
}
