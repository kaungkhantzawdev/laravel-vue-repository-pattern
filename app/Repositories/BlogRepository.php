<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogRepository implements BlogRepositoryInterface
{
    public function all(){
       return Blog::latest('id')->get();
    }

    public function store($request){

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

    public function show($slug){
        return $blog = Blog::where('slug', $slug)
            ->first();
    }

    public function destory($id){
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

    public function addPhotos( $request, $id){
        //onePhoto
        if($request->hasFile('image')){
            $file= $request->file('image');
            $filename= uniqid().'_image'.$file->getClientOriginalName();
            $dir = "public/images";
            $file-> storeAs( $dir , $filename);

            $image = Media::create([
                'name' => $filename,
                'file_type' => 101,
                'blog_id' => $id
            ]);
        }

        //multiplePhoto
        if($request->hasFile('photos')){
            foreach ($request->file('photos') as $photo){
                $file= $photo;
                $filename= uniqid().'_related_photo'.$photo->getClientOriginalName();
                $dir = "public/related_photos";
                $file-> storeAs( $dir , $filename);

                $related_photos = Media::create([
                    'name' => $filename,
                    'file_type' => 102,
                    'blog_id' => $id
                ]);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => "Successfully Added",
        ], 200);
    }

    public function update( $request, $id)
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

        return response()->json([
            'status' => 200,
            'message' => "Successfully Added",
            'data' => $blog
        ], 200);
    }
}
