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
use Modules\Company\App\Http\Requests\CompanyRequest;

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
            return $this->successresponse(new CompanyResource($company));
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function addAttachment(Company $company, Request $request)
    {
        try {
            if($request->filled('bulk')){
                $attachment = $this->companyRepository->uploadAttachments($company, $request);
                return $this->successresponse(AttachmentResource::collection($company->attachments), 'The Attachment has been uploaded successfully.'); 
            }else{
                $payload = $request->all();
                $attachment = $this->companyRepository->addAttachment($company, $payload);
                return $this->successresponse(new AttachmentResource($attachment), 'The Attachment has been uploaded successfully.');
            }
            
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

    public function saveCompany(CompanyRequest $request)
    {   
        DB::beginTransaction();
        try {
            $user = $request->createOrUpdateAdminUser();
            $profile = $this->companyRepository->updateOrCreate($request, $user);

            DB::commit();
            return $this->successresponse(new CompanyResource($profile), 'Company has been saved.');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function lists(Request $request){
        try {
            $companies = $this->companyRepository->lists($request);
            return CompanyResource::collection($companies);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Company $company)
    {
        DB::beginTransaction();
        try {
            $company = $this->companyRepository->updateStatus($company, $request);
            DB::commit();

            return $this->successResponse(new CompanyResource($company), 'Status has been updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

}
