{{-- Activity Drawer --}}
<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '900px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
    <div class="card shadow-none border-0 rounded-0">
        {{-- Header --}}
        <div class="card-header" id="kt_activities_header">
            <h3 class="card-title fw-bold text-dark">Activity Logs</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_activities_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body position-relative" id="kt_activities_body">
            <div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body" data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="5px">
                {{-- Timiline Items --}}
                <div class="timeline">
                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px me-4">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-message-text-2 fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mb-10 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold mb-2">There are 2 new tasks for you in “AirPlus Mobile App” project:</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Added at 4:23 PM by</div>
                                    <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="Nina Nilson">
                                        <img src="{{ asset('assets/dashboard/media/avatars/300-14.jpg') }}" alt="img"/>
                                    </div>
                                </div>
                            </div>

                            {{-- Timeline Details --}}
                            <div class="overflow-auto pb-5">
                                <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">
                                    <a href="#" class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">Meeting with customer</a>
                                    <div class="min-w-175px pe-2">
                                        <span class="badge badge-light text-muted">Application Design</span>
                                    </div>
                                    <div class="symbol-group symbol-hover flex-nowrap flex-grow-1 min-w-100px pe-2">
                                        <div class="symbol symbol-circle symbol-25px">
                                            <img src="{{ asset('assets/dashboard/media/avatars/300-2.jpg') }}" alt="img"/>
                                        </div>
                                        <div class="symbol symbol-circle symbol-25px">
                                            <img src="{{ asset('assets/dashboard/media/avatars/300-14.jpg') }}" alt="img"/>
                                        </div>
                                        <div class="symbol symbol-circle symbol-25px">
                                            <div class="symbol-label fs-8 fw-semibold bg-primary text-inverse-primary">A</div>
                                        </div>
                                    </div>
                                    <div class="min-w-125px pe-2">
                                        <span class="badge badge-light-primary">In Progress</span>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                </div>
                                <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-0">
                                    <a href="#" class="fs-5 text-dark text-hover-primary fw-semibold w-375px min-w-200px">Project Delivery Preparation</a>
                                    <div class="min-w-175px">
                                        <span class="badge badge-light text-muted">CRM System Development</span>
                                    </div>
                                    <div class="symbol-group symbol-hover flex-nowrap flex-grow-1 min-w-100px">
                                        <div class="symbol symbol-circle symbol-25px">
                                            <img src="{{ asset('assets/dashboard/media/avatars/300-20.jpg') }}" alt="img"/>
                                        </div>
                                        <div class="symbol symbol-circle symbol-25px">
                                            <div class="symbol-label fs-8 fw-semibold bg-success text-inverse-primary">B</div>
                                        </div>
                                    </div>
                                    <div class="min-w-125px">
                                        <span class="badge badge-light-success">Completed</span>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-flag fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mb-10 mt-n2">
                            <div class="overflow-auto pe-3">
                                <div class="fs-5 fw-semibold mb-2">Invitation for crafting engaging designs that speak human workshop</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Sent at 4:23 PM by</div>
                                    <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="Alan Nilson">
                                        <img src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}" alt="img"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-disconnect fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mb-10 mt-n1">
                            <div class="mb-5 pe-3">
                                <a href="#" class="fs-5 fw-semibold text-gray-800 text-hover-primary mb-2">3 New Incoming Project Files:</a>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Sent at 10:30 PM by</div>
                                    <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="Jan Hummer">
                                        <img src="{{ asset('assets/dashboard/media/avatars/300-23.jpg') }}" alt="img"/>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-auto pb-5">
                                <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-5">
                                    <div class="d-flex flex-aligns-center pe-10 pe-lg-20">
                                        <img alt="" class="w-30px me-3" src="{{ asset('assets/dashboard/media/svg/files/pdf.svg') }}"/>
                                        <div class="ms-1 fw-semibold">
                                            <a href="#" class="fs-6 text-hover-primary fw-bold">Finance KPI App Guidelines</a>
                                            <div class="text-gray-400">1.9mb</div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-aligns-center pe-10 pe-lg-20">
                                        <img alt="#" class="w-30px me-3" src="{{ asset('assets/dashboard/media/svg/files/doc.svg') }}"/>
                                        <div class="ms-1 fw-semibold">
                                            <a href="#" class="fs-6 text-hover-primary fw-bold">Client UAT Testing Results</a>
                                            <div class="text-gray-400">18kb</div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-aligns-center">
                                        <img alt="#" class="w-30px me-3" src="{{ asset('assets/dashboard/media/svg/files/css.svg') }}"/>
                                        <div class="ms-1 fw-semibold">
                                            <a href="#" class="fs-6 text-hover-primary fw-bold">Finance Reports</a>
                                            <div class="text-gray-400">20mb</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-abstract-26 fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mb-10 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold mb-2">Task
                                <a href="#" class="text-primary fw-bold me-1">#45890</a>merged with
                                <a href="#" class="text-primary fw-bold me-1">#45890</a>in “Ads Pro Admin Dashboard project:</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Initiated at 4:23 PM by</div>
                                    <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="Nina Nilson">
                                        <img src="{{ asset('assets/dashboard/media/avatars/300-14.jpg') }}" alt="img"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-pencil fs-2 text-gray-500"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-10 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold mb-2">3 new application design concepts added:</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Created at 4:23 PM by</div>
                                    <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="Marcus Dotson">
                                        <img src="{{ asset('assets/dashboard/media/avatars/300-2.jpg') }}" alt="img"/>
                                    </div>
                                </div>
                            </div>

                            {{-- Timeline Details --}}
                            <div class="overflow-auto pb-5">
                                <div class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-700px p-7">
                                    <div class="overlay me-10">
                                        <div class="overlay-wrapper">
                                            <img alt="img" class="rounded w-150px" src="{{ asset('assets/dashboard/media/stock/600x400/img-29.jpg') }}"/>
                                        </div>
                                        <div class="overlay-layer bg-dark bg-opacity-10 rounded">
                                            <a href="#" class="btn btn-sm btn-primary btn-shadow">Explore</a>
                                        </div>
                                    </div>
                                    <div class="overlay me-10">
                                        <div class="overlay-wrapper">
                                            <img alt="img" class="rounded w-150px" src="{{ asset('assets/dashboard/media/stock/600x400/img-31.jpg') }}"/>
                                        </div>
                                        <div class="overlay-layer bg-dark bg-opacity-10 rounded">
                                            <a href="#" class="btn btn-sm btn-primary btn-shadow">Explore</a>
                                        </div>
                                    </div>
                                    <div class="overlay">
                                        <div class="overlay-wrapper">
                                            <img alt="img" class="rounded w-150px" src="{{ asset('assets/dashboard/media/stock/600x400/img-40.jpg') }}"/>
                                        </div>
                                        <div class="overlay-layer bg-dark bg-opacity-10 rounded">
                                            <a href="#" class="btn btn-sm btn-primary btn-shadow">Explore</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-sms fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mb-10 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold mb-2">New case
                                <a href="#" class="text-primary fw-bold me-1">#67890</a>is assigned to you in Multi-platform Database Design project</div>
                                <div class="overflow-auto pb-5">
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">Added at 4:23 PM by</div>
                                        <a href="#" class="text-primary fw-bold me-1">Alice Tan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-pencil fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mb-10 mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold mb-2">You have received a new order:</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Placed at 5:05 AM by</div>
                                    <div class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top" title="Robert Rich">
                                        <img src="{{ asset('assets/dashboard/media/avatars/300-4.jpg') }}" alt="img"/>
                                    </div>
                                </div>
                            </div>

                            {{-- Timeline Details --}}
                            <div class="overflow-auto pb-5">
                                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed min-w-lg-600px flex-shrink-0 p-6">
                                    <i class="ki-outline ki-devices-2 fs-2tx text-primary me-4"></i>
                                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                        <div class="mb-3 mb-md-0 fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">Database Backup Process Completed!</h4>
                                            <div class="fs-6 text-gray-700 pe-7">Login into Admin Dashboard to make sure the data integrity is OK</div>
                                        </div>
                                        <a href="#" class="btn btn-primary px-6 align-self-center text-nowrap">Proceed</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Item --}}
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="ki-outline ki-basket fs-2 text-gray-500"></i>
                            </div>
                        </div>

                        <div class="timeline-content mt-n1">
                            <div class="pe-3 mb-5">
                                <div class="fs-5 fw-semibold mb-2">New order
                                <a href="#" class="text-primary fw-bold me-1">#67890</a>is placed for Workshow Planning & Budget Estimation</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Placed at 4:23 PM by</div>
                                    <a href="#" class="text-primary fw-bold me-1">Jimmy Bold</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="card-footer py-5 text-center" id="kt_activities_footer">
            <a href="#" class="btn btn-bg-body text-primary">View All Activities
            <i class="ki-outline ki-arrow-right fs-3 text-primary"></i></a>
        </div>
    </div>
</div>

{{-- Chat Drawer --}}
<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
    <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
        {{-- Header --}}
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <div class="card-title">
                {{-- User Info --}}
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">Brian Cox</a>
                    <div class="mb-0 lh-1">
                        <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>
                        <span class="fs-7 fw-semibold text-muted">Active</span>
                    </div>
                </div>
            </div>

            {{-- Toolbar --}}
            <div class="card-toolbar">
                <div class="me-0">
                    <button class="btn btn-sm btn-icon btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-outline ki-dots-square fs-2"></i>
                    </button>
                    {{-- Menu --}}
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                        {{-- Heading --}}
                        <div class="menu-item px-3">
                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Contacts</div>
                        </div>

                        {{-- Menu Item --}}
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_users_search">Add Contact</a>
                        </div>

                        {{-- Menu Item --}}
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link flex-stack px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">Invite Contacts
                            <span class="ms-2" data-bs-toggle="tooltip" title="Specify a contact email to send an invitation">
                                <i class="ki-outline ki-information fs-7"></i>
                            </span></a>
                        </div>

                        {{-- Menu Item --}}
                        <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
                            <a href="#" class="menu-link px-3">
                                <span class="menu-title">Groups</span>
                                <span class="menu-arrow"></span>
                            </a>

                            {{-- Sub Menu --}}
                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                {{-- Menu Item --}}
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="tooltip" title="Coming soon">Create Group</a>
                                </div>

                                {{-- Menu Item --}}
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="tooltip" title="Coming soon">Invite Members</a>
                                </div>

                                {{-- Menu Item --}}
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="tooltip" title="Coming soon">Settings</a>
                                </div>

                            </div>
                        </div>

                        {{-- Menu Item --}}
                        <div class="menu-item px-3 my-1">
                            <a href="#" class="menu-link px-3" data-bs-toggle="tooltip" title="Coming soon">Settings</a>
                        </div>
                    </div>
                </div>

                <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
                    <i class="ki-outline ki-cross-square fs-2"></i>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body" id="kt_drawer_chat_messenger_body">
            <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
                {{-- Message (in) --}}
                <div class="d-flex justify-content-start mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-start">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-25.jpg') }}"/>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
                                <span class="text-muted fs-7 mb-1">2 mins</span>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">How likely are you to recommend our company to your friends and family ?</div>
                    </div>
                </div>

                {{-- Message (out) --}}
                <div class="d-flex justify-content-end mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-end">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <span class="text-muted fs-7 mb-1">5 mins</span>
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                            </div>
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}"/>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">Hey there, we’re just writing to let you know that you’ve been subscribed to a repository on GitHub.</div>
                    </div>
                </div>

                {{-- Message (in) --}}
                <div class="d-flex justify-content-start mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-start">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-25.jpg') }}"/>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
                                <span class="text-muted fs-7 mb-1">1 Hour</span>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">Ok, Understood!</div>
                    </div>
                </div>

                {{-- Message (out) --}}
                <div class="d-flex justify-content-end mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-end">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <span class="text-muted fs-7 mb-1">2 Hours</span>
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                            </div>
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}"/>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">You’ll receive notifications for all issues, pull requests!</div>
                    </div>
                </div>

                {{-- Message (in) --}}
                <div class="d-flex justify-content-start mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-start">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-25.jpg') }}"/>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
                                <span class="text-muted fs-7 mb-1">3 Hours</span>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">You can unwatch this repository immediately by clicking here:
                        <a href="https://keenthemes.com">Keenthemes.com</a></div>
                    </div>
                </div>

                {{-- Message (out) --}}
                <div class="d-flex justify-content-end mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-end">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <span class="text-muted fs-7 mb-1">4 Hours</span>
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                            </div>
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}"/>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text">Most purchased Business courses during this sale!</div>
                    </div>
                </div>

                {{-- Message (in) --}}
                <div class="d-flex justify-content-start mb-10">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-start">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-25.jpg') }}"/>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
                                <span class="text-muted fs-7 mb-1">5 Hours</span>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">Company BBQ to celebrate the last quater achievements and goals. Food and drinks provided</div>
                    </div>
                </div>

                {{-- Message (Template for out) --}}
                <div class="d-flex justify-content-end mb-10 d-none" data-kt-element="template-out">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-end">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <span class="text-muted fs-7 mb-1">Just now</span>
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</a>
                            </div>
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-1.jpg') }}"/>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-primary text-dark fw-semibold mw-lg-400px text-end" data-kt-element="message-text"></div>
                    </div>
                </div>

                {{-- Message (Template for in) --}}
                <div class="d-flex justify-content-start mb-10 d-none" data-kt-element="template-in">
                    {{-- Wrapper --}}
                    <div class="d-flex flex-column align-items-start">
                        {{-- User Info --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="symbol symbol-35px symbol-circle">
                                <img alt="Pic" src="{{ asset('assets/dashboard/media/avatars/300-25.jpg') }}"/>
                            </div>
                            <div class="ms-3">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1">Brian Cox</a>
                                <span class="text-muted fs-7 mb-1">Just now</span>
                            </div>
                        </div>

                        {{-- Text --}}
                        <div class="p-5 rounded bg-light-info text-dark fw-semibold mw-lg-400px text-start" data-kt-element="message-text">Right before vacation season we have the next Big Deal for you.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
            {{-- Input --}}
            <textarea class="form-control form-control-flush mb-3" rows="1" data-kt-element="input" placeholder="Type a message"></textarea>
            <div class="d-flex flex-stack">
                {{-- Actions --}}
                <div class="d-flex align-items-center me-2">
                    <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button" data-bs-toggle="tooltip" title="Coming soon">
                        <i class="ki-outline ki-paper-clip fs-3"></i>
                    </button>
                    <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button" data-bs-toggle="tooltip" title="Coming soon">
                        <i class="ki-outline ki-cloud-add fs-3"></i>
                    </button>
                </div>

                {{-- Button --}}
                <button class="btn btn-primary" type="button" data-kt-element="send">Send</button>
            </div>
        </div>
    </div>
</div>

{{-- Shopping Cart Drawer --}}
<div id="kt_shopping_cart" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cart" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_shopping_cart_toggle" data-kt-drawer-close="#kt_drawer_shopping_cart_close">
    <div class="card card-flush w-100 rounded-0">
        {{-- Header --}}
        <div class="card-header">
            <h3 class="card-title text-gray-900 fw-bold">Shopping Cart</h3>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_shopping_cart_close">
                    <i class="ki-outline ki-cross fs-2"></i>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body hover-scroll-overlay-y h-400px pt-5">
            {{-- Item --}}
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">Iblender</a>
                        <span class="text-gray-400 fw-semibold d-block">The best kitchen gadget in 2022</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 350</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">5</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-1.jpg') }}" alt=""/>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator separator-dashed my-6"></div>

            {{-- Item --}}
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">SmartCleaner</a>
                        <span class="text-gray-400 fw-semibold d-block">Smart tool for cooking</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 650</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">4</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-3.jpg') }}" alt=""/>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator separator-dashed my-6"></div>

            <!--begin::Item-->
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">CameraMaxr</a>
                        <span class="text-gray-400 fw-semibold d-block">Professional camera for edge</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 150</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">3</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-8.jpg') }}" alt=""/>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator separator-dashed my-6"></div>

            <!--begin::Item-->
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">$D Printer</a>
                        <span class="text-gray-400 fw-semibold d-block">Manfactoring unique objekts</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 1450</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">7</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-26.jpg') }}" alt=""/>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator separator-dashed my-6"></div>

            <!--begin::Item-->
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">MotionWire</a>
                        <span class="text-gray-400 fw-semibold d-block">Perfect animation tool</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 650</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">7</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-21.jpg') }}" alt=""/>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator separator-dashed my-6"></div>

            <!--begin::Item-->
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">Samsung</a>
                        <span class="text-gray-400 fw-semibold d-block">Profile info,Timeline etc</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 720</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">6</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-34.jpg') }}" alt=""/>
                </div>
            </div>

            {{-- Separator --}}
            <div class="separator separator-dashed my-6"></div>

            <!--begin::Item-->
            <div class="d-flex flex-stack">
                {{-- Wrapper --}}
                <div class="d-flex flex-column me-3">
                    <div class="mb-3">
                        <a href="#" class="text-gray-800 text-hover-primary fs-4 fw-bold">$D Printer</a>
                        <span class="text-gray-400 fw-semibold d-block">Manfactoring unique objekts</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold text-gray-800 fs-5">$ 430</span>
                        <span class="text-muted mx-2">for</span>
                        <span class="fw-bold text-gray-800 fs-5 me-3">8</span>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon-success btn-icon w-25px h-25px me-2">
                            <i class="ki-outline ki-minus fs-4"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-light-success btn-icon w-25px h-25px">
                            <i class="ki-outline ki-plus fs-4"></i>
                        </a>
                    </div>
                </div>
                <div class="symbol symbol-70px symbol-2by3 flex-shrink-0">
                    <img src="{{ asset('assets/dashboard/media/stock/600x400/img-27.jpg') }}" alt=""/>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="card-footer">
            {{-- Item --}}
            <div class="d-flex flex-stack">
                <span class="fw-bold text-gray-600">Total</span>
                <span class="text-gray-800 fw-bolder fs-5">$ 1840.00</span>
            </div>

            {{-- Item --}}
            <div class="d-flex flex-stack">
                <span class="fw-bold text-gray-600">Sub total</span>
                <span class="text-primary fw-bolder fs-5">$ 246.35</span>
            </div>

            {{-- Action --}}
            <div class="d-flex justify-content-end mt-9">
                <a href="#" class="btn btn-primary d-flex justify-content-end">Pleace Order</a>
            </div>
        </div>
    </div>
</div>
