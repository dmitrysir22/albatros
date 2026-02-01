<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all pages grouped by their section (General, Legal, etc.)
        // This makes the list look organized like the requirements.
        $pages = CmsPage::all()->groupBy('section');
        
        return view('admin.cms.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cms.form');
    }

    
/**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = CmsPage::findOrFail($id);
        return view('admin.cms.form', compact('page'));
    }


 /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation with image and layout support
        $validated = $request->validate([
            'title'         => 'required|max:255',
            'slug'          => 'required|unique:cms_pages,slug|max:255',
            'section'       => 'required',
            'layout'        => 'required|in:full_width,with_sidebar,two_columns',
            'content'       => 'nullable',
            'banner_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $data = $request->all();

        // Handle Banner Image Upload
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('cms', 'public');
            $data['banner_image'] = $path;
        }

        CmsPage::create($data);

        return redirect()->route('admin.cms.index')
            ->with('success', 'CMS Page created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);

        $validated = $request->validate([
            'title'         => 'required|max:255',
            'slug'          => 'required|max:255|unique:cms_pages,slug,' . $id,
            'section'       => 'required',
            'layout'        => 'required|in:full_width,with_sidebar,two_columns',
            'content'       => 'nullable',
            'banner_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle Banner Image Update
        if ($request->hasFile('banner_image')) {
            // Delete old image if it exists
            if ($page->banner_image && \Storage::disk('public')->exists($page->banner_image)) {
                \Storage::disk('public')->delete($page->banner_image);
            }
            
            // Store new image
            $path = $request->file('banner_image')->store('cms', 'public');
            $data['banner_image'] = $path;
        }

        $page->update($data);

        return redirect()->route('admin.cms.index')
            ->with('success', 'CMS Page updated successfully.');
    }
	
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $page = CmsPage::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.cms.index')
            ->with('success', 'Page deleted successfully.');
    }
}