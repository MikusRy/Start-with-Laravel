@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ Auth::user()->name }} -</h2>
                    </div>
                    <br>
                    <br>
                    <h1 align="center">Albumy ze zdjęciami!</h1>
                    <hr class="hr">
                    <div class="cont-alx">
                        <div class="gallerydiv">
                            <div class="gallerypic">

                            </div>
                            <div class="galleryname">
                                Moje fotki
                            </div>
                        </div>
                        <div class="gallerydiv">
                            <div class="gallerypic">

                            </div>
                            <div class="galleryname">
                                Moje fotki
                            </div>
                        </div>
                        <div class="gallerydiv">
                            <div class="gallerypic">

                            </div>
                            <div class="galleryname">
                                Moje fotki
                            </div>
                        </div>
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
