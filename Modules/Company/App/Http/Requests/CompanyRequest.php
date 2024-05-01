<?php

namespace Modules\Company\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;
use Modules\Company\App\Models\Company;
use App\Traits\ApiHelper;

class CompanyRequest extends FormRequest
{
    use ApiHelper;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'id' => 'nullable',
            'role' => 'required',
            'company_name' => 'required',
            'avatar' => 'nullable',
            'cover_photo' => 'nullable',
            'about' => 'nullable',
            'notes' => 'nullable',
            'mission' => 'nullable',
            'vission' => 'nullable',
            'values' => 'nullable',
            'website' => 'nullable',
            'whatsapp_url' => 'nullable',
            'instagram_url' => 'nullable',
            'twitter_url' => 'nullable',
            'youtube_url' => 'nullable',
            'wechat_url' => 'nullable',
            'telegram_url' => 'nullable',
            'phone' => 'nullable',
            'email' => 'required',
            'gender' => 'nullable',
            'dob' => 'nullable',
            'nationality' => 'nullable',
            'national_id_no' => 'nullable',
            'license_no' => 'nullable',
            'license_expiry' => 'nullable',
            'country' => 'required',
            'province_or_state' => 'nullable',
            'city' => 'nullable',
            'postal_or_zipcode' => 'nullable',
            'address' => 'nullable',
            'managements' => 'nullable',
            'news' => 'nullable',
            'services' => 'nullable',
            'locations' => 'nullable',
            'attachments' => 'nullable',
        ];


        // If there is no id or id is 'null', add the unique rule for email
        if ($this->id === null || $this->id === 'null') {
            $rules['email'] .= '|unique:users';
        } else {
            // Add a rule to ignore the current user's email when updating
            $rules['email'] .= '|unique:users,email,' . $this->id;
        }

        return $rules;
    }

    public function createOrUpdateAdminUser()
    {
        if ($this->id === "null") {
            $user = User::create([
                'email' => $this->email,
                'password' => bcrypt('1234567')
            ]);

            $user->assignRole($this->role);
            $user->markEmailAsVerified();

            $user->profile()->create([
                'first_name' => $this->company_name,
                'last_name' => '',
                'gender' => $this->gender,
                'phone' => $this->phone,
                'dob' => $this->dob,
                'nationality' => $this->nationality,
                'national_id_no' => $this->national_id_no,
                'license_no' => $this->license_no,
                'license_expiry' => $this->license_expiry,
                'country' => $this->country,
                'province_or_state' => $this->province_or_state,
                'city' => $this->city,
                'postal_or_zipcode' => $this->postal_or_zipcode,
                'address' => $this->address,
            ]);

            return $user;
        } else {
            $user = Company::where('id', $this->id)->first()->owner;
            $user->email = $this->email;
            $user->save();
            $user->syncRoles([$this->role]);

            $user->profile()->update([
                'first_name' => $this->company_name,
                'last_name' => '',
                'gender' => $this->gender,
                'phone' => $this->phone,
                'dob' => $this->dob,
                'nationality' => $this->nationality,
                'national_id_no' => $this->national_id_no,
                'license_no' => $this->license_no,
                'license_expiry' => $this->license_expiry,
                'country' => $this->country,
                'province_or_state' => $this->province_or_state,
                'city' => $this->city,
                'postal_or_zipcode' => $this->postal_or_zipcode,
                'address' => $this->address,
            ]);

            return $user;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
