<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
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

    public function show(Contact $contact)
    {
        $contact->load(['company','activities','tasks']);
        return view('contacts.show', compact('contact'));
    }
}