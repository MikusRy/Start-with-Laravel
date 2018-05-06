@extends('layouts.app')

@section('content')

    <!-- Session getting default path -->
    <?php
    if (session()->has('path')) {
    } else {
        session()->put('path', 'http://localhost/Start-with-Laravel/WebGallery/public');
    }
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ Auth::user()->name }}
                            -</h2>
                    </div>
                    <br>
                    <br>
                    <h1 align="center">Albumy ze zdjęciami!</h1>
                    <hr class="hr">
                    <div class="cont-alx">
                        @if($gallery->count()!=0)
                            @foreach($gallery as $item)
                                <div class="gallerydiv">
                                    <a class="a" href="{{session()->get('path')}}/gallery/{{$item->id}}">
                                        <div class="galleryname">
                                            {{$item->name}}
                                        </div>
                                        <div class="gallerypanel">
                                            Wyświetlenia: {{$item->view}}<br>
                                            Like: {{$item->like}}<br>
                                            Unlike: {{$item->unlike}}
                                        </div>
                                        <div class="galleryinfo">
                                            Obrazy: {{$item->items}}
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <h3 style="padding:50px 50px 25px 50px;">Aktualnie brak albumów...</h3>
                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
