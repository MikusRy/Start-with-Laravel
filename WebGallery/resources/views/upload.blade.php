@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ Auth::user()->name }}
                            -</h2>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <div class="uploaddiv">
                        <form class="uploadform" action="{{URL::to('uploadfile')}}" method="post"
                              enctype="multipart/form-data">

                            <h1>Dodaj fotkę!</h1>
                            <hr class="hr">
                            <h5><b>Wybierz obraz do przesłania</b></h5>
                            <input class="margintop10 border-alx" type="file" name="file" id="file"
                                       accept="image/*">
                            <br>
                            <br>
                            <label>Opcje udostępnienia pliku:</label>
                            <br>
                            <select id="selectpublish" class="onselect" name="selectpublishimg">
                                <option value="publiczny">Publiczny</option>
                                <option value="prywatny">Prywatny</option>
                                <option value="zalogowani">Dla zarejestrowanych</option>
                                <option value="wybrani">Prywatny z wyjątkami</option>
                            </select>
                            <br>
                            <br>
                            <label>Zasada wykorzystania:</label>
                            <br>
                            <select id="selectlicence" class="onselect" name="selectlicenceimg">
                                <option value="Brak">Brak</option>
                                <option value="CC BY">Uznanie autorstwa</option>
                                <option value="CC BY-NC">Użycie niekomercyjne</option>
                            </select>
                            <br>
                            <br>
                            <input type="text" class="textbox" name="newname"
                                   placeholder="Tu możesz podać nową nazwę...">
                            <br>
                            <textarea class="textarea" name="comment" placeholder="A tu swój komentarz..."></textarea>
                            <br>
                            <h5><b>Wybierz galerię</b></h5>
                            <select id="selectgallery" class="onselect" name="selectgallery"
                                    onchange="gallerycheck()">
                                <option value="new">Nowa galeria</option>
                                @foreach($gallery as $item)
                                    <option value="{{$item->name}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <br>
                            <div id="newgallery"></div>
                            <br><input class="button margintop10" type="submit" value="Upload" name="submit">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">

                        </form>
                    </div>
                    <script>
                        gallerycheck();

                        function gallerycheck() {
                            var e = document.getElementById("selectgallery");
                            var value = e.options[e.selectedIndex].value;
                            if (value == 'new') {
                                document.getElementById('newgallery').innerHTML = '' +
                                    '<br>' +
                                    '<input class="textbox" type="text" name="newgallery" placeholder="Nazwa nowej galerii..." pattern=".{3,}">' +
                                    '<br>' +
                                    '<textarea class="textarea" name="info" placeholder="Jeśli chcesz, to tu wstaw opis galerii..."></textarea>' +
                                    '<br>\n' +
                                    '<label>Opcje udostępnienia albumu:</label>\n' +
                                    '<br>' +
                                    '<select id="selectpublish" class="onselect" name="selectpublishgallery">\n' +
                                    '<option value="publiczny">Publiczny</option>\n' +
                                    '<option value="prywatny">Prywatny</option>\n' +
                                    '<option value="zalogowani">Dla zarejestrowanych</option>\n' +
                                    '<option value="wybrani">Prywatny z wyjątkami</option>\n' +
                                    '</select><br>';
                            } else {
                                document.getElementById('newgallery').innerHTML = '';
                            }
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection
