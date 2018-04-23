@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ Auth::user()->name }}
                            -</h2>
                    </div>
                    <div class="uploaddiv">
                        <form class="uploadform" action="{{URL::to('uploadfile')}}" method="post"
                              enctype="multipart/form-data">
                            <h1>Dodaj fotkę!</h1>
                            <hr class="hr">
                            <label>Wybierz obraz do przesłania:</label>
                            <br><input class="margintop10 border-alx" type="file" name="file" id="file"
                                       accept="image/*">
                            <br>
                            <br><input type="text" class="button" name="newname"
                                       placeholder="Wpisz coś by zmienić nazwę pliku.">
                            <br>
                            <br>
                            <div class="checkbox">
                                <label style="cursor: pointer"><input style="cursor: pointer" type="checkbox"
                                                                      name="priv" class="cur"> Zaznacz obraz jako
                                    prywatny</label>
                            </div>
                            <br>
                            <label>Wybierz galerię:</label>
                            <br>
                            <select id="selectgallery" class="onselect" onselect="Wszystkie" name="selectgallery"
                                    onchange="gallerycheck(this)">
                                <option value="all">Wszystkie</option>
                                <option value="new">Nowa galeria</option>
                            </select>
                            <br>
                            <div id="newgallery"></div>
                            <br><input class="button margintop10" type="submit" value="Upload" name="submit">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                        </form>
                    </div>
                    <script>
                        function gallerycheck() {
                            var e = document.getElementById("selectgallery");
                            var value = e.options[e.selectedIndex].value;
                            if (value == 'new') {
                                document.getElementById('newgallery').innerHTML = '' +
                                    '<br>' +
                                    '<input type="text" name="newgallery" class="button" placeholder="Nazwa galerii">' +
                                    '<br>';
                            } else {
                                document.getElementById('newgallery').innerHTML = '';
                            }
                        }
                    </script>
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
