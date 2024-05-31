<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait ApiHelper
{
    public function upload_files(Request $request)
    {
        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $fileName = time().'.'.$request->file->extension();

                // Store the image in the storage disk
                $request->file->storeAs($this->module_name, $fileName);

                $imageUrl = Storage::url("{$this->module_name}/{$fileName}");

                return $this->successresponse(['file_path' =>$imageUrl], 'Files is uploaded successfully');
            }

            return $this->errorResponse(null, 'No file has been uploaded');

        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function formatDataForSync(array $validatedData, $customKey = "id")
    {
        $formattedData = [];

        foreach ($validatedData as $categoryData) {
            $attr = [];
            foreach (array_keys($categoryData) as $categoryDataKey) {
                if ($categoryDataKey !== $customKey) {
                    $attr[$categoryDataKey] = $categoryData[$categoryDataKey];
                }

                $formattedData[$categoryData[$customKey]] = $attr;
            }
        }
        
        return $formattedData;
    }

    public function generateOGCode(User $user)
    {
        $code = null;
        $length = 4;
        $id = sprintf("%0". $length . "d", $user->id);
        $role = $user->getRoleNames()->first();
        
        switch ($role) {
            case 'Admin':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'SU ' . implode(' ', str_split($code, 4)) . $id;
                break;
            
            case 'Owner':
                $code = $this->formatPartnerCode($user);
                break;

            case 'Employee':
                if ($user->employeeAccount->company_id) {
                    $company_name = $user->employeeAccount->company->company_name;
                    $code = 'OG' . strtoupper(substr($company_name, 0, 6));
                    $code = 'EM ' . implode(' ', str_split($code, 4)) . $id;
                }
                break;
            case 'Developer':
                $code = $this->formatPartnerCode($user);
                break;

            case 'Real Estate':
                $code = $this->formatPartnerCode($user);
                break;
            
            case 'Vendor':
                $code = $this->formatPartnerCode($user);
                break;

            case 'Agent':
                $code = $this->formatPartnerCode($user);
                break;

            case 'Customer':
                $length = 9;
                $code = sprintf("CU %0". $length . "d", $user->id);
                $code = preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1 ', $code);
                break;
        }
       
        return $code;
    }

    public function formatOGCode($code){
        $code = preg_replace('/\s+/', '', $code);
        
        $type = substr($code, 0, 2);
        $addmail = wordwrap(substr($code, 2), 4, ' ', true );

        return strtoupper($type . ' ' . $addmail);
    }

    public function formatPartnerCode($user)
    {
        $role = $user->getRoleNames()->first();
        $types = [
            'Owner' => 'OW', 
            'Developer' => 'DE', 
            'Agent' => 'AG', 
            'Real Estate' => 'RE', 
            'Vendor' => 'VE'
        ];
        $code = $types[$role] . 'OG'. preg_replace('/\s+/', '', substr($user->company->company_name, 0, 6));
        $id = '';

        if(strlen($code) < 10){
            $diff = 10 - strlen($code);
            $id = $this->addZeroFormat($user->company->id, $diff);
        }

        $code = $code . $id;
        return $this->formatOGCode($code);
    }

    public function addZeroFormat($id, $digits = 4)
    {
        return substr(str_pad($id, $digits, '0', STR_PAD_LEFT), -$digits);
    }

    
}
