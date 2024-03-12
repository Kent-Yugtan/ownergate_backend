<?php

namespace Modules\Company\Repositories\Interfaces;

use App\Models\Property;
use Illuminate\Http\Request;
use Modules\Company\Entities\Company;
use Modules\Company\Entities\CompanyNews;
use Modules\Company\Entities\CompanyService;
use Modules\Company\Entities\CompanyLocation;
use Modules\Company\Entities\CompanyAttachment;
use Modules\Company\Entities\CompanyManagement;

interface CompanyRepositoryInterface
{
    /*public function getProfile(Company $company);

    public function saveInfo(Request $request);

    public function saveManagement(Request $request, Company $company);

    public function deleteManagement(CompanyManagement $management);
    
    public function saveNews(Request $request, Company $company);

    public function deleteNews(CompanyNews $news);

    public function saveServices(Request $request, Company $company);

    public function deleteService(CompanyService $service);

    public function saveLocations(Request $request, Company $company);

    public function deleteLocation(CompanyLocation $location);

    public function addAttachment(Company $company, $name, $file, $type);

    public function removeAttachment(CompanyAttachment $attachment);

    public function addMember(Company $company, $request);

    public function addProperty(Company $company, Property $property);

    public function getProperties(Company $company, Request $request);

    public function getAttachments(Company $company, Request $request);
    
    public function accountsList(Company $company);

    public function changeStatus(Company $company, Request $request);

    public function changePrivacy(Company $company, Request $request);*/
}
