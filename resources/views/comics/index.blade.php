@extends('layouts.app')

@section('custom_css')
@endsection

@section('custom_js')
@endsection

@section('content')
<div class="container">
    @if(count($comics) > 0)
        @php($i = 0)
        @foreach($comics as $comic)
            <div class="card col" style="width: 18rem; padding:0">
                <img class="card-img-top" src="/storage/comic_covers/{{$comic->cover}}" alt="{{$comic->cover}}">
                <div class="card-body">
                    <h5 class="card-title">{{$comic->title}}</h5>
                    {{-- <p class="card-text">{{$comic->description}}</p> --}}
                </div>
            </div>
        @endforeach
        {{$comics->links()}}
    @else
        <p>No posts found</p>
    @endif
</div>
@endsection
