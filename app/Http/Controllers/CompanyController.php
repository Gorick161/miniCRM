<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Modern index with search, sorting and pagination.
     */
    public function index(Request $request)
    {
        // Basic filters from query string
        $q       = trim($request->get('q', ''));
        $sort    = $request->get('sort', 'name');           // name|created|deals|contacts
        $dir     = $request->get('dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPage = (int) $request->get('per', 12);
        $perPage = in_array($perPage, [12, 24, 48]) ? $perPage : 12;

        $query = Company::query()
            ->withCount(['contacts', 'deals']);

        // Free text search on common fields
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('website', 'like', "%{$q}%")
                    ->orWhere('industry', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            });
        }

        // Sorting map
        $sortMap = [
            'name'     => 'name',
            'created'  => 'created_at',
            'deals'    => 'deals_count',
            'contacts' => 'contacts_count',
        ];
        $query->orderBy($sortMap[$sort] ?? 'name', $dir);

        $companies = $query->paginate($perPage)->withQueryString();

        return view('companies.index', compact('companies', 'q', 'sort', 'dir', 'perPage'));
    }

    /**
     * Create a new company (used by the modal in index).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // Keep it pragmatic; extend as you like
            'name'     => ['required', 'string', 'max:255'],
            'website'  => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'city'     => ['nullable', 'string', 'max:255'],
        ]);

        Company::create($data);

        return back()->with('status', 'Company created');
    }

    /**
     * Lightweight detail page.
     */
    public function show(Company $company)
    {
        $company->loadCount(['contacts', 'deals'])
            ->load([
                'contacts' => fn($q) => $q->latest()->limit(8),
                'deals'    => fn($q) => $q->latest()->limit(8)
            ]);

        return view('companies.show', compact('company'));
    }
}
