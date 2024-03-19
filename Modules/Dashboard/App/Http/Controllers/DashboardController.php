<?php

namespace Modules\Dashboard\App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Modules\Dashboard\App\Models\Shortcut;
use Modules\Dashboard\App\Http\Requests\ShortcutRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Dashboard\Repositories\Interfaces\DashboardRepositoryInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Modules\Dashboard\Transformers\DashboardResource;


class DashboardController extends Controller
{
    use ApiResponser;
    private $DashboardRepository;

    public function __construct(DashboardRepositoryInterface $DashboardRepository)
    {
        $this->DashboardRepository = $DashboardRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(404, 'Need to clarify.');
        /*try {
            $analytics = $this->DashboardRepository->getAnalytics();
            return $this->successresponse($analytics, 'Shortcuts has been returned successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }*/
    }

    public function getShortcuts()
    {
        try {
            $shortcuts = $this->DashboardRepository->getShortcuts();
            return $this->successresponse($shortcuts, 'Shortcuts has been returned successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function addShortcut(ShortcutRequest $request)
    {
        DB::beginTransaction();

        try {
            $shortcut = $this->DashboardRepository->addShortcut($request);

            DB::commit();
            return $this->successresponse($shortcut, 'Shortcut has been added successfully.');
            //return $this->successresponse(ShortcutResource::collection($shortcut), 'Shortcut has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateShortcut(ShortcutRequest $request, Shortcut $shortcut)
    {
        DB::beginTransaction();

        try {
            $shortcut = $this->DashboardRepository->updateShortcut($request, $shortcut);

            DB::commit();
            return $this->successresponse($shortcut, 'Shortcut has been updated successfully.');
            //return $this->successresponse(ShortcutResource::collection($shortcut), 'Shortcut has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function deleteShortcut(Shortcut $shortcut)
    {
        DB::beginTransaction();

        try {
            $shortcut = $this->DashboardRepository->deleteShortcut($shortcut);

            DB::commit();
            return $this->successresponse($shortcut, 'Shortcut has been deleted successfully.');
            //return $this->successresponse(ShortcutResource::collection($shortcut), 'Shortcut has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
