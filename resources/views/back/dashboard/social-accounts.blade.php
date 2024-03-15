@extends('back.layouts.app')
@section('content')
    <div class="py-4">
        <div class="dropdown">
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
                <a class="dropdown-item d-flex align-items-center" href="{{url("auth/facebook")}}">
                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z">
                        </path>
                    </svg>
                    Add Facebook
                </a>

            </div>
        </div>
    </div>
    <div class="row justify-content-lg-center">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="table-responsive">
                    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                        <div class="dataTable-container">
                            <table class="table table-flush dataTable-table" id="datatable">
                                <thead class="thead-light">
                                    <tr>
                                        <th data-sortable="" style="width: 19.2045%;"><a href="#"
                                                class="dataTable-sorter">Name</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tiger Nixon</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="dataTable-bottom">
                            <div class="dataTable-info">Showing 1 to 10 of 57 entries</div>
                            <nav class="dataTable-pagination">
                                <ul class="dataTable-pagination-list">
                                    <li class="pager"><a href="#" data-page="1">‹</a></li>
                                    <li class="active"><a href="#" data-page="1">1</a></li>
                                    <li class=""><a href="#" data-page="2">2</a></li>
                                    <li class=""><a href="#" data-page="3">3</a></li>
                                    <li class=""><a href="#" data-page="4">4</a></li>
                                    <li class=""><a href="#" data-page="5">5</a></li>
                                    <li class=""><a href="#" data-page="6">6</a></li>
                                    <li class="pager"><a href="#" data-page="2">›</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
