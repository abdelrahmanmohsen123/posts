<?php

namespace App\Http\Controllers\Api;
use Exception;
use App\Models\Post;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Posts\PostIndexResource;
use App\Http\Requests\Api\Posts\PostStoreRequest;
use App\Http\Requests\Api\Posts\PostUpdateRequest;

class PostController extends Controller
{
    use ApiResponder;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::all();
        return $this->respondResource(PostIndexResource::collection($posts));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        //
        try{
            $data = $request->validated();
            $data['user_id'] = auth()->user()->id;
            $new_post = Post::create($data);
            return $this->respondResource(new PostIndexResource($new_post),[
                'message'=>'Post Created Success !'
            ]);
        }catch(Exception $e){
            return $this->setStatusCode(422)->respondWithError($e->getMessage());


        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        if(!$post){
            return $this->setStatusCode(404)->respondWithError('Not Found Post !');
        }
        return $this->respondResource(new PostIndexResource($post));
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request,$id)
    {
        //
        try{
            $data = $request->validated();
            $post = Post::find($id);
            if(!$post){
                return $this->setStatusCode(404)->respondWithError('Not Found Post !');
            }
            $post->update($data);
            return $this->respondResource(new PostIndexResource($post),[
                'message'=>'Post Updated Success !'
            ]);
        }catch(Exception $e){
            return $this->setStatusCode(422)->respondWithError($e->getMessage());


        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //

        try{
            $post = Post::find($id);
            if(!$post){
                return $this->setStatusCode(404)->respondWithError('Not Found Post !');
            }
            $post->delete();
            return $this->respondWithSuccess('Post Deleted Success !');
        }catch(Exception $e){
            return $this->setStatusCode(422)->respondWithError($e->getMessage());


        }

    }
}
