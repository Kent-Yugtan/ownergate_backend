<?php

namespace Modules\CompanyGallery\Repositories\Interfaces;
use \Illuminate\Pagination\LengthAwarePaginator;
interface GalleryRepositoryInterface
{
    public function getAllGalleriesByCompanyId(int $id, int $perPage = 15):  LengthAwarePaginator;
    public function getGalleryById(int $id): ?array;
    public function createGallery(array $data): array;
    public function updateGallery(int $id, array $data): bool;
    public function deleteGallery(int $id): bool;
    public function searchGalleries(int $companyId, ?string $keyword = null, ?string $startDate = null, ?string $endDate = null, ?string $type = null, int $perPage = 15): LengthAwarePaginator;


}
