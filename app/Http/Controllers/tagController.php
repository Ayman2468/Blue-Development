<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class tagController extends Controller
{
    //
    public function index()
    {
        $tags = Tag::all();
        $response = [
            'status' => "success",
            'msg' => "",
            'data' => $tags,
        ];
        return response()->json($response);
    }

    public function show($tag_id)
    {
        $tag = Tag::find($tag_id);
        if($tag){
            $response = [
                'status' => "success",
                'msg' => "",
                'data' => $tag,
            ];
        }else{
            $response = [
                'status' => "fail",
                'msg' => "No Such Tag Found",
                'data' => "",
            ];
        }
        return response()->json($response);
    }

    public function create(TagRequest $request)
    {
        $tag = Tag::create([
            'name' => $request->name,
        ]);
        $response = [
            'status' => "success",
            'msg' => 'tag created',
            'data' => $tag,
        ];
        return response()->json($response);
    }

    public function update(TagRequest $request, $id)
    {
        Tag::where('id', $id)->update([
            'name' => $request->name
        ]);
        $tag = Tag::find($id);
        $response = [
            'status' => "success",
            'msg' => 'tag updated',
            'data' => $tag,
        ];
        return response()->json($response);
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        if($tag){
            $tag->delete();
            $response = ['status' => "success", 'msg' => 'tag deleted', 'data' => ""];
        }else{
            $response = [
                'status' => "fail",
                'msg' => "No Such Tag Found",
                'data' => "",
            ];
        }
        return response()->json($response);
    }
}
