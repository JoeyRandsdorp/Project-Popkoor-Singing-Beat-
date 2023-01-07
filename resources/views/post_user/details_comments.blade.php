@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>{{$post->title}}</h1>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <p>{{$post->description}}</p>
                    </div>
                    <div class="card-image">
                        <img style="width: 50%;" src="{{ asset('storage/'. $post->thumbnail) }}" alt="Foto">
                    </div>
                    <div>
                        <embed src="{{ asset('storage/'. $post->file) }}">
                    </div>
                    <div>
                        <video width="320" height="240" controls>
                            <source src="{{ asset('storage/'. $post->video) }}">
                        </video>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <form action="{{route('comments.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="comment" class="form-label">Bijschrift bericht</label>
                        <input id="comment"
                               type="text"
                               name="comment"
                               class="@error('comment') is-invalid @enderror form-control"
                               value="{{old('comment')}}"/>
                        @error('comment')
                        <span>{{$message}}</span>
                        @enderror
                    </div>
                    <div>
                        <input id="date" type="hidden" name="date" value="<?php echo date("Y-m-d") ?>">
                    </div>
                    <div>
                        <input id="user_id" type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    </div>
                    <div>
                        <input id="post_id" type="hidden" name="post_id" value="{{ $post->id }}">
                    </div>
                    <br><br>
                    <div>
                        <input type="submit" value="Plaats bericht">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <div>
                @foreach($comments as $comment)
                    @php
                    $comment_user = App\Http\Controllers\CommentController::users($comment->user_id);
                    @endphp
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <p>
                                {{$comment_user}} zei op {{date('d-m-Y', strtotime($comment->date))}}: {{$comment->comment}}
                            </p>
                        </div>
                        @if(auth()->user()?->id === $comment->user_id)
                            <div class="col">
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Verwijderen</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
