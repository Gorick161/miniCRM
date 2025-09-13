<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Company;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // GET /contacts
    public function index(Request $request)
    {
        // --- simple filters (q, sort, perPage) ---
        $q       = trim($request->get('q', ''));
        $sort    = $request->get('sort', 'name');    // name|company|email|recent
        $perPage = (int) $request->get('perPage', 12);
        $perPage = in_array($perPage, [12, 24, 50]) ? $perPage : 12;

        $contacts = Contact::query()
            ->with(['company:id,name']) // eager-load company to avoid N+1
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($sort === 'company', fn($qq) => $qq->join('companies', 'contacts.company_id', '=', 'companies.id')->orderBy('companies.name')->select('contacts.*'))
            ->when($sort === 'email',   fn($qq) => $qq->orderBy('email'))
            ->when($sort === 'recent',  fn($qq) => $qq->latest())
            ->when($sort === 'name',    fn($qq) => $qq->orderBy('last_name')->orderBy('first_name'))
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Contact::count(),
        ];

        // companies for "New Contact" modal select
        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('contacts.index', compact('contacts', 'stats', 'q', 'sort', 'perPage', 'companies'));
    }

    // POST /contacts
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['nullable', 'email', 'max:190'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'title'      => ['nullable', 'string', 'max:120'],
        ]);

        Contact::create($data);

        return back()->with('status', 'Contact created');
    }

    // GET /contacts/{contact}
    public function show(Contact $contact)
    {
        $contact->load('company:id,name');
        return view('contacts.show', compact('contact'));
    }
}
