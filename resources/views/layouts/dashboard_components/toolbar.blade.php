<div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            {{-- Page Title --}}
            <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                {{-- Title --}}
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                    {{ $activeMenu['menu_name'] ?? 'Default Title' }}
                </h1>

                {{-- Breadcrumb --}}
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>

                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item">
                            @if($breadcrumb['path'])
                                <a href="{{ url($breadcrumb['path']) }}" class="text-muted text-hover-primary">
                                    {{ $breadcrumb['menu_name'] }}
                                </a>
                            @else
                                <span class="text-muted">{{ $breadcrumb['menu_name'] }}</span>
                            @endif
                        </li>
                        @if (!$loop->last)
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                        @endif
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>
