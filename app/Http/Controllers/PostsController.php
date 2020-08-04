<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * jason - chapter 8 - deleted the original PostsController and re-installed using
     * php artisan make:controller PostsController --resource 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$posts = Post::all();
       // $posts = Post::orderby('title', 'desc')->get();
        //$posts = DB::select('SELECT * FROM posts');
        $posts = DB::table('posts')->get();
        $users = DB::select('SELECT * FROM users');
        //return view('posts.index')->with('posts', $posts);
        return view('posts.index', compact('posts', 'users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // chapter 11
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // data comes from create.blade.php
        $this->validate($request,
        [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
            // handle the file upload
            if($request->hasFile('cover_image')) {
                // get filename with extensions
                $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
                // get just the filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // get ext
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                // filename we will store. we add the time to differentiate two files with the same name
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                // upload image
                $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            } else {
                $fileNameToStore = 'noimage.jpg';
            }
            

        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();
        return redirect('/posts')->with('success', 'Post created.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id ){
            return redirect('/posts')->with('error', 'Unauthorized page.');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // data comes from create.blade.php
       $this->validate($request,
       [
           'title' => 'required',
           'body' => 'required',
           'cover_image' => 'image|nullable|max:1999'
       ]);
           // handle the file upload
           if($request->hasFile('cover_image')) {
               // get filename with extensions
               $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
               // get just the filename
               $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
               // get ext
               $extension = $request->file('cover_image')->getClientOriginalExtension();
               // filename we will store. we add the time to differentiate two files with the same name
               $fileNameToStore = $filename . '_' . time() . '.' . $extension;
               // upload image
               $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
           }
           
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image') ){

            if($post->cover_image != 'noimage.jpg') {
                Storage::delete('public/cover_images/'.$post->cover_image);
            }

            $post->cover_image = $fileNameToStore;
            
        }
        $post->save();
        return redirect('/posts')->with('success', 'Post updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        if(auth()->user()->id !== $post->user_id ){
            return redirect('/posts')->with('error', 'Unauthorized page.');
        }

        if($post->cover_image != 'noimage.jpg'){
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed.');
    }
}
