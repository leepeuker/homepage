<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keyword;
use App\Bookmark;
use App\BookmarkKeyword;

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
        $keywords = Keyword::orderBy('created_at','desc')->get();

        return view('bookmarks.index')->with('keywords', $keywords);
    }
    
    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMany(Request $request)
    {
        if (!empty($request->input('searchTerm')) || !empty($request->input('keywords'))) {

            switch ($request->input('searchColumn')) {
                case 'title':
                    $bookmarks = Bookmark::with('keywords')->where('title', 'like', '%' . $request->input('searchTerm') . '%')->orderBy('created_at','desc')->paginate(10);
                    break;
                
                case 'url':
                    $bookmarks = Bookmark::with('keywords')->orderBy('created_at','desc')->where('url', 'like', '%' . $request->input('searchTerm') . '%')->paginate(10);
                    break;

                case 'keywords':
                
                    // if ($request->input('keywords') ) {

                    //     foreach ($request->input('keywords') as $keywordId) {
                
                    //         $bookmarkKeywords = BookmarkKeyword::where('keyword_id', $keywordId)->get();
                    //         var_dump($bookmarkKeywords);exit;
                    //         array_push($bookmarkIds, $bookmarkKeyword->bookmark_id);
                    //     }
                    // }

                    // var_dump($bookmarkKeywords);exit;

                    // if ($bookmarkKeywords) {

                    //     $bookmarkIds = [];

                    //     foreach ($bookmarkKeywords as $bookmarkKeyword) {
            
                    //         array_push($bookmarkIds, $bookmarkKeyword->bookmark_id);
                    //     }
                    // }

                    $bookmarks = Bookmark::with('keywords')->orderBy('created_at','desc')->paginate(10);
                    break;

                default:
                    $bookmarks = Bookmark::with('keywords')->orderBy('created_at','desc')->paginate(10);
                    break;
            }
            
        } else {
            $bookmarks = Bookmark::with('keywords')->orderBy('created_at','desc')->paginate(10);
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
        $keywords = Keyword::orderBy('word','desc')->get();

        return view('bookmarks.create')->with('keywords', $keywords);
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
        $bookmark->save();

        $selectedKeywords = $request->input('keywords');

        if ($selectedKeywords) {

            foreach ($selectedKeywords as $index => $word) {

                if (substr($word, 0, 2) === '__') {
                    $keyword = new Keyword;
                    $keyword->word = substr($word, 2);
                    $keyword->save();
                    $selectedKeywords[$index] = $keyword->id;
                }
            }
        }

        $bookmark->keywords()->sync($selectedKeywords, false);
        
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
        $keywords = Keyword::orderBy('word','asc')->get();

        return view('bookmarks.edit')->with('bookmark', $bookmark)->with('keywords', $keywords);
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

        $selectedKeywords = $request->input('keywords');

        if ($selectedKeywords) {

            foreach ($selectedKeywords as $index => $word) {

                if (substr($word, 0, 2) === '__') {
                    $keyword = new Keyword;
                    $keyword->word = substr($word, 2);
                    $keyword->save();
                    $selectedKeywords[$index] = $keyword->id;
                }
            }
        }

        $bookmark->keywords()->sync($selectedKeywords);

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

        $bookmark->delete();
        
        return redirect('/bookmarks')->with('success', 'Bookmark "'. $bookmark->title .'" deleted.');
    }
}
