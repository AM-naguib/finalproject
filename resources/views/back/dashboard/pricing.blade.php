@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <div class="dropdown py-3">
            <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                New Task
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.plans.create') }}">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                            clip-rule="evenodd" />
                    </svg>
                    Add Plan
                </a>

            </div>
        </div>
        <h1>All Plans</h1>

    </div>
    <div class="card border-0 shadow mb-5">
        <div class="card-body">
            <div class="row text-gray justify-content-around">
                @if (count($plans) > 0)
                    @foreach ($plans as $plan)
                        @php
                            $planFeatured = explode(',', $plan->features);
                        @endphp

                        <div class="col-12 col-lg-6 col-xl-4">
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header border-gray-100 py-5 px-4">
                                    <div class="d-flex mb-3"><span class="h5 mb-0">{{ $plan->currency }}</span> <span
                                            class="price display-2 mb-0" data-annual="0"
                                            data-monthly="0">{{ $plan->price }}</span> <span
                                            class="h6 fw-normal align-self-end">/month</span></div>
                                    <h4 class="mb-3 text-black">{{ $plan->name }}</h4>
                                    <p class="fw-normal mb-0">{{ $plan->description }}</p>
                                </div>
                                <div class="card-body py-5 px-4">
                                    <div class="d-flex justify-content-between  mb-3 flex-column">
                                        @foreach ($planFeatured as $feature)
                                        <div class="fe py-1">
                                            <svg class="icon icon-sm me-2" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span>{{$feature}}</span>
                                        </div>

                                        @endforeach
                                    </div>

                                </div>
                                <div class="card-footer border-gray-100 d-grid px-4 pb-4">
                                    <a href="./sign-up.html" target="_blank"
                                        class="btn btn-gray-800 d-inline-flex align-items-center justify-content-center">
                                        Start for Free
                                        <svg class="icon icon-xs ms-2" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif


            </div>
        </div>
    </div>
@endsection
