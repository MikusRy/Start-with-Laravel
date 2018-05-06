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
                echo '<br>Create gallery.';
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
            };

//                Funkcja tworzy nową galerię do której zostanie przypisane zdjęcie
            if ($selectgallery == 'new') {
                $tst = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->count();
                if ($tst == 0) {
                    echo '<br>Create gallery.';
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
            $getGallery = '';
            $getGallery = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->first();
            $items = $getGallery->items;
            $items++;
            $getGallery = $getGallery->id;
            echo '<br>Gallery id: ' . $getGallery;

//                Dla albumu all
            $allid = Gallery::where('name', '=', 'All')->where('created_by', '=', Auth::user()->id)->first();
            if ($allid->id != $getGallery) {
                Image::create([
                    'gallery_id' => $allid->id,
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

                $allitems = $allid->items;
                $allitems++;
                Gallery::where('id', '=', $allid->id)->update(['items' => $allitems]);
            }

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
        $isSomethink = Gallery::where('id', '=', $galleryID)->where('created_by', '=', Auth::user()->id)->get();
        if ($isSomethink->count() != 0) {
            $isimage = Image::where('gallery_id', '=', $galleryID)->get();
            $Images = Image::where('gallery_id', '=', $galleryID)->orderBy('id', 'desc')->get();
            $GalleryName = Gallery::where('id', '=', $galleryID)->first();
            return view('showgallery', ['images' => $Images, 'galleryname' => $GalleryName, 'some' => $isSomethink, 'isimage' => $isimage]);
        } else {
            return redirect('/home');
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
            $galleryid = Input::get('galleryid');

            echo $imageid;
            echo $imagename;
            echo $galleryid;

            $items = Gallery::where('id', '=', $galleryid)->first();
            $items = $items->items;
            if ($items > 0) {
                $items--;
            }
            echo $items;
            Gallery::where('id', '=', $galleryid)->update(['items' => $items]);
            Image::where('id', '=', $imageid)->delete();

            $mess = "Usunięto: " . $imagename;
        } else if (Input::has('delfromall')) {
            $imageid = Input::get('imageid');
            $imagename = Input::get('imagename');
            $galleryid = Input::get('galleryid');

            echo $imageid;
            echo $imagename;
            echo $galleryid;

            $items = Gallery::where('id', '=', $galleryid)->first();
            $items = $items->items;
            if ($items > 0) {
                $items--;
            }
            echo $items;
            Gallery::where('id', '=', $galleryid)->update(['items' => $items]);
            Image::where('id', '=', $imageid)->delete();

            $mess = "Usunięto: " . $imagename;

        } else {
            $mess = "Usp... Coś poszło nie tak :/";
        }
        return redirect()->back()->with(['status' => $mess]);
    }
}
