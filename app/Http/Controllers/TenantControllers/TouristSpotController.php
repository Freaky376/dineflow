<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\TouristSpot;
use Illuminate\Support\Facades\Log;

class TouristSpotController extends Controller
{
    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string',
        'location' => 'required|string',
        'description' => 'nullable|string',
        'category' => 'nullable|string',
        'opening_hours' => 'nullable|string',
        'entry_fee' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $data['image'] = $this->storeImage($request);
    }

    TouristSpot::create($data);

    return redirect()->back()->with('success', 'Tourist spot added successfully!');
}


    public function index(Request $request)
    {
        try {
            // Initialize tenancy to get the current tenant
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city; 

            // Get search input
            $search = $request->input('search');

            // Fetch tourist spots based on search criteria
            $touristSpots = TouristSpot::when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orWhere('opening_hours', 'LIKE', "%{$search}%")
                    ->orWhere('entry_fee', 'LIKE', "%{$search}%");
            })->get();

            // Pass the tenant name and tourist spots to the view
            return view('tenantviews.tenantdash.tenanttourlist', compact('touristSpots', 'tenantName'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Unable to load tourist spots.');
        }
    }

    public function edit(TouristSpot $touristSpot)
    {
        return view('tenantviews.tenantdash.edittouristspot', compact('touristSpot'));
    }

    public function update(Request $request, TouristSpot $touristSpot)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'opening_hours' => 'nullable|string',
            'entry_fee' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request);
        }

        $touristSpot->update($data);

        return redirect()->route('tenantlist')->with('success', 'Tourist spot updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $touristSpot = TouristSpot::findOrFail($id);
            $touristSpot->delete();

            return redirect()->back()->with('success', 'Tourist spot deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the tourist spot.');
        }
    }

    public function show($id)
    {
        $touristSpot = TouristSpot::findOrFail($id);
        return response()->json($touristSpot);
    }

    private function storeImage(Request $request): string
    {
        try {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('storage/visitor/image/' . $filename);

            $image->move(public_path('storage/visitor/image'), $filename);

            return $filename;
        } catch (\Exception $e) {
            // Log the error
            Log::error('Image upload failed: ' . $e->getMessage());

            // Return null or throw an exception depending on how you want to handle errors
            return null;
        }
    }
}