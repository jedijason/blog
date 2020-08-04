@extends('layouts.app')

@section('content')

<h1>Posts</h1>
    @if(count($posts) > 0)
            <div class = "card">
                <ul class="list-group list-group-flush">
                    @foreach($posts as $post)
                        <div class="row">
                            <div class="col-md-4">
                                <img style="width: 25%" src="/storage/cover_images/{{$post->cover_image}}" alt="">
                            </div>
                            <div class="col-md-8">
                                <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                                <small>Written on {{$post->created_at}}</small>
                                @foreach ($users as $user)
                                        @if($user->id == $post->user_id)
                                                <h6>Written by {{$user->name}}</h6>
                                            @break
                                        @endif
                                @endforeach
                            </div>
                        </div>    
                    @endforeach
                </ul>
            </div>
    @else

    @endif

@endsection