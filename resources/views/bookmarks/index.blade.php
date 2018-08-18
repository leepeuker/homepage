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

    <div class="input-group" style="margin-bottom:15px">
        <input type="text" name="search" class="form-control" placeholder="Search" autocomplete="off" id="input_searchTerm">
        <select class="custom-select" name="tags[]" multiple="multiple" id="select_searchTerm" style="display:none">
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->text }}</option>
            @endforeach
        </select>
        <div class="input-group-append">
            <select  class="form-control form-control-chosen" name="searchColumn" id="select_searchColumn" style="margin-left:5px">
                <option value="title" selected>Title</option>
                <option value="tags">Tags</option>
                <option value="url">URL</option>
            </select>
        </div>
        @if (Auth::user()->admin)
            <div class="input-group-append">
                <a class="btn btn-light" title="Add new bookmark" id="link_add" href="{{ route('bookmarks.create') }}"><img src="{{ asset('images/add.png') }}" style="color:white; width:18px"></img></a>
            </div>
        @endif
    </div>
        
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
