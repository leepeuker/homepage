@extends('layouts.app')

@section('custom_css')
<link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
@endsection

@section('custom_js')
    <script src="{{ asset('js/chosen.js') }}" defer></script>
    <script src="{{ asset('js/bookmarks_edit.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
    
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

    <form method="POST" action="{{ route('bookmarks.update', ['bookmark' => $bookmark]) }}">
        @csrf
        <input type="hidden" name="_method" value="PATCH">

        <div class="form-group">
            <label for="input_url">URL</label>
            <input type="text" name="url" class="form-control" id="input_url" value="{{ $bookmark->url }}">
            @if ($errors->has('url'))
            {{ $errors->first('url') }}
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="input_title">Title</label>
            <input type="text" name="title" class="form-control" id="input_title" value="{{ $bookmark->title }}">
            @if ($errors->has('title'))
            {{ $errors->first('title') }}
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('title') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="input_title">Keywords</label>
            <select class="custom-select" name="keywords[]" multiple="multiple" id="select_keywords" style="display:none">
            @foreach($keywords as $keyword)
                <option value="{{ $keyword->id }}" @foreach($bookmark->keywords as $bookmarkKeyword) {{ $bookmarkKeyword->id == $keyword->id ? "selected" : "" }} @endforeach>
                    {{ $keyword->word }}
                </option>
            @endforeach
            </select>
            @if ($errors->has('keywords'))
            {{ $errors->first('keywords') }}
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('keywords') }}</strong>
                </span>
            @endif
        </div>

        <button class="btn btn-primary" type="submit">Save Bookmark</button>
    </form>
</div>
@endsection
