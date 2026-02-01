 @extends('layouts.admin')

@section('title', 'CMS Pages')

@section('content')
<style>
    /* Override the template's sidebar .nav styles 
       to restore standard Bootstrap horizontal tabs behavior 
    */
    .main .box-content .nav-pills {
        max-width: 100% !important;
        width: auto !important;
        display: flex !important; /* Restore flexbox */
        flex-direction: row !important; /* Force horizontal */
        background-color: transparent !important;
        padding: 0 !important;
        position: static !important;
    }

    .main .box-content .nav-pills .nav-item {
        width: auto !important;
        margin-bottom: 0 !important;
    }

    .main .box-content .nav-pills .nav-link {
        padding: 10px 20px !important;
        border-radius: 4px 4px 0 0 !important;
    }
</style>


<div class="row">
    <div class="col-lg-12">
        
        <div class="section-box">
            <div class="container">
                <div class="panel-white mb-30">
                    <div class="box-padding">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-20">Static Content Management</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <a class="btn btn-brand-1 hover-up" href="{{ route('admin.cms.create') }}">
                                    + Create New Page
                                </a>
                            </div>
                        </div>

                        @if($pages->isNotEmpty())
                            <ul class="nav nav-pills mt-20" id="cmsTabs" role="tablist">
                                @foreach($pages as $section => $sectionPages)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                                id="tab-{{ Str::slug($section) }}" 
                                                data-bs-toggle="tab" 
                                                data-bs-target="#content-{{ Str::slug($section) }}" 
                                                type="button" 
                                                role="tab" 
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ $section }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mt-30" id="cmsTabsContent">
                                @foreach($pages as $section => $sectionPages)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="content-{{ Str::slug($section) }}" 
                                         role="tabpanel" 
                                         aria-labelledby="tab-{{ Str::slug($section) }}">
                                        
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Page Title</th>
                                                        <th>Slug (URL)</th>
                                                        <th>Status</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sectionPages as $page)
                                                        <tr>
                                                            <td>{{ $page->id }}</td>
                                                            <td><strong>{{ $page->title }}</strong></td>
                                                            <td><code>/{{ $page->slug }}</code></td>
                                                            <td>
                                                                @if($page->is_active)
                                                                    <span class="badge bg-success">Active</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Draft</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('admin.cms.edit', $page->id) }}" class="btn btn-sm btn-light border">
                                                                    Edit
                                                                </a>
                                                                
<form action="{{ route('admin.cms.destroy', $page->id) }}" method="POST" id="delete-form-{{ $page->id }}" style="display:none;">
    @csrf
    @method('DELETE')
</form>
<button class="btn btn-sm btn-light text-danger border" onclick="if(confirm('Are you sure?')) document.getElementById('delete-form-{{ $page->id }}').submit();">
    Delete
</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center mt-50 mb-50">
                                <img src="{{ asset('backend/imgs/page/dashboard/tasks.svg') }}" alt="No Content" style="width: 60px; opacity: 0.5;">
                                <p class="text-muted mt-20">No pages found. Click "Create New Page" to start.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection