<?php

namespace Modules\MainScreen\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\MainScreen\App\Models\MainScreen;
use Modules\MainScreen\Transformers\MainScreenResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponser;

class MainScreenController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $settings = MainScreen::orderBy('id', 'desc')->first();
            return $this->successResponse(new MainScreenResource($settings), 'Settings has been retrieved.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $settings = MainScreen::orderBy('id', 'desc')->first();
            $id = !is_null($settings) ? $settings->id : null;
            
            $settings = MainScreen::updateOrCreate(
                ['id' => $id],
                ['title' => $request->title]
            );

            $images = ['logo', 'banner'];
            foreach($images as $field){
                if($request->filled($field) || $request->hasFile($field)){
                    $this->saveImagePath($request, $settings, $field);
                }
            }

            return $this->successResponse(new MainScreenResource($settings), 'Settings has been saved.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    private function saveImagePath($request, $settings, $field){
        $path = $request[$field];

        if(is_file($request[$field])){
            $path = $request->file($field)->store('mainscreen');
        }

        $settings->update([$field => $path]);
    }
}
