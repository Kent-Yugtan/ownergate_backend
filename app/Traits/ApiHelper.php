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

        $role = $user->getRoleNames()->first();
        $code = '';
        
        switch ($role) {
            case 'Admin':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'SU ' . implode(' ', str_split($code, 4));
                break;
            
            case 'Owner':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'OW ' . implode(' ', str_split($code, 4));
                break;

            case 'Employee':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'EM ' . implode(' ', str_split($code, 4));
                break;

            case 'Developer':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'DE ' . implode(' ', str_split($code, 4));
                break;

            case 'Real Estate':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'RE ' . implode(' ', str_split($code, 4));
                break;
            
            case 'Vendor':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'VE ' . implode(' ', str_split($code, 4));
                break;

            case 'Agent':
                $code = 'OG' . strtoupper(substr($user->profile->first_name, 0, 6));
                $code = 'AG ' . implode(' ', str_split($code, 4));
                break;

            case 'Customer':
                $length = 9;
                $code = sprintf("CU %0". $length . "d", $user->id);
                $code = preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1 ', $code);
                break;
        }
        if (User::where('og_code', $code)->exists()) {
            $this->generateOGCode($user);
        }
        return $code;
    }


}
