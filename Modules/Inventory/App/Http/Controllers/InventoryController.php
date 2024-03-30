<?php

namespace Modules\Inventory\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Inventory\App\Models\Inventory;
use Modules\Inventory\Transformers\InventoryResource;

class InventoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = auth()->user()->inventories;
        return InventoryResource::Collection($inventories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $inventory = auth()->user()->inventories()->create($request->all());

            if ($request->attachments) {
                foreach ($request->attachments as $attachment) {
                    if (is_file($attachment['file'])) {
                        $path = $attachment['file']->store('inventories/' . $inventory->id);
                        $attachment['path'] = $path;

                        $inventory->attachments()->create($attachment);
        
                        DB::commit();
                    }
                }
            }

            DB::commit();

            return $this->successresponse(new InventoryResource($inventory), 'Item has been added.');
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        try {
            DB::beginTransaction();

            $inventory->update($request->all());

            if (!empty($request->attachments) && is_array($request->attachments)) {
                foreach ($request->attachments as $attachment) {
                    $old_attachment = $inventory->attachments()->where('id', $attachment['id'])->first();

                    if(isset($attachment['file']) && is_file($attachment['file'])) {
                        if ($old_attachment) {
                            if ($old_attachment->path) {
                                Storage::delete($old_attachment->path);
                            }

                            $path = $attachment['file']->store('inventories/' . $inventory->id);

                            $old_attachment->update([
                                'title' => $attachment['title'],
                                'path' => $path,
                            ]);
                        } else {
                            $path = $attachment['file']->store('inventories/' . $inventory->id);
                            $attachment['path'] = $path;
    
                            $inventory->attachments()->create($attachment);
                        }
                    } elseif (array_key_exists('file', $attachment) && empty($attachment['file']) && $old_attachment) {
                        Storage::delete($old_attachment->path);
                        $old_attachment->delete();
                    }
                }
            }

            DB::commit();

            return $this->successresponse(new InventoryResource($inventory), 'Item has been added.');
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
