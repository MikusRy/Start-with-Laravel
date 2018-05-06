<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\URL;
use App\Gallery;
use App\Image;

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
        $Gallery = Gallery::where('created_by', Auth::user()->id)->get();
        return view('home', ['gallery' => $Gallery]);
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

            if ($gallery == ''){$gallery = 'All';}
            if ($select == 'new') {
                $tst = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->count();
                if ($tst == 0) {
                    echo '<br>Create gallery.';
                    Gallery::create(['name' => $gallery, 'created_by' => Auth::user()->id, 'public' => $gallerypub, 'like' => 0, 'unlike' => 0, 'view' => 0, 'items'=>0, 'info' => $info]);
                }else{
                    echo '<br>Galeria istnieje.';
                }
            };
            $getGallery = '';
            $getGallery = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->first();
            $items = $getGallery->items;
            $items++;
            $getGallery = $getGallery->id;
            echo '<br>Gallery id: ' . $getGallery;
            Image::create(['gallery_id' => $getGallery, 'file_name' => $filename, 'pic_name' => $picname, 'created_by' => Auth::user()->id, 'public' => $picpub, 'like' => 0, 'unlike' => 0, 'view' => 0, 'info' => $komentarz]);
            Gallery::where('id', '=', $getGallery)->update(['items'=>$items]);
            /*            print "<pre>";
              print_r($getGallery);
              print "</pre>";
*/
            $file->move(Auth::user()->usercode, $filename);
            $mess = 'Plik: ' . $picname . ', dodano do galerii: ' . $gallery . '.';
        } else {
            $mess = 'Nie wybrano pliku!';
        }

        return redirect('upload')->with('status', $mess);
    }


    public function showgallery($galleryID)
    {
        $isSomethink = Gallery::where('id', '=', $galleryID)->where('created_by', '=', Auth::user()->id)->get();
        if($isSomethink->count() != 0) {
            $Images = Image::where('gallery_id', '=', $galleryID)->get();
            $GalleryName = Gallery::where('id', '=', $galleryID)->first();
            return view('showgallery', ['images' => $Images, 'galleryname' => $GalleryName, 'some' => $isSomethink]);
        }else{
            return redirect('/home');
        }
    }
}
