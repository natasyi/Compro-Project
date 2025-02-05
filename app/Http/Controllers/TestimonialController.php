<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestimonialRequest;
use App\Models\ProjectClient;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateTestimonialRequest;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::orderByDesc('id')->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = ProjectClient::orderByDesc('id')->get();
        return view('admin.testimonials.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
        DB::transaction(function() use ($request){
            $validated = $request->validated();

            if($request->hasFile('thumbnail')){
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validated['thumbnail'] =  $thumbnailPath;
            }

            $newTestimonial = Testimonial::create($validated);
        });

        return redirect()->route('admin.testimonials.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        $clients = ProjectClient::orderByDesc('id')->get();
        return view('admin.testimonials.edit', compact('testimonial', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        DB::transaction(function() use ($request, $testimonial){
            $validated = $request->validate([
                'project_client_id' => 'required|exists:project_clients,id',
                'message' => 'required|string|max:500',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Update fields
            $testimonial->project_client_id = $request->project_client_id;
            $testimonial->message = $request->message;

            // Handle file upload (only if new file uploaded)
            if ($request->hasFile('thumbnail')) {
                // Delete the old thumbnail file if exists
                if ($testimonial->thumbnail) {
                    Storage::disk('public')->delete($testimonial->thumbnail);
                }

                // Store the new thumbnail and update the path
                $testimonial->thumbnail = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            // Save the changes
            $testimonial->save();
        });

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        DB::transaction(function() use ($testimonial){
            $testimonial->delete();
        });
        return redirect()->route('admin.testimonials.index');
    }
}
