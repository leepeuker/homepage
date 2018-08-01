<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bookmark;

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
        return view('bookmarks.index');
    }
    
    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMany(Request $request)
    {
        if (!empty($request->input('searchTerm'))) {

            switch ($request->input('searchColumn')) {
                case 'title':
                    $bookmarks = Bookmark::orderBy('created_at','desc')->where('title', 'like', '%' . $request->input('searchTerm') . '%')->paginate(10);
                    break;
                
                case 'url':
                    $bookmarks = Bookmark::orderBy('created_at','desc')->where('url', 'like', '%' . $request->input('searchTerm') . '%')->paginate(10);
                    break;

                default:
                    $bookmarks = Bookmark::orderBy('created_at','desc')->where('title', 'like', '%' . $request->input('searchTerm') . '%')->paginate(10);
                    break;
            }
        } else {
            $bookmarks = Bookmark::orderBy('created_at','desc')->paginate(10);
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
        return view('bookmarks.create');
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

        return redirect('/bookmarks/create')->with('success', 'Bookmark was created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
        
        // Check for correct user
        if(auth()->user()->admin){
            return redirect('/bookmarks')->with('error', 'Unauthorized Page');
        }

        $bookmark->delete();
        return redirect('/bookmarks')->with('success', 'Bookmark "'. $bookmark->title .'" deleted.');
    }
}
