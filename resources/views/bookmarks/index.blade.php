@extends('layouts.app')

@section('custom_js')
    <script src="{{ asset('js/custom.js') }}" defer></script>
@endsection

@section('content')
<div class="container">

    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search in ..." autocomplete="off" id="searchTerm">
        <div class="input-group-append">
            <select class="custom-select" name="searchColumn" id="searchColumn" style="margin-left:5px">
                <option value="title" selected>Title</option>
                <option value="keywords">Keywords</option>
                <option value="url">URL</option>
            </select>
        </div>
    </div>

    <br>
        
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
<div id="test1"></div>
    {{-- @if(!empty($bookmarks))
        @foreach($bookmarks as $bookmark)
        <div class="card" style="background-color: rgba(255,255,255,0.5);">
            <div class="card-body" style="display:inline">
                <form method="POST" action="{{ route('bookmarks.destroy', $bookmark->id) }}">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger float-right" style="color:white;margin-top:10px">Delete</button>
                </form>
                <h3><a href="{{ $bookmark->url }}" target="_blank">{{ $bookmark->title }}</a></h3>
                <small>{{ $bookmark->url }}</small>
                <p style="margin-bottom:0px;margin-top:5px;cursor:pointer">github &nbsp nodejs &nbsp rest-api</p>
            </div>
        </div>
        <br>
        @endforeach
    @else
        <p>No bookmarks found</p>
        <br>
    @endif --}}

    <a class="btn btn-primary" href="{{ route('bookmarks.create') }}">Create Bookmark</a>
</div>
@endsection
