<?php

namespace App\Http\Controllers;

use Faker\Provider\File;
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
            $selectgallery = Input::get('selectgallery');
            $selectpublishgallery = Input::get('selectpublishgallery');
            $selectpublishimg = Input::get('selectpublishimg');
            $selectlicenceimg = Input::get('selectlicenceimg');

            $newname = Input::get('newname');
            ($newname != '') ? $filename = $newname . '.' . $file->getClientOriginalExtension() : $newname = $newname;

            $newgallery = Input::get('newgallery');
            ($selectgallery == 'new') ? ($newgallery != '') ? $gallery = $newgallery : $gallery = 'all' : $gallery = $selectgallery;

            $komentarz = Input::get('comment');

            $info = Input::get('info');

            $picname = $filename;
            $filename = time() . '-' . $filename;

            echo '<br>Selected gallery: ' . $selectgallery;
            echo '<br>New name: ' . $newname;
            echo '<br>New gallery: ' . $newgallery;
            echo '<br>--------------------------';
            echo '<br>File name: ' . $filename;
            echo '<br>Pic name: ' . $picname;
            echo '<br>Komnetarz: ' . $komentarz;
            echo '<br>Publikacja: ' . $selectpublishimg;
            echo '<br>Licencja: ' . $selectlicenceimg;
            echo '<br>Gallery: ' . $gallery;
            echo '<br>Info: ' . $info;
            echo '<br>Publikacja: ' . $selectpublishgallery;

            if ($gallery == '') {
                $gallery = 'All';
            }

//            Funkcja tworzy galerię All jeśli takowej nie ma
            $all = Gallery::where('name', '=', 'All')->where('created_by', '=', Auth::user()->id)->count();
            if ($all == 0) {
//              Create new gallery 'All'
                Gallery::create([
                    'name' => 'All',
                    'created_by' => Auth::user()->id,
                    'publish' => 'prywatny',
                    'like' => 0,
                    'unlike' => 0,
                    'view' => 0,
                    'items' => 0,
                    'info' => 'Galeria ze wszystkimi obrazami.'
                ]);
            }elseif ($selectgallery == 'new') {
                $tst = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->count();
                if ($tst == 0) {
//                  Create new gallery
                    Gallery::create([
                        'name' => $gallery,
                        'created_by' => Auth::user()->id,
                        'publish' => $selectpublishgallery,
                        'like' => 0,
                        'unlike' => 0,
                        'view' => 0,
                        'items' => 0,
                        'info' => $info
                    ]);
                };
            }
            $getGallery = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->first();
            $items = $getGallery->items + 1;
            $getGallery = $getGallery->id;

//                  Dla albumu nowego lub wybranego
            Image::create([
                'gallery_id' => $getGallery,
                'file_name' => $filename,
                'pic_name' => $picname,
                'created_by' => Auth::user()->id,
                'like' => 0,
                'unlike' => 0,
                'view' => 0,
                'publish' => $selectpublishimg,
                'licence' => $selectlicenceimg,
                'blacklist' => 0,
                'info' => $komentarz]);

            Gallery::where('id', '=', $getGallery)->update(['items' => $items]);
            $file->move(Auth::user()->usercode, $filename);

            $mess = 'Plik: ' . $picname . ', dodano do galerii: ' . $gallery . '.';
        } else {
            $mess = 'Nie wybrano pliku!';
        }

        return redirect('upload')->with('status', $mess);
    }


    public
    function showgallery($galleryID)
    {
        if ($galleryID == 'all') {
            $GalleryName = Gallery::where('name', '=', 'all')->where('created_by', '=', Auth::user()->id)->first();
            if ($GalleryName->count() != 0) {
                $Images = Image::where('created_by', '=', Auth::user()->id)->orderBy('id', 'desc')->get();
                return view('showgallery', ['images' => $Images, 'galleryname' => $GalleryName]);
            }
        }else {
            $GalleryName = Gallery::where('id', '=', $galleryID)->first();
            if ($GalleryName->count() != 0) {
                $Images = Image::where('gallery_id', '=', $galleryID)->orderBy('id', 'desc')->get();
                return view('showgallery', ['images' => $Images, 'galleryname' => $GalleryName]);
            } else {
                return redirect('/home');
            }
        }
    }

    public
    function updategallery()
    {
        if (Input::has('edit')) {
            $galleryid = Input::get('galleryid');
            $galleryname = Input::get('galleryname');
            $galleryinfo = Input::get('galleryinfo');
            $gallerypublish = Input::get('gallerypublish');

            echo '<br>Gallery Name: ' . $galleryname;
            echo '<br>Gallery Info: ' . $galleryinfo;
            echo '<br>Gallery Publish: ' . $gallerypublish;
            echo '<br>Gallery ID: ' . $galleryid;

            Gallery::where('id', '=', $galleryid)->update(['name' => $galleryname, 'info' => $galleryinfo, 'publish' => $gallerypublish,]);

            $mess = "Pomyślnie zapisano zmiany w albumie: " . $galleryname . ".";
        } else if (Input::has('del')) {
            $galleryid = Input::get('galleryid');
            $galleryname = Input::get('galleryname');

            Gallery::where('id', '=', $galleryid)->delete();
            $mess = "Usunięto album: " . $galleryname;
            return redirect('/')->with(['status' => $mess]);
        } else {
            $mess = "Usp... Coś poszło nie tak :/";
        }
        return redirect()->back()->with(['status' => $mess]);
    }

    public
    function updateimg()
    {
        if (Input::has('view')) {
            $imageid = Input::get('imageid');
            $imagename = Input::get('imagename');
            $galleryid = Input::get('galleryid');

            $mess = "Pomyślnie zapisano zmiany w albumie: " . $imagename . ".";
        } else if (Input::has('del')) {
            $imageid = Input::get('imageid');
            $imagename = Input::get('imagename');

            $imagedata = Image::where('id', '=', $imageid)->first();

            echo $imageid;
            echo $imagename;
            echo $imagedata->gallery_id;

            if (Gallery::where('id', '=', $imagedata->gallery_id)->count() != 0) {
                $items = Gallery::where('id', '=', $imagedata->gallery_id)->first()->items;
                    if ($items > 0) {
                        $items--;
                    }
                    echo $items;
                    Gallery::where('id', '=', $imagedata->gallery_id)->update(['items' => $items]);
            }
            $filename = Image::where('id', '=', $imageid)->first();
            $link3 = public_path() . '\\' . Auth::user()->usercode . '\\' . $filename->file_name;
            echo $link3;
            if (file_exists($link3)) {
                unlink($link3);
            }
            Image::where('id', '=', $imageid)->delete();

            $mess = "Usunięto: " . $imagename;
        } else {
            $mess = "Usp... Coś poszło nie tak :/";
        }
        return redirect()->back()->with(['status' => $mess]);
    }
}
