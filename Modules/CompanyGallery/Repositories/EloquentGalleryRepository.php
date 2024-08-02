<?php
// app/Repositories/EloquentGalleryRepository.php

namespace Modules\CompanyGallery\Repositories;

use Modules\CompanyGallery\Repositories\Interfaces\GalleryRepositoryInterface;
use Modules\CompanyGallery\App\Models\Gallery;

class EloquentGalleryRepository implements GalleryRepositoryInterface
{
    public function getAllGalleriesByCompanyId(int $id, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        // Retrieve paginated galleries with related company properties
        $galleries = Gallery::where('company_id', $id)
            ->with(['companyProperty.propertyType']) // Eager load the relationship
            ->paginate($perPage);

        // Transform the results to include fields from related models
        $galleries->getCollection()->transform(function ($gallery) {
            return [
                'id' => $gallery->id,
                'property_id' => $gallery->property_id,
                'property_name' => $gallery->companyProperty ? $gallery->companyProperty->name : null,
                'type' => $gallery->companyProperty && $gallery->companyProperty->propertyType
                    ? $gallery->companyProperty->propertyType->name : null, // Add related field
                'assigned_to' => $gallery->assigned_to,
                'maintained_by' => $gallery->maintained_by,
                'company_id' => $gallery->company_id,
            ];
        });

        return $galleries;
    }

    public function getGalleryById(int $id): ?array
    {
        $gallery = Gallery::with(['companyProperty.propertyType'])->find($id);

        if (!$gallery) {
            return null;
        }

        return [
            'id' => $gallery->id,
            'property_id' => $gallery->property_id,
            'property_name' => $gallery->companyProperty ? $gallery->companyProperty->name : null,
            'type' => $gallery->companyProperty && $gallery->companyProperty->propertyType
                ? $gallery->companyProperty->propertyType->name : null, // Add related field
            'assigned_to' => $gallery->assigned_to,
            'maintained_by' => $gallery->maintained_by,
            'company_id' => $gallery->company_id,
        ];
    }

    public function createGallery(array $data): array
    {
        // Check if a gallery with the same property_id and company_id already exists
        $existingGallery = Gallery::where('property_id', $data['property_id'])
            ->where('company_id', $data['company_id'])
            ->first();

        if ($existingGallery) {
            // Return a more user-friendly message or throw an exception with additional details
            throw new \Exception('A gallery with this Property ID and Company ID already exists. Please check the details and try again.');
        }

        // Create new gallery if it does not exist
        $gallery = Gallery::create($data);
        return $gallery->toArray();
    }

    public function updateGallery(int $id, array $data): bool
    {
        $gallery = Gallery::find($id);
        if ($gallery) {
            return $gallery->update($data);
        }
        return false;
    }

    public function deleteGallery(int $id): bool
    {
        $gallery = Gallery::find($id);
        return $gallery ? $gallery->delete() : false;
    }

    public function searchGalleries(int $companyId, ?string $keyword = null, ?string $startDate = null, ?string $endDate = null, ?string $type = null, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Gallery::where('company_id', $companyId);

        if ($keyword) {
            $query->where(function($query) use ($keyword) {
                $query->where('assigned_to', 'like', "%{$keyword}%")
                      ->orWhere('maintained_by', 'like', "%{$keyword}%");
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($type) {
            $query->where(function($query) use ($type, $keyword) {
                if ($type === 'assigned_to') {
                    $query->where('assigned_to', 'like', "%{$keyword}%");
                } elseif ($type === 'maintained_by') {
                    $query->where('maintained_by', 'like', "%{$keyword}%");
                }
            });
        }

        return $query->with(['companyProperty.propertyType'])->paginate($perPage);
    }
}
