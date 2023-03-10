@extends('layouts.app')

@section('title', 'Bewerk: ' . $playlist->title)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="/playlists">< Terug</a>
            <br><br>
            <h1>Bewerk de playlist {{$playlist->title}}</h1>
            <form action="{{route('playlists.update', $playlist->id)}}" method="post" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div>
                    <label for="title" class="form-label">Titel</label>
                    <input id="title"
                           type="text"
                           name="title"
                           class="@error('title') is-invalid @enderror form-control"
                           value="{{$playlist->title}}"/>
                    @error('title')
                    <span>{{$message}}</span>
                    @enderror
                </div>
                <br><br>
                <div>
                    <input type="submit" value="Wijzigingen opslaan">
                </div>
            </form>
        </div>
    </div>
@endsection
