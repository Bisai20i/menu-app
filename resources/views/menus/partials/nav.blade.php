<div class="sticky-top bg-white border-bottom py-3 shadow-sm" style="z-index:100">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-lg-8 col-12">
                <div class="d-flex flex-wrap cat-nav">
                    <button class="btn active filter-btn" data-category="all">All</button>
                    @foreach($categories as $category)
                        <button class="btn filter-btn" data-category="cat-{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="input-group input-group-sm border rounded-2 px-2 py-1 bg-light">
                    <span class="input-group-text bg-transparent border-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="menuSearch" class="form-control bg-transparent border-0 shadow-none" placeholder="Search for dishes..." style="font-size: 0.85rem;">
                </div>
            </div>
        </div>
    </div>
</div>
