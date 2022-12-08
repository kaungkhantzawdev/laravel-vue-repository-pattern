<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Media;
use App\Repositories\BlogRepositoryInterface;
use Illuminate\Support\Facades\Storage;
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
            $file-> storeAs( $dir , $filename);

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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::find($id);
        if($request->has('title')){
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title, '-');
        }
        if($request->has('description')){
            $blog->description = $request->description;
            $blog->excerpt = Str::limit($request->description,100, ' ...');
        }

        $blog->save();
        //onePhoto
        if($request->file('image')){
            $file= $request->file('image');
            $filename= uniqid().'_image'.$file->getClientOriginalName();
            $dir = "public/images";
            $file-> storeAs( $dir , $filename);

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
            'message' => "Successfully Added",
            'data' => $blog
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
        if(is_null($blog)){
            return response()->json([
                'status' => 404,
                'message' => "not found"
            ], 404);
        }

        $media = Media::where('blog_id', $id )->get();
        if($media){
            foreach ($media as $m){
                if($m->file_type == 101){
                    $dir = "public/images/";
                    Storage::delete($dir.$m->name);

                }

                if($m->file_type == 102){
                    $dir = "public/related_photos/";
                    Storage::delete($dir.$m->name);
                }
                $m->delete();
            }
        }

        $blog->delete();
        return response()->json([
            'status' => 200,
            'message' => "successfully deleted",
            'data' => $blog
        ], 200);

    }

    public function deletePhoto($id) {
        $media = Media::find($id);
        if(!$media){
            return response()->json([
                'status' => 404,
                'message' => "not found"
            ], 404);
        }
        if($media->file_type == 101){
            $dir = "public/images/";
            Storage::delete($dir.$media->name);
        }

        if($media->file_type == 102){
            $dir = "public/related_photos/";
            Storage::delete($dir.$media->name);
        }

        $media->delete();
        return response()->json([
            'status' => 200,
            'message' => "successfully deleted",
        ], 200);
    }
}
