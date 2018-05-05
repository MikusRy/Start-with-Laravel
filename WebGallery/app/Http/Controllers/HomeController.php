<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use App\Gallery;
use App\Upload;

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
        $Gallery = Gallery::where('created_by', Auth::user()->id)->get();
        return view('upload', ['gallery' => $Gallery]);
    }


    public function uploadfile()
    {
        echo 'Podsumowanie:<br>';
        if (Input::has('file')) {
            $file = Input::file('file');
            $filename = $file->getClientOriginalName();
            $select = Input::get('selectgallery');

            $picpriv = Input::get('picpriv');
            ($picpriv != '') ? $picpub = '0' : $picpub = '1';

            $gallerypriv = Input::get('gallerypriv');
            ($gallerypriv != '') ? $gallerypub = '0' : $gallerypub = '1';

            $newname = Input::get('newname');
            ($newname != '') ? $filename = $newname . '.' . $file->getClientOriginalExtension() : $newname = $newname;

            $newgallery = Input::get('newgallery');
            ($select == 'new') ? ($newgallery != '') ? $gallery = $newgallery : $gallery = 'all' : $gallery = $select;

            $komentarz = Input::get('comment');

            $info = Input::get('info');

            $picname = $filename;
            $filename = time() . '-' . $filename;

            echo '<br>Selected gallery: ' . $select;
            echo '<br>New name: ' . $newname;
            echo '<br>New gallery: ' . $newgallery;
            echo '<br>--------------------------';
            echo '<br>File name: ' . $filename;
            echo '<br>Pic name: ' . $picname;
            echo '<br>Komnetarz: ' . $komentarz;
            echo '<br>Public Pic: ' . $picpub;
            echo '<br>Gallery: ' . $gallery;
            echo '<br>Info: ' . $info;
            echo '<br>Public Gallery: ' . $gallerypub;

            if ($select == 'new') {
                $tst = Gallery::where('name', '=', $gallery, 'AND', 'created_by', '=', Auth::user())->count();
                if ($tst == 0) {
                    echo '<br>Create gallery.';
                    Gallery::create(['name' => $gallery, 'created_by' => Auth::user()->id, 'public' => $gallerypub, 'like' => 0, 'unlike' => 0, 'view' => 0, 'info' => $info]);
                }else{
                    echo '<br>Galeria istnieje.';
                }
            };
            $getGallery = '';
            $getGallery = Gallery::where('name', '=', $gallery, 'AND', 'created_by', '=', Auth::user()->id)->first()->id;
            echo '<br>Gallery id: ' . $getGallery;
            Upload::create(['gallery_id' => $getGallery, 'file_name' => $filename, 'pic_name' => $picname, 'created_by' => Auth::user()->id, 'public' => $picpub, 'like' => 0, 'unlike' => 0, 'view' => 0, 'info' => $komentarz]);
/*            print "<pre>";
              print_r($getGallery);
              print "</pre>";
*/
            $file->move(Auth::user()->usercode, $filename);
            $mess = 'Plik został wysłany!';
        } else {
            $mess = 'Nie wybrano pliku!';
        }

        return redirect('upload')->with('status', $mess);
    }
}
