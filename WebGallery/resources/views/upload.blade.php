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
                            <label>Wybierz obraz do przesłania:</label>
                            <br><input class="margintop10 border-alx" type="file" name="file" id="file"
                                       accept="image/*">
                            <br>
                            <br>
                            <div class="checkbox">
                                <label style="cursor: pointer"><input style="cursor: pointer" type="checkbox"
                                                                      name="picpriv" class="cur"> Zaznacz obraz jako
                                    prywatny</label>
                            </div>
                            <input type="text" class="textarea" name="newname"
                                   placeholder="Tu możesz podać nową nazwę...">
                            <br>
                            <textarea class="textarea" name="comment" placeholder="A tu swój komentarz..."></textarea>
                            <br>
                            <label>Wybierz galerię:</label>
                            <br>
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
                                    '<input class="textarea" type="text" name="newgallery" placeholder="Nazwa galerii...">' +
                                    '<br>' +
                                    '<textarea class="textarea" name="info" placeholder="Jeśli chcesz, to tu wstaw opis galerii..."></textarea>' +
                                    '<div class="checkbox">' +
                                    '<label style="cursor: pointer">' +
                                    '<input style="cursor: pointer" type="checkbox" name="gallerypriv" class="cur"> Zaznacz jako galeria prywatna' +
                                    '</label></div>';
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
