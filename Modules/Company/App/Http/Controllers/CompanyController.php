<?php

namespace Modules\Company\App\Http\Controllers;

use Modules\CompanyProperty\App\Models\Property;
use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\Company\App\Models\CompanyAttachment;
use Modules\Company\Transformers\AttachmentResource;
use Modules\Company\Transformers\CompanyResource;
use Modules\Company\App\Models\CompanyTeam;
use Modules\Company\Repositories\Interfaces\CompanyRepositoryInterface;
use Modules\CompanyProperty\App\Resources\PropertyResource;

class CompanyController extends Controller
{
    use ApiResponser, ApiHelper;

    private $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index(Company $company)
    {
        try {
            $owner_company = auth()->user()->company;

            if (!$owner_company->is($company)) {
                abort(403, 'Unauthorized action.');
            }

            return $this->successresponse(new CompanyResource($company));
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function addAttachment(Company $company, Request $request)
    {
        try {
            $attachment = $this->companyRepository->addAttachment($company, $request->name, $request->file('file'), $request->type);
            return $this->successresponse(new AttachmentResource($attachment), 'The Attachment has been uploaded successfully.');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function removeAttachment(Company $company, CompanyAttachment $attachment)
    {
        try {
            $this->companyRepository->removeAttachment($attachment);
            return $this->successresponse($company, 'The Attachment has been removed successfully.');
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
    
    public function getAttachments(Company $company, Request $request)
    {
        try {
            $perPage = $request->perPage ?? 10;

            $attachments = $company->attachments()->paginate($perPage);

            return $attachments;
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $profile = $this->companyRepository->saveInfo($request);

            DB::commit();

            return $this->successresponse(new CompanyResource($profile), 'Profile has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function getProperties(Company $company, Request $request)
    {
        try {
            $properties = $this->companyRepository->getCompanyProperties($company, $request);
            return PropertyResource::collection($properties);
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
    
    public function changeStatus(Company $company, Request $request)
    {
        try {

            DB::beginTransaction();
            $account = $this->companyRepository->changeStatus($company, $request);
            DB::commit();

            return $this->successresponse($account, 'The status of account has been changed successfully.');
       
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function changePrivacy(Company $company, Request $request)
    {
        try {

            DB::beginTransaction();
            $account = $this->companyRepository->changePrivacy($company, $request);
            DB::commit();

            return $this->successresponse($account, 'The privacy of account has been changed successfully.');

        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
