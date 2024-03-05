<?php

namespace App\Traits;

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



}
