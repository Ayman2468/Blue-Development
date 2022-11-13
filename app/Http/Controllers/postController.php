<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdate;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Traits\imageinsertiontrait;

class postController extends Controller
{
    use imageinsertiontrait;
    //
    public function index()
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        $posts = Post::with('tags')->where('user_id',$user->id)->where('removed',0)->orderBy('pinned', 'DESC')->get();
        $response = [
            'status' => "success",
            'msg' => "",
            'data' => $posts,
        ];
        return response()->json($response);
    }

    public function deleted_posts()
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        $posts = Post::where('user_id',$user->id)->where('removed',1)->get();
        $response = [
            'status' => "success",
            'msg' => "",
            'data' => $posts,
        ];
        return response()->json($response);
    }

    public function show($post_id)
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        $post = Post::with('tags')->where('user_id',$user->id)->where('id',$post_id)->where('removed',0)->first();
        if($post){
            $response = [
                'status' => "success",
                'msg' => "",
                'data' => $post,
            ];
        }else{
            $response = [
                'status' => "fail",
                'msg' => "No Such Post Found",
                'data' => "",
            ];
        }
        return response()->json($response);
    }

    public function create(PostRequest $request)
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();

        $image =$request->file('cover_image');
        $folder = public_path('images/posts/'.$user->id);
        if (!file_exists($folder)) {
            mkdir($folder);
        }
        $file_name = $this->imageinsertion($image, $folder);
        if(str_contains($file_name,'error')){
            return response()->json([
                'status' => false,
                'msg' => "one or more images have wrong extension"
            ]);
        }
        $post = Post::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $file_name,
            'pinned' => $request->pinned,
            'removed' => 0,
        ]);

        $response = [
            'status' => "success",
            'msg' => 'post created',
            'data' => $post,
        ];
        return response()->json($response);
    }

    public function update(PostUpdate $request, $id)
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        $post = Post::where('id', $id)->where('user_id',$user->id)->first();
        if($request->file('cover_image')){
                $image =$request->file('cover_image');
            $folder = public_path('images/posts/'.$user->id);
            if (!file_exists($folder)) {
                mkdir($folder);
            }
            $file_name = $this->imageinsertion($image, $folder);
            if(str_contains($file_name,'error')){
                return response()->json([
                    'status' => false,
                    'msg' => "one or more images have wrong extension"
                ]);
            }
        }else{
            $file_name = $post->cover_image;
        }
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'cover_image' => $file_name,
            'pinned' => $request->pinned,
        ]);
        $post = Post::find($id);
        $response = [
            'status' => "success",
            'msg' => 'post updated',
            'data' => $post,
        ];
        return response()->json($response);
    }

    public function destroy($id)
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        $post = Post::where('id', $id)->where('user_id',$user->id)->update(['removed'=>1]);
        if($post){
            $response = ['status' => "success", 'msg' => 'post deleted', 'data' => ""];
        }else{
            $response = [
                'status' => "fail",
                'msg' => "No Such Post Found",
                'data' => "",
            ];
        }
        return response()->json($response);
    }

    public function restore($id)
    {
        /** @var \App\Models\User $user **/
        $user = auth('sanctum')->user();
        $post = Post::where('id', $id)->where('user_id',$user->id)->update(['removed'=>0]);
        if($post){
            $response = ['status' => "success", 'msg' => 'post restored', 'data' => ""];
        }else{
            $response = [
                'status' => "fail",
                'msg' => "No Such Post Found",
                'data' => "",
            ];
        }
        return response()->json($response);
    }
}
