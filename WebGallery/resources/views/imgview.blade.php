@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    {{--
                        Autor galerii
                        --}}
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ Auth::user()->name }}
                            -</h2></div>
                    <br>
                    {{--
                     Info div
                    --}}
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <br>
                    <h1 align="center">Album: {{ $gallery->name }}</h1>
                    <hr class="hr">
                    <div class="cont-alx">
                        <table>
                            <td>
                                {{--
                                Wyświetlenie miniaturki z przyciskiem powiększenia
                                --}}
                                <div class="gallerypanel">
                                    <?php $path = Auth::user()->usercode . '/' . $image->file_name; ?>
                                    <img class="showimg" src="{{ asset($path) }}" style="min-width: 300px">
                                    <button class="button" type="button" style="width: 300px;"
                                            onclick="openpic('{{$path}}')">
                                        Powiększ
                                    </button>
                                    <form action="{{URL::to('updateimg')}}" method="post">
                                        <input type="hidden" name="imageid" value="{{$image->id}}">
                                        <input type="hidden" name="imagename" value="{{$image->pic_name}}">
                                        <input type="hidden" name="viewpage" value="true">
                                        <input type="hidden" value="{{csrf_token()}}" name="_token">

                                        <button class="button" style="width: 300px;" type="submit" name="edit">
                                            Edytuj dane zdjęcia
                                        </button>
                                    </form>
                                    <form action="{{URL::to('updateimg')}}" method="post">
                                        <input type="hidden" name="imageid" value="{{$image->id}}">
                                        <input type="hidden" name="imagename" value="{{$image->pic_name}}">
                                        <input type="hidden" name="viewpage" value="true">
                                        <input type="hidden" value="{{csrf_token()}}" name="_token">

                                        <button class="button" style="width: 300px;" type="submit" name="del">
                                            Usuń zdjęcie
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                {{--
                                Nazwa zdjęcia
                                --}}
                                <div>
                                    <h3>{{$image->pic_name}}</h3>
                                    <ul style="text-align: left">
                                        <li>Wyświetlenia: {{$image->view}}</li>
                                        <li>Like: {{$image->like}}</li>
                                        <li>Unlike: {{$image->unlike}}</li>
                                        <li>Data: {{$image->created_at}}</li>
                                    </ul>
                                </div>
                            </td>
                        </table>

                        {{--
                        Okno z opisem zdjęcia
                        --}}
                        <div style="width: 85%; text-align: left">
                            <label class="paddingL20"><h4>Opis:</h4></label>
                            <br>
                            <textarea class="textarea-view" name="imginfo"
                                      placeholder="Brak opisu..." disabled>{{$image->info}}</textarea>
                        </div>
                    </div>
                    <br>
                    @if( $image->comments == 0 )
                        <div style="width: 95%; margin: 2%;">
                            <h4>Sekcja komentarzy</h4>
                            <hr class="hr">
                            {{--
                            Div dla komentarzy
                            --}}
                            <div>
                                <div style="text-indent: 1.5em">Napisał: Adam</div>
                                <textarea class="textarea-view" name="imginfo" placeholder="Brak komentarzy..."
                                          disabled>{{$image->info}}</textarea>
                                <div style="width: 100%; text-align: right">
                                    <button class="button" style="width: 150px; margin: 0px 20px">Usuń komentarz
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <br>
                    <br>

                </div>
            </div>
        </div>
    </div>
    <script>
        function passcheck() {
            var pass1 = document.getElementById('newpass1');
            var pass2 = document.getElementById('newpass2');
            var passwd = document.getElementById('passwd');
            if (pass1.value != pass2.value) {
                console.log(pass1.value + '!=' + pass2.value);
                pass2.style.background = '#ffa172';
                passwd.disabled = true;
            } else {
                console.log(pass1.value + '==' + pass2.value);
                pass2.style.background = '#b0ffaa';
                passwd.disabled = false;
            }
        }

        function openpic(path) {
            console.log(path);
            window.open(path, "_blank");
        }

        function gallerychenge() {
            var gselect = document.getElementById('gallerychenge');
            var ginputfaker = document.getElementById('gallerynamefaker');
            var ginput = document.getElementById('galleryname');
            ginputfaker.value = gselect.value;
            ginput.value = gselect.value;
            console.log(ginput.value + ' = ' + gselect.value);
        }

    </script>
@endsection
