<?php 
namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\{StoreCompanyRequest, UpdateCompanyRequest};
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request) {
        $q = Company::query();

        if ($s = $request->get('s')) {
            $q->where(function($qq) use ($s) {
                $qq->where('name','like',"%{$s}%")
                   ->orWhere('domain','like',"%{$s}%")
                   ->orWhere('phone','like',"%{$s}%");
            });
        }

        $companies = $q->withCount(['contacts','deals'])
                       ->orderBy('name')
                       ->paginate(12)
                       ->withQueryString();
                       
        return view('companies.index', compact('companies'));
    }

    public function create() {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request) {
        $company = Company::create($request->validated() + ['owner_id' => auth()->id()]);
        return redirect()->route('companies.show', $company)->with('status','Company erstellt.');
    }

    public function show(Company $company) {
        $company->load(['contacts','deals.stage']);
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company) {
        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company) {
        $company->update($request->validated());
        return redirect()->route('companies.show', $company)->with('status','Company aktualisiert.');
    }

    public function destroy(Company $company) {
        $company->delete();
        return redirect()->route('companies.index')->with('status','Company gelöscht.');
    }
}