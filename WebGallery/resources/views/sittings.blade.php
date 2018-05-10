@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header"><h2 style="text-align: center">- Gallered by: {{ Auth::user()->name }}
                            -</h2>
                    </div>
                    <br>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                    <br>
                    <h1 align="center">Ustawienia</h1>
                    <hr class="hr">
                    <div class="cont-alx">
                        <form class="form-simple" method="post" action="{{ route('sittings') }}">
                            <h4>Zmień Nickname</h4>
                            <h5>Nick powinien zawierać co najmniej 3 znaki.</h5>
                            <input class="textbox-simple" type="text" placeholder="Nickname" value="{{Auth::user()->name}}" name="newnickname" pattern=".{3,}" required>
                            <input type="submit" name="newnick" class="button" value="Zmień">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                        </form>
                    </div>
                    <hr class="hr">
                    <div class="cont-alx">
                        <form class="form-simple" method="post" action="{{ route('sittings') }}">
                            <h4>Zmień hasło</h4>
                            <h5>Hasło powinno zawierać co najmniej 6 znaków!</h5>
                            <input id="newpass1" class="textbox-simple" type="password" name="newpass1" placeholder="Nowe hasło" oninput="passcheck()" pattern=".{6,}" required>
                            <input id="newpass2" class="textbox-simple" type="password" name="newpass2" placeholder="Powtórz hasło" oninput="passcheck()" pattern=".{6,}" required>
                            <input id="passwd" type="submit" name="passwd" class="button" value="Zmień">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                        </form>
                    </div><hr class="hr">
                    <div class="cont-alx">
                        <form class="form-simple" method="post" action="{{ route('sittings') }}">
                            <h4>Usuń konto</h4>
                            <h5 style="color:#f00;">Usunite zostanie konto oraz wszystkie zdjcia i albumy!</h5>
                            <input class="textbox-simple" type="text" name="mail" placeholder="E-mail" required>
                            <input type="submit" name="delacc" class="button" value="Usuń konto">
                            <input type="hidden" value="{{csrf_token()}}" name="_token">
                        </form>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <script>
        function passcheck(){
            var pass1 = document.getElementById('newpass1');
            var pass2 = document.getElementById('newpass2');
            var passwd = document.getElementById('passwd');
            if(pass1.value != pass2.value){
                console.log(pass1.value + '!=' + pass2.value);
                pass2.style.background = '#ffa172';
                passwd.disabled = true;
            }else{
                console.log(pass1.value + '==' + pass2.value);
                pass2.style.background = '#b0ffaa';
                passwd.disabled = false;
            }
        }
    </script>
@endsection
