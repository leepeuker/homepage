<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Tag;
use App\Bookmark;
use App\BookmarkTag;

class BookmarksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('text','asc')->get();

        return view('bookmarks.index')->with('tags', $tags);
    }
    
    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMany(Request $request)
    {
        if (!empty($request->input('searchTerm')) || !empty($request->input('tags'))) {

            switch ($request->input('searchColumn')) {
                case 'title':
                    $bookmarks = Bookmark::with('tags')->where('title', 'like', '%' . $request->input('searchTerm') . '%')->orderBy('created_at','desc')->paginate(10);
                    break;
                
                case 'url':
                    $bookmarks = Bookmark::with('tags')->where('url', 'like', '%' . $request->input('searchTerm') . '%')->orderBy('created_at','desc')->paginate(10);
                    break;

                case 'tags':
                    $bookmarks = Bookmark::with('tags')->whereHas('tags', function($query) use($request) {
                        $query->whereIn('id', $request->input('tags'));
                    })->withCount('tags')->has('tags', '>=', count($request->input('tags')))->orderBy('tags_count', 'desc')->paginate(10);
                    break;

                default:
                    $bookmarks = Bookmark::with('tags')->orderBy('created_at','desc')->paginate(10);
                    break;
            }
            
        } else {
            $bookmarks = Bookmark::with('tags')->orderBy('created_at','desc')->paginate(10);
        }
        

        return response()->json($bookmarks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::orderBy('text','asc')->get();

        return view('bookmarks.create')->with('tags', $tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url',
            'title' => 'required'
        ]);
        
        $bookmark = new Bookmark;
        $bookmark->url = $request->input('url');
        $bookmark->title = $request->input('title');
        $bookmark->user_id = auth()->user()->id;
        $bookmark->setFavicon($request->input('url'));
        $bookmark->save();

        $selectedTags = $request->input('tags');

        if ($selectedTags) {

            foreach ($selectedTags as $index => $text) {

                if (substr($text, 0, 2) === '__') {
                    $tag = new Tag;
                    $tag->text = substr($text, 2);
                    $tag->save();
                    $selectedTags[$index] = $tag->id;
                }
            }
        }
        
        $bookmark->tags()->sync($selectedTags, false);
        
        return redirect('/bookmarks')->with('success', 'Bookmark was created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/bookmarks');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bookmark = Bookmark::find($id);
        $tags = Tag::orderBy('text','asc')->get();

        return view('bookmarks.edit')->with('bookmark', $bookmark)->with('tags', $tags);
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
        $this->validate($request, [
            'url' => 'required|url',
            'title' => 'required'
        ]);

        $bookmark = Bookmark::find($id);
        $bookmark->url = $request->input('url');
        $bookmark->title = $request->input('title');
        $bookmark->save();

        $selectedTags = $request->input('tags');

        if ($selectedTags) {

            foreach ($selectedTags as $index => $markedText) {

                if (substr($markedText, 0, 2) === '__') {
                    $tag = new Tag;
                    $tag->text = substr($markedText, 2);
                    $tag->save();
                    $selectedTags[$index] = $tag->id;
                }
            }
        }

        $bookmark->tags()->sync($selectedTags);

        return redirect('/bookmarks')->with('success', 'Bookmark Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bookmark = Bookmark::find($id);
        
        if(auth()->user()->admin){

            return redirect('/bookmarks')->with('error', 'Unauthorized Page');
        }

        if ($bookmark->favicon) {

            Storage::delete('public/favicons/'. $bookmark->favicon);
        }

        $bookmark->delete();
        
        return redirect('/bookmarks')->with('success', 'Bookmark "'. $bookmark->title .'" deleted.');
    }
}
