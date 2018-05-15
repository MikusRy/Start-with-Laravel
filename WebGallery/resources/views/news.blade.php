@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header"><h2 style="text-align: center">- Co nowego? -</h2>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    {{--body--}}
                    <div align="center">
                        @foreach($images as $image)
                            <a href="{{'look/' .$image->usercode. '/' . $image->pic_name}}">
                                <div class="gallerypanel">
                                <?php $path = $image->usercode . '/' . $image->file_name; ?>
                                <img class="inlookimg" src="{{ asset($path) }}" style="min-width: 300px">
                            </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
