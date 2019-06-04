<?php

namespace App\Http\Controllers\API\Admin;

use App\{Company, Driver, Http\Controllers\Controller};
use Exception;
use Illuminate\{Http\Request, Http\Response, Validation\ValidationException};

class CompanyController extends Controller
{
    public function __construct ()
    {
        $this->middleware('check_role:master_admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index (Request $request)
    {
        $companies = Company::where('name', '<>', Company::GENERAL_COMPANY)
            ->when($request->search, static function ($query) use ($request) {
                return $query->where('name', 'LIKE', "%$request->search%");
            })->get();

        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:companies,name'
        ]);

        Company::create($request->all());

        return response()->json(['message' => 'success']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Company $company
     * @return Response
     * @throws ValidationException
     */
    public function update (Request $request, Company $company)
    {
        $this->validate($request, [
            'name' => "required|string|max:255|unique:companies,name,{$company->id},id"
        ]);

        $company->update($request->all());

        return response()->json(['message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return Response
     * @throws Exception
     */
    public function destroy (Company $company)
    {
        if ($company->name == Company::GENERAL_COMPANY) {
            return response()->json(['message' => 'delete_general_company_is_impossible'], 403);
        }

        $users = $company->users();

        Driver::whereIn('user_id', $users->pluck('id'))->delete();

        $users->delete();

        $company->delete();

        return response()->json(['message' => 'success']);
    }
}
