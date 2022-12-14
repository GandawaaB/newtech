<?php

namespace App\Http\Controllers;
use App\Comment;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'search', 'show','comment']);
    }
    
    public function index(Request $request)
    {
        $posts = Post::with('category', 'user')
            ->withCount('comments')
            ->published()
            ->paginate(5);

        return view('home', compact('posts'));
    }

    public function search(Request $request)
    {
        $this->validate($request, ['query' => 'required']);

        $query = $request->get('query');

        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('body', 'like', "%{$query}%")
            ->with('category', 'user')
            ->withCount('comments')
            ->published()
            ->paginate(5);

        return view('post.search', compact('posts'));
    }

    public function show(Post $post)
    {
        $post = $post->load(['comments', 'user', 'category']);

        return view('post.show', compact('post'));
    }

    public function comment(Request $request, Post $post)
    {
        $this->validate($request, ['body' => 'required','name'=>'required']);

        $comment = new Comment;
        
        $comment ->name = $request ->name;
        $comment ->body = $request ->body;
        $comment ->post_id = $post ->id;

        $comment ->save();
        

        // $post->comments()->create([
        //     // 'user_id'   => auth()->id(), 
        //     'name' => $request->name,
        //     'body'      => $request->body           
        // ]);

        session()->flash('message', 'Сэтгэгдэл нийтлэгдлээ.');

        return redirect("/posts/{$post->id}");
            
    }

    public function create()
    {
        return view('user.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|max:250',
            'body'       => 'required|min:50',
            'category'   => 'required|exists:categories,id',
            'publish'    => 'accepted',
            // 'image' => 'mimes:jpeg,bmp,png'
        ]);

        $imageName = time().'.'.$request->image->extension(); 
        $request->image->move(public_path('images'), $imageName);

        $post = Post::create([
            'title'         => $request->title,
            'body'          => $request->body,
            'user_id'       => auth()->id(),
            'category_id'   => $request->category, 
            'is_published'  => $request->has('publish'),
            'image' => $imageName
        ]);

        session()->flash('message', 'Амжилттай нийтэллээ.');

        return redirect()->route('user.posts');
    }

    public function edit(Post $post)
    {
        if($post->user_id != auth()->user()->id && auth()->user()->isNotAdmin()) {

            session()->flash('message', "Танд засах эрх байхгүй байна.");

            return redirect()->route('user.posts'); 
        }

        return view('user.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if($post->user_id != auth()->user()->id && auth()->user()->isNotAdmin()) {

            session()->flash('message', "Танд засах эрх байхгүй байна.");

            return redirect()->route('user.posts');
        }

        $request->validate([
            'title'      => 'required|max:250',
            'body'       => 'required|min:50',
            'category'   => 'required|exists:categories,id',
            'publish'    => 'accepted'
        ]);
        if ($request->image) {

            $image_path = 'images/'.$post->image;

            if (file_exists($image_path)) {
                @unlink($image_path);
            }
            
            $imageName = time().'.'.$request->image->extension(); 
            $request->image->move(public_path('images'), $imageName);

            $post->update([
                'title'       => $request->title,
                'body'        => $request->body,
                'category_id' => $request->category,
                'is_published'  => $request->has('publish'),
                'image' => $imageName
            ]);
            session()->flash('message', 'Нийтлэл амжилттай өөрчлөгдлөө');

            return redirect()->to("/posts/$post->id");

        }else{
            $post->update([
                'title'       => $request->title,
                'body'        => $request->body,
                'category_id' => $request->category,
                'is_published'  => $request->has('publish'),

            ]);

            session()->flash('message', 'Нийтлэл амжилттай өөрчлөгдлөө');

            return redirect()->to("/posts/$post->id");
        }

        
    }

    public function destroy(Post $post)
    {
        if($post->user_id != auth()->user()->id && auth()->user()->isNotAdmin()) {

            session()->flash('message', "Танд устгах эрх байнгүй байна.");

            return redirect()->route('user.posts');
        }
        $image_path = 'images/'.$post->image;

        if (file_exists($image_path)) {
            @unlink($image_path);
        }
        $post->delete();

        session()->flash('message', 'Нийтлэл амжилттай устлаа.');

        return redirect()->route('user.posts');
    }
}