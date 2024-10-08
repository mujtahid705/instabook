<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Contracts\Service\Attribute\Required;

class ListingController extends Controller
{
    // Show All Listings
    public function index() {
        return view('listings.index', [
            "listings" => Listing::latest()->filter(request(["tag", "search"]))->paginate(6)
        ]);
    }

    // Show Single Listing
    public function show(Listing $listing) {
        return view("listings.show", [
            "listing" => $listing
        ]);
    }

    // Create Listings
    public function create() {
        return view("listings.create");
    }

    // Store Listings
    public function store(Request $request) {
        $formFields = $request->validate([
            "title" => "required",
            "company" => ["required", Rule::unique("listings", "company")],
            "location" => "required",
            "website" => "required",
            "email" => ["required", "email"],
            "tags" => "required",
            "description" => "required",
        ]);

        if ($request->hasFile("logo")) {
            $formFields["logo"] = $request->file("logo")->store("logos", "public");
        }

        Listing::create($formFields);

        return redirect("/")->with("message", "Listing created successfully!");
    }

    // Show Edit Form
    public function edit(Listing $listing) {
        return view("listings.edit", [
            "listing" => $listing
        ]);
    } 

    // Update Listing
    public function update(Request $request, Listing $listing) {
        $formFields = $request->validate([
            "title" => "required",
            "company" => "required",
            "location" => "required",
            "website" => "required",
            "email" => ["required", "email"],
            "tags" => "required",
            "description" => "required",
        ]);

        if ($request->hasFile("logo")) {
            $formFields["logo"] = $request->logo; 
        }

        $listing->update($formFields);

        return back()->with("message", "Listing updated successfully!");
    }

    // Delete Listing
    public function destroy(Listing $listing) {
        $listing->delete();
        return redirect("/")->with("message", "Listing deleted successfully!");
    }
}
