<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function upload()
    {
        return view('upload');
    }


    public function uploadfile()
    {
//        echo 'Pilik został przesłany!';
        if (Input::has('file')) {
            $file = Input::file('file');
            $filename = $file->getClientOriginalName();
            $select = Input::get('selectgallery');
            $priv = Input::get('priv');
            ($priv != '') ? $priv = 'true' : $priv = 'false';
            $newname = Input::get('newname');
            ($newname != '') ? $filename = $newname . '.' . $file->getClientOriginalExtension() : $newname = $newname;
            $newgallery = Input::get('newgallery');
            ($select == 'new')? ($newgallery != '')? $gallery = $newgallery : $gallery = 'all' : $gallery = $select;
//            echo '<br>File name: ' . $filename;
//            echo '<br>Selected gallery: ' . $select;
//            echo '<br>Private: ' . $priv;
//            echo '<br>New name: ' . $newname;
//            echo '<br>New gallery: ' . $newgallery;
            $file->move(Auth::user()->email, $filename);
            //            echo '<img src="gallery/' . $file->getClientOriginalName() . '"/>';
            $mess = 'Plik został wysłany!';
        } else {
            $mess = 'Nie wybrano pliku!';
        }

        return redirect('upload')->with('status', $mess);
    }
}
