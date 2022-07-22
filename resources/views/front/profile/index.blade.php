@extends('front.profile.layouts.app')

@section('profile-content')
    <div class="col-sm-7">
        <div class="row">
            <div class="col-sm-6">
                <a href="{{ route('front.user.manage') }}" class="account-card">
                    <figure>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-user">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                    </figure>
                    <figcaption>
                        <h4>Profile Details</h4>
                        <h6>Change your profile details</h6>
                    </figcaption>
                </a>
            </div>

            <div class="col-sm-6">
                @if(\Auth::guard('web')->user()->user_type == 6)
                    @php
                        $store_id = DB::select('SELECT id FROM stores WHERE store_name = "'.Auth::guard('web')->user()->name.'"');
                    @endphp

                    <a href="{{ route('front.user.order', ['store' => $store_id[0]->id]) }}" class="account-card">
                @else
                    <a href="{{ route('front.user.order') }}" class="account-card">
                @endif
                    <figure>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-package">
                                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                </path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </span>
                    </figure>
                    <figcaption>
                        <h4>Orders</h4>
                        <h6>Check your order status</h6>
                    </figcaption>
                </a>
            </div>

            <div class="col-sm-6">
                <a href="{{ route('front.user.password.edit') }}" class="account-card">
                    <figure>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-package">
                                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                </path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </span>
                    </figure>
                    <figcaption>
                        <h4>Change Password</h4>
                        <h6>Check your old password or create a new one</h6>
                    </figcaption>
                </a>
            </div>
        </div>
    </div>
@endsection
