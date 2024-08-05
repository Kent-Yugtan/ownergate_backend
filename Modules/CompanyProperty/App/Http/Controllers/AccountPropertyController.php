<?php

namespace Modules\CompanyProperty\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\Transformers\PropertyResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountPropertyController extends Controller
{
    use ApiResponser, ApiHelper;

    public function list(Request $request): AnonymousResourceCollection
    {
        $owner = auth()->user();

        $perPage = $request->perPage ?? 10;

        $properties = $owner->company->properties()
            ->when($request->keyword, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('og_code', 'like', '%' . $request->keyword . '%')
                ->orWhere('state', 'like', '%' . $request->keyword . '%')
                ->orWhere('city', 'like', '%' . $request->keyword . '%');
            })
            ->paginate($perPage);

        return PropertyResource::collection($properties);
    }

    public function getSourceProperties(Request $request)
    {
        $source_types = ['Complex Village', 'Villa', 'House'];
        $perPage = $request->perPage ?? 10;
        $owner = auth()->user();
        

        return $owner->company->properties()
            ->whereHas('propertyType', function ($query) use ($source_types, $request) {
                $query->whereIn('name', $source_types);
            })
            ->where('country', 'like', '%'. $request->country .'%')
            ->where('state', 'like', '%'. $request->state .'%')
            ->where('city', 'like', '%'. $request->city .'%')
            ->pluck('og_code');
    }

    public function searchBuilding(Request $request)
    {
        $source_types = [
            'Residential Buildings',
            'Commercial Buildings'
        ];
        
        $perPage = $request->perPage ?? 10;
        $og_code = $request->og_code ?? null;
        $owner = auth()->user();

        return $owner->company->properties()
            ->whereHas('sourceProperty', function ($query) use ($og_code) {
                $query->where('og_code', $og_code);
            })
            ->whereHas('propertyType', function ($query) use ($source_types) {
                $query->whereIn('name', $source_types);
            })
            ->pluck('og_code');
    }

    public function searchUnits(Request $request)
    {
        $source_types = [
            'Multiple Unit'
        ];
        
        $perPage = $request->perPage ?? 10;
        $og_code = $request->og_code ?? null;
        $owner = auth()->user();

        return $owner->company->properties()
            ->whereHas('sourceProperty', function ($query) use ($og_code) {
                $query->where('og_code', $og_code);
            })
            ->whereHas('propertyType', function ($query) use ($source_types) {
                $query->whereIn('name', $source_types);
            })
            ->pluck('og_code');
    }

    public function searchSpots(Request $request)
    {
        $source_types = [
            'Spot'
        ];
        
        $perPage = $request->perPage ?? 10;
        $og_code = $request->og_code ?? null;
        $owner = auth()->user();

        return $owner->company->properties()
            ->whereHas('sourceProperty', function ($query) use ($og_code) {
                $query->where('og_code', $og_code);
            })
            ->whereHas('propertyType', function ($query) use ($source_types) {
                $query->whereIn('name', $source_types);
            })
            ->pluck('og_code');
    }

    public function updatePropertySourceLevel(Request $request, CompanyProperty $property)
    {
        $owner = auth()->user();
        $og_code = $request->og_code ?? null;

        if (!$owner->company->properties->contains($property)) {
            abort(403, 'Unauthorized action.');
        }

        $source_property = $owner->company->properties()
            ->where('og_code', $og_code)
            ->first();

        $property->update([
            'source_property_id' => $source_property->id
        ]);

        return new PropertyResource($property);
    }


}
