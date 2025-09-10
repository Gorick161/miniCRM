<?php 
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $q = Company::query();

        if ($s = $request->get('s')) {
            $q->where('name', 'like', "%{$s}%")
              ->orWhere('domain', 'like', "%{$s}%")
              ->orWhere('phone', 'like', "%{$s}%");
        }

        $companies = $q->withCount(['contacts','deals'])
                       ->orderBy('name')
                       ->paginate(12)
                       ->withQueryString();

        return view('companies.index', compact('companies'));
    }

    public function show(Company $company)
    {
        $company->load(['contacts','deals']);
        return view('companies.show', compact('company'));
    }
}