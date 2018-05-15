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
                                    <form action="{{URL::to('updateimg')}}" method="post" name="form2">
                                        <input type="hidden" name="imageid" value="{{$image->id}}">
                                        <input type="hidden" name="imagename" value="{{$image->pic_name}}">
                                        <input type="hidden" name="viewpage" value="true">
                                        <input type="hidden" value="{{csrf_token()}}" name="_token">
                                        <button class="button" type="button" style="width: 300px;"
                                                onclick="openpic('{{$path}}')">
                                            Powiększ
                                        </button>
                                        <button class="button" style="width: 300px;" type="button" name="edit" onclick="editdata()">
                                            Zapisz zmiany
                                        </button>
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
                                    <input id="imgname" class="textbox-simple" type="text" value="{{$image->pic_name}}">
                                    <ul style="text-align: left">
                                        <li>Wyświetlenia: {{$image->view}}</li>
                                        <li>Like: {{$image->like}}</li>
                                        <li>Unlike: {{$image->unlike}}</li>
                                        <li>Data: {{$image->created_at}}</li>
                                    </ul>
                                    <br>
                                    <label>Opcje udostępnienia pliku:</label>
                                    <br>
                                    <select id="imgpublish" class="onselect" name="imgpublish">
                                        <option value="publiczny" <?php if ($image->publish == 'publiczny') {
                                            echo 'selected';
                                        } ?> >Publiczny
                                        </option>
                                        <option value="prywatny" <?php if ($image->publish == 'prywatny') {
                                            echo 'selected';
                                        } ?> >Prywatny
                                        </option>
                                        <option value="zalogowani" <?php if ($image->publish == 'zalogowani') {
                                            echo 'selected';
                                        } ?> >Dla zarejestrowanych
                                        </option>
                                        <option value="wybrani" <?php if ($image->publish == 'wybrani') {
                                            echo 'selected';
                                        } ?> >Prywatny z wyjątkami
                                        </option>
                                    </select>
                                    <br>
                                    <label>Zasada wykorzystania:</label>
                                    <br>
                                    <select id="selectlicence" class="onselect" name="selectlicenceimg">
                                        <option value="Brak" <?php if ($image->licence == 'Brak') {
                                            echo 'selected';
                                        } ?> >Brak
                                        </option>
                                        <option value="CC BY" <?php if ($image->licence == 'CC BY') {
                                            echo 'selected';
                                        } ?> >CC BY
                                        </option>
                                        <option value="CC BY-NC" <?php if ($image->licence == 'CC BY-NC') {
                                            echo 'selected';
                                        } ?> >CC BY-NC
                                        </option>
                                    </select>
                                    <br>
                                    <label>Komentarz:</label>
                                    <br>
                                    <select id="comments" class="onselect" name="comments">
                                        <option value="0" <?php if ($image->comments == '0') {
                                            echo 'selected';
                                        } ?> >Włącz komentarze
                                        </option>
                                        <option value="1" <?php if ($image->comments == '1') {
                                            echo 'selected';
                                        } ?> >Wyłącz komentarze
                                        </option>
                                    </select>

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
                                      placeholder="Brak opisu...">{{$image->info}}</textarea>
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
