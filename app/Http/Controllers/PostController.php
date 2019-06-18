<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;

class PostController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function InsertPost(Request $request)
    {	
    	//Validation from laravel docu
    	$validatedData = $request->validate([
	        'title' => 'required|unique:posts|max:255',
	        'author' => 'required|min:4|max:15',
	        'tag' => 'required',
	        'desc' => 'required',
	    ]);
    	//Validation End

	    $post = new Post;
	    $post->title = $request->title;
	    $post->author = $request->author;
	    $post->tag = $request->tag;
	    $post->desc = $request->desc;

	    $post->save();

	    //Toastr Msg Show
	    if ($post->save()) {
	    	$notification=array(
	    		'message'=>'Post Added Successfully',
	    		'alert-type'=>'success'
	    	);
	    	return Redirect()->back()->with($notification);
	    }else{
	    	return Redirect()->back();
	    }
    }

    public function AllPost()
    {
    	$post=Post::all();
    	return view('all_post')->with('post',$post);
    }

    public function EditPost($id)
    {	
    	$post=Post::findorfail($id);
    	return view('edit_post', compact('post'));
    }

    public function UpdatePost(Request $request, $id)
    {	
    	//Validation from laravel docu
    	$validatedData = $request->validate([
	        'title' => 'required|unique:posts|max:255',
	        'author' => 'required|min:4|max:15',
	        'tag' => 'required',
	        'desc' => 'required',
	    ]);
    	//Validation End
    	
    	$post = Post::findorfail($id);
    	$post->title = $request->title;
	    $post->author = $request->author;
	    $post->tag = $request->tag;
	    $post->desc = $request->desc;

	    $update=$post->save();

    	if ($update) {
	    	$notification=array(
	    		'message'=>'Post Update Successfully',
	    		'alert-type'=>'success'
	    	);
	    	return Redirect()->route('home')->with($notification);
	    }else{
	    	return Redirect()->back();
	    }
    }

    public function DeletePost($id)
    {
    	$post = Post::find($id);
    	$delete=$post->delete();

    	//Toastr Msg Show
	    if ($delete) {
	    	$notification=array(
	    		'message'=>'Post Delete Successfully',
	    		'alert-type'=>'error'
	    	);
	    	return Redirect()->back()->with($notification);
	    }else{
	    	return Redirect()->back();
	    }
    }

    public function NewsAdd()
    {
    	return view('news_add');
    }

    public function InsertNews(Request $request)
    {
    	$validatedData = $request->validate([
	        'title' => 'required|unique:posts|max:255',
	        'author' => 'required|min:4|max:15',
	        'image' => 'required',
	        'details' => 'required',
	    ]);

	    $data=array();
	    $data['title']=$request->title;
	    $data['details']=$request->details;
	    $data['author']=$request->author;
	    $img=$request->image;

	    if ($img) {
		  $image_name=str_random(20);
		  $ext=strtolower($img->getClientOriginalExtension());
		  $image_full_name=$image_name.'.'.$ext;
	      $upload_path='public/post/';
	      $image_url=$upload_path.$image_full_name;
	      $success=$img->move($upload_path,$image_full_name);  

	      if ($success) {
	        $data['image']=$image_url;
   	        $post=DB::table('news')->insert($data); 
		    if ($post) {
		           $notification=array(
		           'message'=>'Post Inserted Successfully',
		           'alert-type'=>'success'
		           );
		           return Redirect()->route('news.add')->with($notification);                      
		        }else{
		          return Redirect()->back();
		        } 
		   }
		}else{
		  return Redirect()->back();
		} 
	}

	public function AllNews()
	{	
		$news=DB::table('news')->get();
		return view('all_news', compact('news'));
	}

	public function DeleteNews($id)
	{
		$img=DB::table('news')->where('id',$id)->first();
		$image_path=$img->image;
		$dlt_done=unlink($image_path);

		$delete=DB::table('news')->where('id',$id)->delete();

		if ($delete) {
		           $notification=array(
		           'message'=>'Post Deleted Successfully',
		           'alert-type'=>'success'
		           );
		           return Redirect()->route('all.news')->with($notification);                      
		        }else{
		          return Redirect()->back();
		        } 
		   
	}

}
