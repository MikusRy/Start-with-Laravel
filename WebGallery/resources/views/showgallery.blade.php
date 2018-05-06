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
                        <form class="galleryform">
                            <hr class="hr"/>
                            <label><b>Tytuł albumu</b></label>
                            <br>
                            <input class="textbox-simple" type="text" value="{{$galleryname->name}}">
                            <br>
                            <br>
                            <label><b>Opis albumu</b></label>
                            <br>
                            <textarea class="textarea-simple"
                                      placeholder="Brak opisu...">{{$galleryname->info}}</textarea>
                            <br>
                            <div class="d-flex justify-content-around">
                                <div>Obrazy: {{$galleryname->items}}</div>
                                <div>Wyświetlenia: {{$galleryname->view}}</div>
                                <div>Like: {{$galleryname->like}}</div>
                                <div>Unlike: {{$galleryname->unlike}}</div>
                            </div>
                            <br>
                            <div class="checkbox"><label style="cursor: pointer" class="colorhover">
                                    <input <?php
                                           if($galleryname->public == 0){echo 'checked';}
                                           ?> style="cursor: pointer" type="checkbox" name="gallerypriv" class="cur">
                                    Zaznacz jako galeria prywatna</label></div>
                            <br>
                            <button class="button" type="submit">Zapisz zmiany</button>
                            <button class="button" type="submit">Usuń album</button>
                        </form>
                    </details>
                    <hr class="hr">
                    <form class="galleryImage-container">
                        <table>
                            <thead>
                            <tr>
                                <th>Obraz</th>
                                <th>Info</th>
                                <th>Akcje</th>
                            </tr>
                            </thead>
                            <tbody class="bghover">
                            @foreach($images as $item)
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
                                            <li>Wyświetlenia: {{$item->view}}</li>
                                            <li>Like: {{$item->like}}</li>
                                            <li>Unlike: {{$item->unlike}}</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>
                                                <button class="gallerybutton">Wyświetl</button>
                                            </li>
                                            <li>
                                                <button class="gallerybutton">Edytuj</button>
                                            </li>
                                            <li>
                                                <button class="gallerybutton">Usuń</button>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
@endsection
