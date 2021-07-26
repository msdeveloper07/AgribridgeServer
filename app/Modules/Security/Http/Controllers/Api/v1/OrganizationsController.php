<?php

namespace App\Modules\Security\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Modules\Security\Models\Organizations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class OrganizationsController extends Controller
{
    public function insert(Request $request)
    {
        //Validate data
        $data = $request->except('org_taluka', 'org_village', 'org_pincode');
        $validator = Validator::make($request->all(), [
            'org_name' => 'required|string',
            'parent_org_id' => 'required',
            'org_type' => 'required|string'
        ], [
            'org_name.required' => "The Organization Name field is required.",
            'parent_org_id.required' => "The Organization ID field is required.",
            'org_type.required' => "The Organization Type field is required.",
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ], Response::HTTP_OK);
        }
        $data['app_id'] = 1;
        $path = 'public/images/organization/';

        if ($request->log_file) {
            // file upload
            $fileName = (string) Str::upper(Str::random(rand(1, 10))) . rand(1, 9999);
            $newFileName = base64File($fileName, $request->log_file, $path);
            $log_file_ = $newFileName;
            $data['org_logo_url'] = "storage/images/organization/" . $log_file_;
        }
        unset($data['log_file']);


        $create = Organizations::create($data);
        return response()->json([
            'success' => true,
            'message' => "Data Save.",
            'data' => $create
        ], Response::HTTP_OK);
    }

    public function get_organizition_list()
    {
        $get_all = Organizations::get();
        return response()->json([
            'success' => true,
            'message' => "Data get.",
            'data' => $get_all
        ], Response::HTTP_OK);
    }
}
