<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Media;
use App\Repositories\BlogRepositoryInterface;
use Illuminate\Support\Str;

class BlogController extends Controller
{
//    private $blogRepository;
//
//    public function __construct(BlogRepositoryInterface $blogRepository)
//    {
//        $this->blogRepository = $blogRepository;
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::latest('id')->get();
        return response()->json($blogs, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBlogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        $request->validate([
            'title'=>'required|min:3|max:50',
            'description'=>'required',
            'image'=>'required|file|mimes:jpg,png,jpeg|max:2000',
            'photos' => 'required'
        ]);

        $blog = Blog::create([
            'title'=> $request->title,
            'slug' => Str::slug($request->title, '-'),
            'description'=> $request->description,
            'excerpt' => Str::limit($request->description,100, ' ...')
        ]);

        //onePhoto
        if($request->file('image')){
            $file= $request->file('image');
            $filename= uniqid().'_image'.$file->getClientOriginalName();
            $dir = "public/images";
            $file_path =$file-> storeAs( $dir , $filename);

            $image = Media::create([
                'name' => $filename,
                'file_type' => 101,
                'blog_id' => $blog->id
            ]);
        }

        //multiplePhoto
        if($request->file('photos')){
            foreach ($request->file('photos') as $photo){
                $file= $photo;
                $filename= uniqid().'_related_photo'.$photo->getClientOriginalName();
                $dir = "public/related_photos";
                $file-> storeAs( $dir , $filename);

                $related_photos = Media::create([
                    'name' => $filename,
                    'file_type' => 102,
                    'blog_id' => $blog->id
                ]);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => "Successfully Added"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->first();
        if(is_null($blog)){
            return response()->json([
                'status' => 404,
                'message' => "not found"
            ], 404);
        }else{
            return response()->json([
                'status' => 200,
                'message' => "data are following",
                'data' => $blog
            ], 200);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogRequest  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        //
    }
}
