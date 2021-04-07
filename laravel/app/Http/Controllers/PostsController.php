<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Events\BlogPostPosted;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
             ->only(['create','store','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index',['posts'=> BlogPost::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // dd($request->all());
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validatedData);

        // $hasFile = $request->hasFile('thumbnail');
        // dump($hasFile); // ovo daje true ili false

        // if($hasFile) {
            // $file = $request->file('thumbnail');
            // dump($file); // ovo daje info
            // dump($file->getClientMimeType()); // image/png
            // dump($file->getClientOriginalExtension()); // png

            // dump($file->store('thumbnails')); // i snimi thumb/ewqeqdasdasd.png
            // dump(Storage::disk('public')->put('thumbnails', $file)); // i snimi thumb/ewqeqdasdasd.png

            // dump($file->storeAs('thumbnails', $blogPost->id . '.' . $file->guessExtension())); // thumbnails/12.png
            // dump(Storage::putFileAs('thumbnails', $file, $blogPost->id . '.' . $file->guessExtension())); // sad radi thumbnails/12.png i ovo snima u svoj local disk koji se zove thumb


            // $name1 = $file->storeAs('thumbnails', $blogPost->id . '.' . $file->guessExtension());
            // $name2 = Storage::disk('local')->putFileAs('thumbnails', $file, $blogPost->id . '.' . $file->guessClientExtension()); // za local nece da cita ovo ne znam da koristim ovo proveriti kako koristi

            // dump(Storage::url($name1)); // public ovako radi url
            // dump(Storage::disk('local')->url($name2)); // url path root

        // if ($request->hasFile('thumbnail')) { // ovo u zagradi mora biti razlicito od store-a
        //     $path = $request->file('thumbnail')->store('thumbnails'); // i ovde mora biti mora biti razlicit
        //     $blogPost->image()->save(
        //         Image::make(['path' => $path])
        //     );
        // }
        // die;

        // event(new BlogPostPosted($blogPost));

        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show',['post'=> $blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('posts.show', ['posts'=> BlogPost::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        $this->authorize('update', $post);
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog-post");
        // }

        return view('posts.edit', ['posts'=> $post]);

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
        $post = BlogPost::findOrFail($id);

        $this->authorize('update', $post);
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog-post");
        // }

        $validatedData = $request->validated();
        // $posts->title=$validatedData['title'];
        // $posts->content=$validatedData['content'];
        $post->fill($validatedData);

        // if ($request->hasFile('thumbnail')) {
        //     $path = $request->file('thumbnail')->store('thumbnails');

        //     if ($post->image) {
        //         Storage::delete($post->image->path);
        //         $post->image->path = $path;
        //         $post->image->save();
        //     } else {
        //         $post->image()->save(
        //             Image::make(['path' => $path])
        //         );
        //     }

        // }

        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');
        return redirect()->route('posts.show', ['post'=>$post->id]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);

        $this->authorize('delete', $post);

        $post->delete();

        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
