@extends('layouts.app')

@section('custom_css')
    <link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bookmarks_index.css') }}" rel="stylesheet">
@endsection

@section('custom_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js" defer></script>
    <script src="{{ asset('js/chosen.js') }}" defer></script>
    @if (Auth::user()->admin)
        <script src="{{ asset('js/bookmark_index_admin.js') }}" defer></script>
    @else
        <script src="{{ asset('js/bookmark_index_user.js') }}" defer></script>
    @endif
@endsection

@section('content')
<div class="container">

    <div class="input-group">
        <input class="form-control" type="text" name="search" placeholder="Search" autocomplete="off" id="input_searchTerm">
        <div class="input-group-append">
            <select class="form-control form-control-chosen" title="Select search area" name="searchColumn" id="select_searchColumn">
                <option value="title" selected>Title</option>
                <option value="url">URL</option>
            </select>
        </div>
        <div class="input-group-append">
            <button class="btn btn-light" type="button" title="Expand filter" id="btn_expand"><img src="{{ asset('images/expand-more-black.png') }}"></img></button>
        </div>
    </div>
    
    <div class="input-group" id="select_div" style="display:none;">
        <select class="custom-select" name="tags[]" multiple="multiple" title="Select tags" id="select_searchTag" style="display:none;">
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->text }}</option>
            @endforeach
        </select>
        @if (Auth::user()->admin)
            <div class="input-group-append">
                <a class="btn btn-light" title="Add new bookmark" id="link_add" href="{{ route('bookmarks.create') }}"><img src="{{ asset('images/add.png') }}" style="color:white; width:18px"></img></a>
            </div>
        @endif
    </div>

    <hr>

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

    <div id="bookmark_list"></div>
</div>
@endsection
