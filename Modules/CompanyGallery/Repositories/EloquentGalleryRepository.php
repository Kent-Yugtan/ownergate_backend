<?php
// app/Repositories/EloquentGalleryRepository.php

namespace Modules\CompanyGallery\Repositories;

use Modules\CompanyGallery\Repositories\Interfaces\GalleryRepositoryInterface;
use Modules\CompanyGallery\App\Models\Gallery;
use Modules\CompanyGallery\Transformers\GalleryResource;
use Modules\CompanyGallery\Transformers\PropertyResource;
use Modules\CompanyGallery\Transformers\EmployeeResource;
use Auth;

class EloquentGalleryRepository implements GalleryRepositoryInterface
{
    public function getAllGalleriesByCompanyId(int $perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        $user = auth()->user();

        $company = $user->company;
        if (!$company) {
            throw new \Exception('Company not found.');
        }

        // Use the company's galleries relationship and paginate the results
        $galleries = $company->galleries()
            ->with(['companyProperty.propertyType']) // Eager load the relationship
            ->paginate($perPage);

        // Transform the results to include fields from related models
        $galleries->getCollection()->transform(function ($gallery) {
            return new GalleryResource($gallery);
        });

        return $galleries;
    }

    public function getGalleryById(int $id): ?array
    {
        $user = Auth::user();
        $gallery = $user->company->galleries()->where('id', $id)->with(['companyProperty.propertyType'])->first();

        if (!$gallery) {
            return null;
        }

        // Create a GalleryResource instance and convert it to array
        $galleryResource = new GalleryResource($gallery);
        return $galleryResource->toArray(request());
    }

    public function createGallery(array $data): array
    {
        // Check if a gallery with the same property_id and company_id already exists
        $existingGallery = Gallery::where('property_id', $data['property_id'])
            ->where('company_id', auth()->user()->company->id)
            ->first();

        if ($existingGallery) {
            // Return a more user-friendly message or throw an exception with additional details
            throw new \Exception('A gallery with this Property ID and Company ID already exists. Please check the details and try again.');
        }
        $data['company_id'] = auth()->user()->company->id;
        // Create new gallery if it does not exist
        $gallery = Gallery::create($data);
        $galleryResource = new GalleryResource($gallery);
        return $galleryResource->toArray(request());
    }

    public function updateGallery(int $id, array $data): bool
    {
        $user = Auth::user();
        $gallery = $user->company->galleries()->where('id', $id);
        if ($gallery) {
            return $gallery->update($data);
        }
        return false;
    }

    public function deleteGallery(int $id): bool
    {
        $user = Auth::user();

        if (!$user || !$user->company) {
            throw new \Exception('User or company not found.');
        }

        // Check if the gallery belongs to the user's company before attempting to delete
        $deleted = $user->company->galleries()->where('id', $id)->delete();

        if (!$deleted) {
            throw new \Exception('Gallery not found or could not be deleted.');
        }

        return true;
    }

    public function searchGalleries(?string $keyword = null, ?string $startDate = null, ?string $endDate = null, ?string $type = null, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Start building the query
        $query = $user->company->galleries()
            ->where('company_id', $user->company->id);
        // Apply keyword search if provided
        
        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                // Search within the 'getAssignTo' relationship
                $query->orWhereHas('getAssignTo', function ($query) use ($keyword) {
                    $query->where('og_code', 'like', "%{$keyword}%")
                          ->orWhere('name', 'like', "%{$keyword}%")
                          ->orWhere('username', 'like', "%{$keyword}%");
                });
            
                // Search within the 'getMaintainBy' relationship
                $query->orWhereHas('getMaintainBy', function ($query) use ($keyword) {
                    $query->where('og_code', 'like', "%{$keyword}%")
                          ->orWhere('name', 'like', "%{$keyword}%")
                          ->orWhere('username', 'like', "%{$keyword}%");
                });
            
                // Search within the 'companyProperty' relationship
                $query->orWhereHas('companyProperty', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                          ->orWhere('og_code', 'like', "%{$keyword}%");
                });
            
            });
        }

        // Apply date range filter if provided
        if ($startDate && $endDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay(); // Set start date to the beginning of the day
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay(); // Set end date to the end of the day
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        // Apply type filter if provided
        if ($type) {
            $query->where(function ($query) use ($type, $keyword) {
                if ($type === 'assigned_to') {
                    $query->whereHas('getAssignTo', function ($query) use ($keyword) {
                        $query->where('og_code', 'like', "%{$keyword}%");
                        $query->where('name', 'like', "%{$keyword}%");
                        $query->where('username', 'like', "%{$keyword}%");

                    });
                } elseif ($type === 'maintained_by') {
                    $query->whereHas('getMaintainBy', function ($query) use ($keyword) {
                        $query->where('og_code', 'like', "%{$keyword}%");
                        $query->where('name', 'like', "%{$keyword}%");
                        $query->where('username', 'like', "%{$keyword}%");

                    });
                } elseif ($type === 'property_name') {
                    $query->whereHas('companyProperty', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('og_code', 'like', "%{$keyword}%");                    });
                } 
            });
        }

        $galleries = $query->with(['companyProperty.propertyType', 'getAssignTo', 'getMaintainBy'])->paginate($perPage);

        // Transform the results to include fields from related models
        $galleries->getCollection()->transform(function ($gallery) {
            return new GalleryResource($gallery);
        });

        return $galleries;
    }

    public function getListing(array $data): array
    {
        $user = Auth::user();
        $galleryLisitng = $user->company->properties()->where('og_code', $data['og_code'])->first();

        if (!$galleryLisitng) {
            return [];
        }
        $listing = new PropertyResource($galleryLisitng);
        return $listing->toArray(request());
    }
    public function getCompanyUsers(): array
    {
        $user = Auth::user();
        $employees = $user->company->CompanyEmployee;
    
        if ($employees->isEmpty()) {
            return []; // Return an empty array if no employees are found
        }
    
        // Transform the employee collection using EmployeeResource
        $employeeResources = $employees->map(function ($employee) {
            return new EmployeeResource($employee);
        });
    
        // Convert the collection of resources to an array
        return $employeeResources->toArray();
    }
}
