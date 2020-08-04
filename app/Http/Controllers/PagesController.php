<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function index() {
        // passing values to template
        //$title = "Welcome to the jungle";
       // return view('pages.index', compact('title'));

        // passing values to template using with method
        $title = "Welcome to bizapp.php";
        return view('pages.index')->with('someText', $title);
    }

    //
    public function about() {
        $title = 'About Us';
    return view('pages.about')->with('title', $title);
    }

    //
    public function services() {
        $data = array(
            'title' => 'Services',
            'services' => ['Web Design', 'Programming', 'SEO']
        );
    return view('pages.services')->with($data) ;
    }


}
