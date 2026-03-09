<div class="shimmer-wrapper">
    <div class="card border-0 shadow-sm overflow-hidden bg-white rounded-3">
        <!-- Dashboard-style Header Bar -->
        <div class="card-header bg-white border-0 py-3">
            <div class="row g-3 align-items-center">
                <!-- Search Shimmer -->
                <div class="col-md-3">
                    <div class="shimmer-box rounded" style="width: 100%; height: 38px;"></div>
                </div>
                <!-- Filter Shimmer 1 -->
                <div class="col-md-2">
                    <div class="shimmer-box rounded" style="width: 100%; height: 38px;"></div>
                </div>
                <!-- Filter Shimmer 2 -->
                <div class="col-md-2">
                    <div class="shimmer-box rounded" style="width: 100%; height: 38px;"></div>
                </div>
                <!-- Right Side Actions -->
                <div class="col-md-auto ms-auto">
                    <div class="shimmer-box rounded" style="width: 120px; height: 38px;"></div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" width="40"><div class="shimmer-box m-auto" style="width: 18px; height: 18px;"></div></th>
                        @for($i=0; $i<4; $i++)
                            <th class="py-3"><div class="shimmer-box" style="width: 100px; height: 14px;"></div></th>
                        @endfor
                        <th class="text-end pe-4"><div class="shimmer-box ms-auto" style="width: 60px; height: 14px;"></div></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table Body with a professional centered spinner instead of many bars -->
                    <tr>
                        <td colspan="6" class="py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                <div class="spinner-border text-primary opacity-50 mb-3" style="width: 2.5rem; height: 2.5rem;" role="status text-muted">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="shimmer-box rounded" style="width: 120px; height: 12px; opacity: 0.4;"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Footer Shimmer -->
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between">
            <div class="shimmer-box rounded" style="width: 150px; height: 15px;"></div>
            <div class="shimmer-box rounded" style="width: 100px; height: 15px;"></div>
        </div>
    </div>
</div>

<style>
    .shimmer-wrapper {
        opacity: 0.8;
    }
    .shimmer-box {
        background: #f6f7f8;
        background-image: linear-gradient(90deg, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
        background-repeat: no-repeat;
        background-size: 200% 100%;
        display: block;
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
