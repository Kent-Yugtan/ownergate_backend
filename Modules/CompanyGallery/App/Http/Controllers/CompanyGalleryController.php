<?php

namespace Modules\CompanyGallery\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Company\App\Models\Company;
use Modules\CompanyGallery\App\Models\Gallery;
use Modules\CompanyGallery\Transformers\GalleryResource;
use Modules\CompanyGallery\Repositories\Interfaces\GalleryRepositoryInterface;

class CompanyGalleryController extends Controller
{
    use ApiResponser, ApiHelper;
    protected $galleryRepository;

    public function __construct(GalleryRepositoryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

 

    public function save(Request $request) {
        try {
            DB::beginTransaction();
          
            $galleryData = $request->all();
            $gallery =  $this->galleryRepository->createGallery($galleryData);

            DB::commit();

            return $this->successresponse($gallery, 'Gallery has been saved.');

        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function list()
    {
        try {
            $perPage = request()->get('perPage', 10); // Get 'perPage' from the request or use 15 as default
            $galleries = $this->galleryRepository->getAllGalleriesByCompanyId($perPage);
    
            return $this->successresponse($galleries);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $gallery = $this->galleryRepository->getGalleryById($id);
    
            return $this->successresponse($gallery);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function update($id, Request $request) {
        try {

            $galleryData = $request->all();

            $gallery = $this->galleryRepository->updateGallery($id, $galleryData);
    
            return $this->successresponse($gallery);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function destroy($gallery)
    {
        try {

            $gallery = $this->galleryRepository->deleteGallery($gallery);

            return $this->successresponse($gallery, 'Gallery has been Successfully Deleted.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->get('keyword');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $type = $request->get('type'); // Added type parameter
            $perPage = $request->get('perPage', 10);   
           
            $galleries = $this->galleryRepository->searchGalleries($keyword, $startDate, $endDate, $type, $perPage);

            return $this->successresponse($galleries);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function getPartnerListing(Request $request) {
        try {

            $listing = $this->galleryRepository->getListing($request->all());    
            
            return $this->successresponse($listing);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function getAllUsers($type) {
        try {

            $users = $this->galleryRepository->getCompanyUsers($type);    
            
            return $this->successresponse($users);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
