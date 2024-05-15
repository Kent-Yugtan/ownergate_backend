<?php

namespace Modules\Company\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\Company\App\Models\CompanyNews;
use Modules\Company\Transformers\NewsResource;
use Modules\Company\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyNewsController extends Controller
{
    use ApiResponser;

    private $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index(Request $request, Company $company)
    {
        $perPage = $request->perPage ?? 10;

        $news = $company->news()->paginate($perPage);

        return NewsResource::collection($news);
    }

    public function showAllNews(Request $request)
    {
        $perPage = $request->perPage ?? 10;

        $news = CompanyNews::where('visibility', 1)
                            ->orderBy('posted_at', 'desc')
                            ->paginate($perPage);

        return NewsResource::collection($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $news = $this->companyRepository->saveNews($request, $company);
            
            DB::commit();

            if ($news instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                return NewsResource::collection($news);
            }

            return $this->successresponse(new NewsResource($news), 'News has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function destroy(Company $company, CompanyNews $news)
    {
        try {
            if (!auth()->user()->hasRole('Admin') && !auth()->user()->company->is($company)) {
                abort(403, 'Unauthorized action.');
            }

            $news = $this->companyRepository->deleteNews($news);

            return $this->successresponse($news, 'News has been Successfully Deleted.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
