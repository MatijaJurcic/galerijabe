<?php

namespace App\Http\Controllers;

use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index()
    {
        $galleries =  Gallery::with('user')->orderBy('id', 'desc')->paginate(10);

        if ($galleries->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ooops no galleries',
            ], 404);
        };

        return $galleries;
    }

    public function show($id)
    {
        return Gallery::with('user')->with('comments')->findOrFail($id);
    }

    public function store(GalleryRequest $request)
    {
        $gallery = $request->all();
        $gallery['user_id'] = Auth::user()->id;

        return Gallery::create($gallery);
    }

    public function update($id, GalleryRequest $request)
    {
        return Gallery::findOrFail($id)->update($request->all());
    }

    public function destroy($id)
    {
        return Gallery::destroy($id);
    }
}
