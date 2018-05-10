<?php

namespace App\Http\Controllers;

use App\User;
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
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Wyświetlenie galerii
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Gallery = Gallery::where('created_by', Auth::user()->id)->get();
        return view('home', ['gallery' => $Gallery]);
    }

    /**
     * Zwraca strone ze zmiana hasla, nickname'u oraz mozliwoscia zamkniecia konta
     * Akcje:
     * - Zmiana nickname
     * - Zmiana hasła
     * - Zamknięcie konta
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sittings()
    {
     $mess = '';
        if (Input::has('newnick')) {
            /**
             * Zmiana nickname
             */
            $newnickname = Input::get('newnickname');
            User::where('id', Auth::user()->id)->update(['name' => $newnickname]);
            $mess = 'Zmiana nicku zakończona sukcesem!';
            return redirect('sittings')->with('status', $mess);
        }elseif (Input::has('passwd')){
            /**
             * Zmiana hasła
             */
            $newpass1 = Input::get('newpass1');
            $newpass2 = Input::get('newpass2');

            if ($newpass1 == $newpass2){
                User::where('id', Auth::user()->id)->update(['password' => Hash::make($newpass1)]);
                $mess = "Zmiana hasła zakończona powodzeniem";
            }else (
                /**
                 * Wiadomość w razie błędu
                 */
                $mess = "Coś poszło nie tak. Zostaje stare hasło."
            );
            return redirect('sittings')->with('status', $mess);
        }elseif (Input::has('delacc')){
            /**
             * Usunięcie konta użytkownika po wprowadzeniu dobrego email'u
             */
            $mail = Input::get('mail');
            if ($mail == Auth::user()->email) {
                /**
                 * Nieaktywny moduł usuwania katalogu przy usunięciu użytkownika
                 * Problem w tym że nie usuwa katalogu :/
                $dirpath = '\\' . Auth::user()->usercode;
                if (is_dir($dirpath)) {
                    $mess = $dirpath;
                    rmdir(dirpath);
                    Gallery::where('id',Auth::user()->id)->delete();
                    Image::where('id',Auth::user()->id)->delete();
                    User::where('id',Auth::user()->id)->delete();
                    return redirect('/login');
                 }
                 */
                    Gallery::where('id',Auth::user()->id)->delete();
                    Image::where('id',Auth::user()->id)->delete();
                    User::where('id',Auth::user()->id)->delete();
                    return redirect('/login');
            }else {
                $mess = 'To nie ten mail.';
            }
            return redirect('sittings')->with('status', $mess);
        }
        return view('sittings');
    }

    /**
     * Zwraca stronę z formem uploadu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upload()
    {
        $Gallery = Gallery::where('created_by', Auth::user()->id)->get();
        return view('upload', ['gallery' => $Gallery]);
    }

    /**
     * Tworzenie albumu
     * Przypisanie zdjęcia do albumu
     * Przesłanie zdjęcia do folderu o nazwie == usercode
     * @return \Illuminate\Http\RedirectResponse
     */
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
            /**
             * Jeśli wprowadzono nową nazwę dla zdjęcia to zmień
             */
            $newname = Input::get('newname');
            ($newname != '') ? $filename = $newname . '.' . $file->getClientOriginalExtension() : $newname = $newname;
            /**
             * Jeśli wybrano nową galerię i nie wprowadzono danych to zdjęcie trafi do domyślnej 'All',
             */
            $newgallery = Input::get('newgallery');
            ($selectgallery == 'new') ? ($newgallery != '') ? $gallery = $newgallery : $gallery = 'all' : $gallery = $selectgallery;
            $komentarz = Input::get('comment');
            $info = Input::get('info');
            $picname = $filename;
            /**
             * Ustawienie indywidualnej nazwy pliku (nie nazwy zdjęcia)
             */
            $filename = time() . '-' . $filename;
            /**
             * jeśli nazwa galerii jest pusta to przypisze domyślną 'All'
             */
            if ($gallery == '') {
                $gallery = 'All';
            }
            /**
             * Funkcja tworzy galerię All jeśli takowej nie ma
             */
            $all = Gallery::where('name', '=', 'All')->where('created_by', '=', Auth::user()->id)->count();
            if ($all == 0) {
                /**
                 * Stworzenie galerii 'All'
                 */
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
            } elseif ($selectgallery == 'new') {
                $tst = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->count();
                if ($tst == 0) {
                    /**
                     * Stworzenie nowej galerii o podanych
                     * przez użytkownika parametrach
                     */
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
            /**
             * Przygotowanie iteracji ilości zdjęć w galerii do której trafi zdjęcie
             */
            $getGallery = Gallery::where('name', '=', $gallery)->where('created_by', '=', Auth::user()->id)->first();
            $getGallery = $getGallery->id;
            /**
             * Stworzenie danych zdjęcia w DB
             */
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
                'info' => $komentarz,
                'comments' => 0]);
            /**
             * Upload zdjęcia do odpowiedniego folderu w public
             */
            $file->move(Auth::user()->usercode, $filename);
            $mess = 'Plik: ' . $picname . ', dodano do galerii: ' . $gallery . '.';
        } else {
            $mess = 'Nie wybrano pliku!';
        }

        return redirect('upload')->with('status', $mess);
    }

    /**
     * Wyświetla zawartość wybranej galerii
     * All to galeria wszystkich zdjęć
     * @param $galleryID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showgallery($galleryID)
    {
        if ($galleryID == 'all') {
            /**
             * Wyświetlenie wszystkich zdjęć użytkownika
             */
            $Gallery = Gallery::where('name', '=', 'all')->where('created_by', '=', Auth::user()->id)->first();
            if ($Gallery->count() != 0) {
                $Images = Image::where('created_by', '=', Auth::user()->id)->orderBy('id', 'desc')->get();
                return view('showgallery', ['images' => $Images, 'galleryname' => $Gallery]);
            }
        } else {
            /**
             * Wyświetlenie zdjęć wybranej galerii
             */
            $Gallery = Gallery::where('id', '=', $galleryID)->first();
            if ($Gallery->count() != 0) {
                /**
                 * Wyświetl jeśli galeria istnieje
                 */
                $Images = Image::where('gallery_id', '=', $galleryID)->orderBy('id', 'desc')->get();
                return view('showgallery', ['images' => $Images, 'galleryname' => $Gallery]);
            } else {
                return redirect('/home');
            }
        }
    }

    /**
     * Update galerii
     * Usuwanie galerii
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updategallery()
    {
        if (Input::has('edit')) {
            /**
             * Edycja danych galerii
             */
            $galleryid = Input::get('galleryid');
            $galleryname = Input::get('galleryname');
            $galleryinfo = Input::get('galleryinfo');
            $gallerypublish = Input::get('gallerypublish');
            /**
             * Update informacji o galerii
             */
            Gallery::where('id', '=', $galleryid)->update(['name' => $galleryname, 'info' => $galleryinfo, 'publish' => $gallerypublish,]);
            $mess = "Pomyślnie zapisano zmiany w albumie: " . $galleryname . ".";
        } else if (Input::has('del')) {
            /**
             * Usuwanie galerii
             */
            $galleryid = Input::get('galleryid');
            $galleryname = Input::get('galleryname');
            /**
             * Usunięcie galerii z DB
             */
            Gallery::where('id', '=', $galleryid)->delete();
            $mess = "Usunięto album: " . $galleryname;
            return redirect('/')->with(['status' => $mess]);
        } else {
            $mess = "Usp... Coś poszło nie tak :/";
        }
        return redirect()->back()->with(['status' => $mess]);
    }

    /**
     * Wyświetlanie obrazu
     * Usuwanie orazu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateimg()
    {

        if (Input::has('view')) {
            /**
             *
             * funkcja odpowiada za przekierowanie do wyświetlenia
             * wybranego obrazu wraz ze szczegółami
             *
             */
            $imageid = Input::get('imageid');
            /**
             * Pobranie danych o zdjęciu
             */
            $image = Image::where('id', $imageid)->first();
            $gallery = Gallery::where('id', $image->gallery_id)->first();
            $gallerylist = Gallery::where('created_by', Auth::user()->id)->get();
            ($image->comments == true)? $checked = 'checked' : $checked = '' ;
            return view('imgview', ['image'=>$image, 'gallery'=>$gallery, 'gallerylist'=>$gallerylist, 'checked'=>$checked]);
        } else if (Input::has('del')) {
            /**
             *
             * Usuwanie zdjęć z DB oraz odpowiedniego folderu
             *
             */
            $imageid = Input::get('imageid');
            $imagename = Input::get('imagename');
            /**
             * Generowanie linku do pliku (ścieżka usuwania)
             */
            $filename = Image::where('id', '=', $imageid)->first();
            $link3 = public_path() . '\\' . Auth::user()->usercode . '\\' . $filename->file_name;
            echo $link3;
            /**
             * Usunięcie zdjęcia z folderu
             */
            if (file_exists($link3)) {
                unlink($link3);
            }
            /**
             * Usunięcie zdjęcia z DB
             */
            Image::where('id', '=', $imageid)->delete();
            $mess = "Usunięto: " . $imagename;
            if (Input::has('viewpage')){
                return redirect('/')->with(['status' => $mess]);
            }else {
                return redirect()->back()->with(['status' => $mess]);
            }
        } else {
            $mess = "Usp... Coś poszło nie tak :/";
            return redirect()->back()->with(['status' => $mess]);
        }
        return redirect()->back();
    }

}
