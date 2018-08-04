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

    <div class="card" style="background-color: rgba(255,255,255,0.8);">
        <div class="card-body">
            <h3 style="text-align: center;">Edit bookmark</h3>
            <hr>
            <form method="POST" action="{{ route('bookmarks.update', ['bookmark' => $bookmark]) }}">
                @csrf
                <input type="hidden" name="_method" value="PATCH">

                <div class="form-group">
                    <label for="input_title">Title</label>
                    <input type="text" name="title" class="form-control" id="input_title" value="{{ $bookmark->title }}">
                    @if ($errors->has('title'))
                        <p style="color:red;font-weight:bold;font-size:14px">{{ $errors->first('title') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="input_url">URL</label>
                    <input type="text" name="url" class="form-control" id="input_url" value="{{ $bookmark->url }}">
                    @if ($errors->has('url'))
                        <p style="color:red;font-weight:bold;font-size:14px">{{ $errors->first('url') }}</p>
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
                        <p style="color:red;font-weight:bold;font-size:14px">{{ $errors->first('keywords') }}</p>
                    @endif
                </div>
                
                <button class="btn btn-primary float-right" type="submit" style="margin-top:5px;margin-left:5px">Save</button>
                <a class="btn btn-dark float-right" style="margin-top:5px;color:white" href="{{ url('/bookmarks') }}">Back</a>
                <small style="position: absolute;bottom: 27px;cursor:default">Created at {{ $bookmark->created_at }}</small>
            </form>
        </div>
    </div>

</div>
@endsection
