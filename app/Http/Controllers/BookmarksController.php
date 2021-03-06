<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Model;
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
        $tags = DB::select('SELECT DISTINCT tags.id, tags.text FROM tags JOIN bookmark_tag ON bookmark_tag.tag_id = tags.id ORDER BY tags.text ASC');

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
        // Get base query
        $query = Bookmark::query();
        
        // Add tags to query
        if (!empty($request->input('tags'))) {

            foreach ($request->input('tags') as $tag_id) {

                $query->whereHas('tags', function($q) use ($tag_id) {

                    $q->where('tag_id', $tag_id);
                });
            }
        }

        // Add searchterm to query
        if (!empty($request->input('searchTerm'))) {
            
            if ($request->input('searchColumn') == 'title') {

                $query ->where('title', 'like', '%' . $request->input('searchTerm') . '%');
            }

            if ($request->input('searchColumn') == 'url') {

                $query ->where('url', 'like', '%' . $request->input('searchTerm') . '%');
            }
        }
            
        // Execute query
        $bookmarks = $query->with('tags')->orderBy('created_at','desc')->paginate(10);

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
        if(auth()->user()->admin) {
            
            $this->validate($request, [
                'url' => 'required|unique:bookmarks|url',
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

        return redirect('/bookmarks')->with('warning', 'Not authorized to create bookmark');
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
        if(auth()->user()->admin) {
            
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

        return redirect('/bookmarks')->with('warning', 'Not authorized to update bookmark');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->admin) {

            $bookmark = Bookmark::find($id);

            if ($bookmark->favicon) {
    
                Storage::delete('public/favicons/'. $bookmark->favicon);
            }
    
            $bookmark->delete();
            
            return redirect('/bookmarks')->with('success', 'Bookmark "'. $bookmark->title .'" deleted.');
        }

        return redirect('/bookmarks')->with('warning', 'Not authorized to delete bookmark');
    }
}
