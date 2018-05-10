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
                    <h1 align="center">Galeria: {{$galleryname->name}}</h1>
                    <details align="center">
                        <summary>Info</summary>
                        <form class="galleryform" action="{{URL::to('updategallery')}}" method="post">
                            <hr class="hr"/>
                            <label><b>Tytuł albumu</b></label>
                            <br>
                            @if ($galleryname->name == 'All')
                                <input class="textbox-simple" type="text"
                                       value="{{$galleryname->name}}" disabled>
                                <input type="hidden" value="{{$galleryname->name}}" name="galleryname">
                            @else
                                <input class="textbox-simple" type="text" name="galleryname"
                                       value="{{$galleryname->name}}">
                            @endif
                            <br>
                            <br>
                            <label><b>Opis albumu</b></label>
                            <br>
                            <textarea class="textarea-simple" name="galleryinfo"
                                      placeholder="Brak opisu...">{{$galleryname->info}}</textarea>
                            <br>
                            <div class="d-flex justify-content-around">
                                <div>Wyświetlenia: {{$galleryname->view}}</div>
                                <div>Like: {{$galleryname->like}}</div>
                                <div>Unlike: {{$galleryname->unlike}}</div>
                                <div>Data: {{$galleryname->created_at}}</div>
                            </div>
                            <br>
                            <br>
                            <label><b>Dostęp: </b></label>
                            <select id="gallerypublish" class="onselect" name="gallerypublish">
                                <option value="publiczny" <?php if ($galleryname->publish == 'publiczny') {
                                    echo 'selected';
                                } ?> >Publiczny
                                </option>
                                <option value="prywatny" <?php if ($galleryname->publish == 'prywatny') {
                                    echo 'selected';
                                } ?> >Prywatny
                                </option>
                                <option value="zalogowani" <?php if ($galleryname->publish == 'zalogowani') {
                                    echo 'selected';
                                } ?> >Dla zarejestrowanych
                                </option>
                                <option value="wybrani" <?php if ($galleryname->publish == 'wybrani') {
                                    echo 'selected';
                                } ?> >Prywatny z wyjątkami
                                </option>
                            </select>
                            <br>
                            <br>
                            <br>
                            <input type="hidden" name="galleryid" value="{{$galleryname->id}}">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                            <input class="button" type="submit" name="edit" value="Zapisz zmiany">
                            @if ($galleryname->name != 'All')
                                <input class="button" type="submit" name="del" value="Usuń album">
                            @endif
                        </form>
                    </details>
                    <hr class="hr">
                    @if($images->count() != 0)
                            <table>
                                <thead>
                                <tr>
                                    <th>Obraz</th>
                                    <th>Info</th>
                                    <th>Akcje</th>
                                </tr>
                                </thead>
                                @foreach($images as $item)
                                <tbody class="bghover">
                                    <tr>
                                        <td>
                                            <div class="gallerypanel">
                                                <?php $path = Auth::user()->usercode . '/' . $item->file_name; ?>
                                                <img class="showimg" src="{{ asset($path) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <ul>
                                                <li><b>{{$item->pic_name}}</b></li>
                                                <hr class="hr">
                                                <li>Wyświetlenia: {{$item->view}}</li>
                                                <li>Like: {{$item->like}}</li>
                                                <li>Unlike: {{$item->unlike}}</li>
                                            </ul>
                                        </td>
                                        <td>

                                            <form action="{{URL::to('updateimg')}}"
                                                  method="post">
                                                <input type="hidden" name="imageid" value="{{$item->id}}">
                                                <input type="hidden" name="imagename" value="{{$item->pic_name}}">
                                                <input type="hidden" name="galleryid" value="{{$galleryname->id}}">
                                                <input type="hidden" value="{{csrf_token()}}" name="_token">
                                                <ul>
                                                    <li>
                                                        {{--przekazuję do updateimg id obrazu po czym zwracam odpowiedni widok--}}
                                                        <input class="button" type="submit" name="view"
                                                               value="Wyświetl">
                                                    </li>
                                                        <li>
                                                            {{--przekazuję do updateimg dane obrazu po czym usuwam z bazy oraz folderu--}}
                                                            <input class="button" type="submit" name="del" value="Usuń">
                                                        </li>
                                                </ul>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                    @else
                        <div align="center">
                            <br>
                            <br>
                            <h3>Brak obrazów w albumie...</h3>
                            <br>
                            <br>
                        </div>
                    @endif
                </div>
            </div>
        </div>
@endsection
