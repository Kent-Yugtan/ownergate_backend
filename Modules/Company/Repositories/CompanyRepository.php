<?php

namespace Modules\Company\Repositories;

//use App\Models\Media;
//use App\Models\MediaPath;
//use App\Models\Property;
use Modules\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Company\App\Models\Company;
use Modules\Company\App\Models\CompanyAttachment;
//use Modules\Company\Entities\CompanyTeam;
use Modules\Company\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Traits\ApiHelper;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    use ApiHelper;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    public function getProfile($company)
    {
        $company = $this->model->find($company);
        return $company;
    }

    public function addAttachment(Company $company, $payload)
    {
        $uniqueFilename = 'attachment' . date('Ymd') . rand(0, 9999) . '.' . $payload['file']->guessExtension();

        $path = $payload['file']->storeAs('company/attachment/' . $company->id . '/', $uniqueFilename);

        $attachment = $company->attachments()->create([
            "name" => $payload['name'],
            "path" => $path,
            "type" => $payload['type'],
            "visibility" => $payload['visibility'],
        ]);

        return $attachment;
    }

    public function uploadAttachments(Company $company, $payload)
    {
        if ($payload->filled('attachments')) {
            foreach ($payload->attachments as $attachment) {
                $uniqueFilename = 'attachment' . date('Ymd') . rand(0, 9999) . '.' . $attachment['file']->guessExtension();

                $path = $attachment['file']->storeAs('company/attachment/' . $company->id . '/', $uniqueFilename);

                $attachment = $company->attachments()->create([
                    "name" => $attachment['name'],
                    "path" => $path,
                    "type" => $attachment['type'],
                    "visibility" => isset($attachment['visibility']) ? $attachment['visibility'] : 0,
                ]);
            }
        }
        return $company;
    }

    public function removeAttachment(CompanyAttachment $attachment)
    {
        if (Storage::delete($attachment->path)) {
            $company = $attachment->company();
            if ($attachment->delete()) {
                return $company;
            }
        }

        return null;
    }

    public function saveInfo(Request $request)
    {
        $profile = auth()->user()->company()->updateOrCreate([
            'owner_id' => auth()->user()->id
        ], $request->all());

        if ($request->has('avatar')) {
            $this->uploadPhoto($profile, $request->avatar, 'profile_picture');
        }
        if ($request->has('cover_photo')) {
            $this->uploadPhoto($profile, $request->cover_photo, 'profile_poster');
        }

        if ($request->has('profile_picture')) {
            $this->uploadPhoto($profile, $request->profile_picture, 'profile_picture');
        }
        if ($request->has('profile_poster')) {
            $this->uploadPhoto($profile, $request->profile_poster, 'profile_poster');
        }

        return $profile;
    }

    public function updateOrCreate(Request $request, $user)
    {
        $data = $request->all();
        $isUpdate = $request->filled('id');

        if (!$isUpdate) {
            $data = array_merge($data, [
                'addmail' => $this->generateOGCode($user)
            ]);
        }

        $profile = $user->company()->updateOrCreate([
            'owner_id' => $user->id
        ], $data);

        if (!$isUpdate) {
            $profile->users()->attach($user->id);
        }

        if ($request->has('avatar')) {
            $this->uploadPhoto($profile, $request->avatar, 'profile_picture');
        }
        if ($request->has('cover_photo')) {
            $this->uploadPhoto($profile, $request->cover_photo, 'profile_poster');
        }

        if ($request->has('profile_picture')) {
            $this->uploadPhoto($profile, $request->profile_picture, 'profile_picture');
        }
        if ($request->has('profile_poster')) {
            $this->uploadPhoto($profile, $request->profile_poster, 'profile_poster');
        }

        return $profile;
    }

    private function uploadPhoto($profile, $file, $key)
    {
        $path = $file;

        if (is_file($file)) {
            if ($profile->{$key}) {
                Storage::delete($profile->{$key});
            }

            $path = $file->store('company/' . $profile->id);
        }

        $profile->update([$key => $path]);
    }

    public function saveManagement(Request $request, $company)
    {
        return $this->saveOrEdit($company, 'managements', $request->managements, [
            'name',
            'position',
            'image_path',
            'phone_number',
            'visibility'
        ]);
    }

    public function deleteManagement($management)
    {
        if ($management->image_path != null) {
            Storage::delete($management->image_path);
        }
        return $management->delete();
    }

    public function saveNews(Request $request, $company)
    {
        return $this->saveOrEdit($company, 'news', $request->news, [
            'title',
            'description',
            'image_path',
            'posted_at',
            'visibility'
        ]);
    }

    public function deleteNews($news)
    {
        if ($news->image_path != null) {
            Storage::delete($news->image_path);
        }
        return $news->delete();
    }

    public function saveServices(Request $request, $company)
    {
        return $this->saveOrEdit($company, 'services', $request->services, [
            'title',
            'description',
            'visibility'
        ]);
    }

    public function deleteService($service)
    {
        Storage::delete($service->image_path);
        return $service->delete();
    }

    public function saveLocations(Request $request, $company)
    {
        return $this->saveOrEdit($company, 'locations', $request->locations, [
            'office_name',
            'address',
            'latitude',
            'longitude',
            'is_default',
            'visibility'
        ]);
    }

    public function deleteLocation($location)
    {
        return $location->delete();
    }


    public function addMember(Company $company, $request)
    {
        $data = $request->input();
        if ($request->file('image')) {
            $file = $request->file('image');
            $uniqueFilename = 'attachment' . date('Ymd') . rand(0, 9999) . $file->guessExtension();
            $image = $file->storeAs('company/' . $company->id . '/team/', $uniqueFilename);
            $data = array_merge($data, ['image' => $image]);
        }

        $company->users()->create($data);

        return $company;
    }

    public function getAttachments(Company $company, Request $request)
    {
        $per_page = $request->perPage ?? 10;

        $attachments = $company->attachments()->paginate($per_page);

        return $attachments;
    }

    public function getCompanyProperties($company, $request)
    {
        $perPage = $request->perPage ?? 10;
        $properties = $company->properties()->when($request->target_type, function ($q) use ($request) {
            $q->whereHas('target_type', function ($q) use ($request) {
                $q->where('name', $request->target_type);
            });
        })->paginate($perPage);

        return $properties;
    }

    /*public function accountsList(Company $company){

        $accounts = $company->companyUsers()->get();
        return $accounts;

    }*/

    public function changeStatus(Company $company, Request $request)
    {
        $company->status = $request->status;
        $company->save();
        return $company;
    }

    public function changePrivacy(Company $company, Request $request)
    {

        $company->privacy = $request->privacy;
        $company->save();
        return $company;
    }

    protected function saveOrEdit($company, $key, $section_payloads, $attributes)
    {
        $response = null;

        if (!empty($section_payloads) && is_array($section_payloads)) {

            foreach ($section_payloads as $payload) {
                $data = [];

                foreach ($attributes as $attribute) {
                    if (array_key_exists($attribute, $payload)) {
                        $data[$attribute] = $payload[$attribute];
                    } else {
                        if ($attribute === 'posted_at') {
                            $data[$attribute] = now();
                        }
                    }
                }

                if (isset($payload['is_default']) && $payload['is_default']) {
                    // reset default location
                    $company->{$key}()->update(['is_default' => 0]);
                }

                if (isset($payload['id'])) {
                    $old_data = $company->{$key}()->where('id', $payload['id'])->first();

                    if ($old_data) {
                        if (isset($payload['image_path']) && is_file($payload['image_path'])) {
                            if ($old_data->image_path) {
                                Storage::delete($old_data->image_path);
                            }

                            $path = $payload['image_path']->store('company/' . $company->id . '/' . $key);
                            $data['image_path'] = $path;
                        }

                        $old_data->update($data);

                        $response = $old_data;
                    }
                } else {
                    if (isset($payload['image_path']) && is_file($payload['image_path'])) {
                        $path = $payload['image_path']->store('company/' . $company->id . '/' . $key);
                        $data['image_path'] = $path;
                    }

                    $response = $company->{$key}()->create($data);
                }
            }

            if (count($section_payloads) > 1) {
                return $company->{$key}()->paginate(10);
            }

            return $response;
        }
    }

    public function lists($request)
    {
        $perPage = $request->perPage ?? 10;
        $companies = $this->model->withTrashed()->when($request->id, function ($q) use ($request) {
            $q->whereHas('owner', function ($q) use ($request) {
                $q->where('og_code', $request->id);
            });
        })->when('keyword', function ($q) use ($request) {
            $q->where('company_name', 'LIKE', $request->keyword . '%');
        })->paginate($perPage);

        return $companies;
    }

    public function updateStatus($company, $request)
    {
        $company->update(['note' => $request->note, 'status' => $request->status]);
        return $company;
    }
}
