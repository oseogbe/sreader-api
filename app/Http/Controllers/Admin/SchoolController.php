<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSchoolRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SchoolController extends Controller
{
    private AdminRepositoryInterface $adminRepository;
    private SchoolRepositoryInterface $schoolRepository;

    public function __construct(AdminRepositoryInterface $adminRepository, SchoolRepositoryInterface $schoolRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->schoolRepository = $schoolRepository;
    }

    public function allSchools()
    {
        // return response([
        //     'success' => true,
        //     'data' => $this->adminRepository->getSchools()
        // ]);

        return $this->adminRepository->getSchools();
    }

    public function addSchool(CreateSchoolRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $logo = storeFileOnFirebase("schools/$validated[name]/logo", $validated['logo']);

            $school = $this->schoolRepository->createSchool(array_merge($request->only('name', 'address'), ['logo' => $logo]));

            $pcp_data = $request->only('pcp.phone_number', 'pcp.email');
            $scp_data = $request->only('scp.phone_number', 'scp.email');

            $pcp_profile_pic = storeFileOnFirebase("schools/$validated[name]/admins/profile_pic", $validated['pcp']['profile_pic']);
            $scp_profile_pic = storeFileOnFirebase("schools/$validated[name]/admins/profile_pic", $validated['scp']['profile_pic']);

            $pcp_name = $request->input('pcp.name');
            $pcp_name_words = str_word_count($pcp_name, 1);
            $pcp_firstname = $pcp_name_words[0];
            $pcp_lastname = $pcp_name_words[count($pcp_name_words) - 1];

            $scp_name = $request->input('scp.name');
            $scp_name_words = str_word_count($scp_name, 1);
            $scp_firstname = $scp_name_words[0];
            $scp_lastname = $scp_name_words[count($scp_name_words) - 1];

            $school_admins = [
                $pcp = array_merge($pcp_data['pcp'],
                            [
                                'school_id' => $school['id'],
                                'is_pcp' => 1,
                                'firstname' => $pcp_firstname,
                                'lastname' => $pcp_lastname,
                                'profile_pic' => $pcp_profile_pic,
                                'password' => bcrypt('12345678')
                            ]),
                $scp = array_merge($scp_data['scp'],
                            [
                                'school_id' => $school['id'],
                                'firstname' => $scp_firstname,
                                'lastname' => $scp_lastname,
                                'profile_pic' => $scp_profile_pic,
                                'password' => bcrypt('12345678')
                            ]),
            ];

            $school['admins'] = $this->schoolRepository->createSchoolAdmins($school_admins);

            DB::commit();

            return response([
                'success' => true,
                'message' => "$school[name] added successfully!",
                'data' => $school
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // create school admin
    // set pcp to true for first one created
}
