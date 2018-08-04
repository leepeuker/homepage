@extends('layouts.app')

@section('custom_css')
<link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
@endsection

@section('custom_js')
    <script src="{{ asset('js/chosen.js') }}" defer></script>
    <script src="{{ asset('js/bookmarks_create.js') }}" defer></script>
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
            <h3 style="text-align: center;">Create bookmark</h3>
            <hr>
            <form method="POST" action="{{ route('bookmarks.store') }}">
                @csrf

                <div class="form-group">
                    <label for="input_title">Title</label>
                    <input type="text" name="title" class="form-control" id="input_title">
                    @if ($errors->has('title'))
                        <p style="color:red;font-weight:bold;font-size:14px">{{ $errors->first('title') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="input_url">URL</label>
                    <input type="text" name="url" class="form-control" id="input_url">
                    @if ($errors->has('url'))
                        <p style="color:red;font-weight:bold;font-size:14px">{{ $errors->first('url') }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="select_keywords">Keywords</label>
                    <select class="custom-select" name="keywords[]" multiple="multiple" id="select_keywords" style="display:none" data-placeholder=" ">
                        @foreach($keywords as $keyword)
                            <option value="{{ $keyword->id }}">{{ $keyword->word }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('keywords'))
                    {{ $errors->first('keywords') }}
                        <p style="color:red;font-weight:bold;font-size:14px">{{ $errors->first('keywords') }}</p>
                    @endif
                </div>

                <button class="btn btn-primary float-right" type="submit">Create </button>
                <a class="btn btn-dark float-right" style="margin-right:5px;color:white" href="{{ url('/bookmarks') }}">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
