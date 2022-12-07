<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Models\Media;
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

    }
}
