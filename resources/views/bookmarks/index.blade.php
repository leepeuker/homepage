@extends('layouts.app')

@section('custom_css')
<link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
<link href="{{ asset('css/bookmarks_index.css') }}" rel="stylesheet">
@endsection

@section('custom_js')
    <script src="{{ asset('js/bookmark_index.js.js') }}" defer></script>
    <script src="{{ asset('js/chosen.js') }}" defer></script>
@endsection

@section('content')
<div class="container">

    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search" autocomplete="off" id="input_searchTerm">
        <select class="custom-select" name="keywords[]" multiple="multiple" id="select_searchTerm" style="display:none">
            @foreach($keywords as $keyword)
                <option value="{{ $keyword->id }}">{{ $keyword->word }}</option>
            @endforeach
        </select>
        <div class="input-group-append">
            <select  class="form-control form-control-chosen" name="searchColumn" id="select_searchColumn" style="margin-left:5px">
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

    <a class="btn btn-primary" href="{{ route('bookmarks.create') }}">Create Bookmark</a>
</div>
@endsection
