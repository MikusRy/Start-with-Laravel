@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    {{--
                        Autor galerii
                        --}}
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ $image->name }}
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
                    <a href="{{asset('look/' . $image->usercode . '-' . $gallery->id)}}"><h1 align="center">Album: {{ $gallery->name }}</h1></a>
                    <hr class="hr">
                    <div class="cont-alx">
                        <table>
                            <td>
                                {{--
                                Wyświetlenie miniaturki z przyciskiem powiększenia
                                --}}
                                <div class="gallerypanel">
                                    <?php $path = '/' . $image->usercode. '/' . $image->file_name;
                                    $path = asset($path);
                                    ?>
                                    <img class="showimg" src="{{ asset($path)}}" style="min-width: 300px">
                                        <button class="button" type="button" style="width: 300px;"
                                                onclick="openpic('{{$path}}')">
                                            Powiększ
                                        </button>
                                </div>
                            </td>
                            <td>
                                {{--
                                Nazwa zdjęcia
                                --}}
                                <div>
                                    <input id="imgname" class="textbox-view" type="text" value="{{$image->pic_name}}" disabled>
                                    <ul style="text-align: left">
                                        <li>Wyświetlenia: {{$image->view}}</li>
                                        <li>Like: {{$image->like}}</li>
                                        <li>Unlike: {{$image->unlike}}</li>
                                        <li>Data: {{$image->created_at}}</li>
                                    </ul>
                                    <br>
                                    <label>Opcje udostępnienia pliku:</label>
                                    <br>
                                    <h5>{{$image->publish}}</h5>
                                    <br>
                                    <label>Zasada wykorzystania:</label>
                                    <br>
                                    <h5>{{$image->licence}}</h5>
                                </div>
                            </td>
                        </table>

                        {{--
                        Okno z opisem zdjęcia
                        --}}
                        <div style="width: 85%; text-align: left">
                            <label class="paddingL20"><h4>Opis:</h4></label>
                            <br>
                            <textarea id="imginfo" class="textarea-view" name="imginfo"
                                      placeholder="Brak opisu..." disabled>{{$image->info}}</textarea>
                        </div>
                    </div>
                    <br>
                    @if( $image->comments == 0 AND Auth::user()->name != '')
                        <div style="width: 95%; margin: 2%;">
                            <h4>Sekcja komentarzy</h4>
                            <hr class="hr">
                            {{--
                            Div dla komentarzy
                            --}}
                            <div>
                                <div style="text-indent: 1.5em">Autor: {{ Auth::user()->name }}</div>
                                <textarea class="textarea-view" name="imginfo" placeholder="Twój komentarz..."></textarea>
                                <div style="width: 100%; text-align: right">
                                    <button class="button" style="width: 150px; margin: 0px 20px">Dodaj komentarz
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

        function openpic(path) {
            console.log(path);
            window.open(path, "_blank");
        }

        function editdata() {
            $.ajax({
                type : "POST",
                url : "{{URL::to('editimg')}}",
                data : {
                    'id' : '{{$image->id}}',
                    'name': document.getElementById('imgname').value,
                    'info': document.getElementById('imginfo').value,
                    'licence': document.getElementById('selectlicence').value,
                    'publish': document.getElementById('imgpublish').value,
                    'comments': document.getElementById('comments').value,
                    '_token' : '{{csrf_token()}}',
                },
                success : function (result) {
                    console.log(result);
                    window.location.reload();
                }
            });
        }

    </script>
@endsection
