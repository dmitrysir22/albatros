<div class="sidebar-with-border">
    <h6 class="color-brand-1 mb-20">Related Information</h6>
    <ul class="nav-sidebar">
        {{-- Here we can fetch other pages from the same section --}}
        @php
            $relatedPages = \App\Models\CmsPage::where('section', $page->section)
                ->where('is_active', true)
                ->where('id', '!=', $page->id)
                ->get();
        @endphp

        @forelse($relatedPages as $relPage)
            <li class="mb-10">
                <a class="font-sm color-grey-500" href="{{ route('pages.show', $relPage->slug) }}">
                    {{ $relPage->title }}
                </a>
            </li>
        @empty
            <li class="font-sm color-grey-400">No other pages in this section.</li>
        @endforelse
    </ul>

    <div class="box-newsletter-sidebar mt-40">
        <h6 class="color-brand-1 mb-15">Need Help?</h6>
        <p class="font-xs color-grey-500 mb-20">Speak to our recruitment consultants today.</p>
        <a href="/page/contact-us" class="btn btn-brand-1-full hover-up">Contact Us</a>
    </div>
</div>

<style>
    .nav-sidebar { list-style: none; padding: 0; }
    .nav-sidebar li a { text-decoration: none; transition: 0.3s; }
    .nav-sidebar li a:hover { color: #0045ff !important; padding-left: 5px; }
    .sidebar-with-border {
        padding: 25px;
        border: 1px solid #E0E6F7;
        border-radius: 8px;
    }
</style>