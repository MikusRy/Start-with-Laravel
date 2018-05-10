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
                    <h1 align="center">View</h1>
                    <hr class="hr">
                    <div class="cont-alx">

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
