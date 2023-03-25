<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSchoolRequest;
use App\Http\Requests\EditSchoolRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $validated = $request->customValidated();

        DB::beginTransaction();

        try {
            $school_data = Arr::only($validated, ['name', 'address', 'logo']);

            $school = $this->schoolRepository->createSchool($school_data);

            $pcp_data = array_merge([
                'school_id' => $school['id'],
                'is_pcp' => 1,
                'password' => bcrypt('12345678')
            ], Arr::only($validated['pcp'], ['firstname', 'lastname', 'phone_number', 'email', 'profile_pic']));

            $school['admins']['pcp'] = $this->schoolRepository->createSchoolAdmin($pcp_data);

            $scp_data = array_merge([
                'school_id' => $school['id'],
                'password' => bcrypt('12345678'),
            ], Arr::only($validated['pcp'], ['firstname', 'lastname', 'phone_number', 'email', 'profile_pic']));

            $school['admins']['scp'] = $this->schoolRepository->createSchoolAdmin($scp_data);

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

    public function updateSchool(EditSchoolRequest $request, $school_id)
    {
        $validated = $request->customValidated();

        DB::beginTransaction();

        try {
            $school_data = Arr::only($validated, ['name', 'address', 'logo']);

            $school = $this->schoolRepository->editSchool($school_id, $school_data);

            if (Arr::has($validated, 'pcp')) {
                $this->schoolRepository->editSchoolAdmin(
                    Arr::get($validated, 'pcp.id'),
                    Arr::only($validated['pcp'], ['firstname', 'lastname', 'phone_number', 'email', 'profile_pic'])
                );
            }

            if(Arr::has($validated, 'scp'))
            {
                $this->schoolRepository->editSchoolAdmin(
                    Arr::get($validated, 'scp.id'),
                    Arr::only($validated['scp'], ['firstname', 'lastname', 'phone_number', 'email', 'profile_pic'])
                );
            }

            DB::commit();

            return response([
                'success' => true,
                'message' => "$school[name] information updated!",
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
