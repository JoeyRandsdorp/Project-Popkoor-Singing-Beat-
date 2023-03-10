<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Validation\Rules\File;

class AdminPhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([                                                                                    //controle type gegevens van input
            'image' => 'required', File::types(['gif', 'GIF', 'jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG']),
            'title' => 'required', 'string', 'max:255'
        ]);

        if(request()->file('image') === null){
            $image = null;
        } else {
            $image = request()->file('image')->store('photos');
        }

        $photoAlbumId = $request['album_id'];                                                                     //detail functie voor photoalbum

        Photo::create([
            'photo_album_id' => $photoAlbumId,                                                                   //create functie
            'image' => $image,
            'title' => $request['title']
        ]);

        return redirect()->route('photo_albums.show', $photoAlbumId);
    }

    public function destroy($id)                                                                                //delete functie
    {
        $photo = Photo::find($id);
        $photo->delete();
        return redirect()->route('photo_albums.index');
    }
}
