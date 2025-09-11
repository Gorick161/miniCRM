<?php
namespace App\Http\Controllers;

use App\Models\{Contact, Company};
use App\Http\Requests\{StoreContactRequest, UpdateContactRequest};
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request) {
        $q = Contact::query()->with('company');

        if ($s = $request->get('s')) {
            $q->where(function($qq) use ($s) {
                $qq->where('first_name','like',"%{$s}%")
                   ->orWhere('last_name','like',"%{$s}%")
                   ->orWhere('email','like',"%{$s}%")
                   ->orWhere('phone','like',"%{$s}%");
            });
        }

        $contacts = $q->orderBy('last_name')->paginate(15)->withQueryString();
        return view('contacts.index', compact('contacts'));
    }

    public function create() {
        $companies = Company::orderBy('name')->get(['id','name']);
        return view('contacts.create', compact('companies'));
    }

    public function store(StoreContactRequest $request) {
        $contact = Contact::create($request->validated());
        return redirect()->route('contacts.show', $contact)->with('status','Contact erstellt.');
    }

    public function show(Contact $contact) {
        $contact->load(['company','activities','tasks']);
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact) {
        $companies = Company::orderBy('name')->get(['id','name']);
        return view('contacts.edit', compact('contact','companies'));
    }

    public function update(UpdateContactRequest $request, Contact $contact) {
        $contact->update($request->validated());
        return redirect()->route('contacts.show', $contact)->with('status','Contact aktualisiert.');
    }

    public function destroy(Contact $contact) {
        $contact->delete();
        return redirect()->route('contacts.index')->with('status','Contact gelöscht.');
    }
}