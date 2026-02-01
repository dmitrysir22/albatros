<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    /**
     * Display a specific CMS page by its slug
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        // Find the page or return 404 if not found/inactive
        $page = CmsPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.cms.show', compact('page'));
    }
}