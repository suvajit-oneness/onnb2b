<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-equal-height.min.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    @php
        if (auth()->guard('web')->check()) {
            $userType = auth()->guard('web')->user()->user_type;
        }
    @endphp

    <div id="app">
        @if( !request()->is('login') && !request()->is('login/otp*') && !request()->is('register') )
        <nav class="topnavbar navbar navbar-expand-md">
            <div class="container-fluid px-0">
                <div class="logo">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <!-- {{ config('app.name', 'Laravel') }} -->
                        <img src="{{asset('img/logo-square.png')}}" alt="">
                    </a>
                </div>
                <nav class="topNavigation">
                    <ul class="navbar-nav ml-auto align-items-center">
                        @auth
                            @if($userType != 1 && $userType != 6)
                            <li class="nav-item">
                                <a class="minicartBtn" href="{{ route('front.cart.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                @if($cartCount > 0)<span class="badge badge-danger">{{ $cartCount }}</span>@endif</a>
                            </li>
                            @endif
                        @endauth
                        @guest
                            <li class="nav-item link__login">
                                <a class="nav-link" href="{{ route('login') }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>{{ __('Login') }}</a>
                            </li>
                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                            <li class="nav-item profileDrodown dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::guard('web')->user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <p class="dropdown-header">
                                        @php
                                            switch ($userType) {
                                                case 1: $userTypeDetail = "Vice President";break;
                                                case 2: $userTypeDetail = "Regional sales manager";break;
                                                case 3: $userTypeDetail = "Area sales manager";break;
                                                case 4: $userTypeDetail = "Area sales executive";break;
                                                case 5: $userTypeDetail = "Distributor";break;
                                                case 6: $userTypeDetail = "Retailer";break;
                                                default: $userTypeDetail = "";break;
                                            }
                                        @endphp
                                        {{$userTypeDetail}}
                                    </p>
                                    <a class="dropdown-item" href="{{ route('front.user.profile') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        {{ __('Profile') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        {{ __('Logout') }}
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
        </nav>

        <aside class="left_bar">
            <ul>
                <li class="nav-item {{ (request()->is('dashboard*')) ? 'active' : '' }}"><a href="{{ route('front.dashboard.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Dashboard</a>
                </li>

                {{-- not for VP, Distributor & Retailer --}}
                @if ($userType != 1 && $userType != 2 && $userType != 3 && $userType != 5 && $userType != 6)
                    <li class="nav-item {{ (request()->is('order-on-call*')) ? 'active' : '' }}"><a href="{{ route('front.store.order.call.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        Order on call</a>
                    </li>
                    <li class="nav-item {{ (request()->is('store')) ? 'active' : '' }}"><a href="{{ route('front.store.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        Store visit</a>
                    </li>
                @endif

                {{-- only for ASE --}}
                @if ($userType == 4)
                    <li class="nav-item {{ (request()->is('store/add*')) ? 'active' : '' }}"><a href="{{ route('front.store.add') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        Add Store</a>
                    </li>
                @endif

                {{-- not for VP, Distributor & Retailer --}}
                @if ($userType != 1 && $userType != 5 && $userType != 6)
                    <li class="nav-item {{ (request()->is('distributor*')) ? 'active' : '' }}"><a href="{{ route('front.directory.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        Distributor</a>
                    </li>
                @endif

                {{-- only for Distributor --}}
                @if ($userType == 5)
                    <li class="nav-item {{ (request()->is('store/order/distributor*')) ? 'active' : '' }}"><a href="{{ route('front.store.order.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        Store Orders</a>
                    </li>
                    <li class="nav-item {{ (request()->is('distributor/order/place*')) ? 'active' : '' }}"><a href="{{ route('front.distributor.order.place.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        Place Order</a>
                    </li>
                @endif

                {{-- only for Retailer --}}
                @if ($userType == 6)
                    <li class="nav-item {{ (request()->is('invoice*')) ? 'active' : '' }}"><a href="{{ route('front.invoice.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Inovice</a>
                    </li>
                    <li class="nav-item {{ (request()->is('store/image*')) ? 'active' : '' }}"><a href="{{ route('front.store.image.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Store Image</a>
                    </li>
                @endif

                <li class="nav-item {{ (request()->is('scheme*')) ? 'active' : '' }}"><a href="{{ route('front.offer.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    Schemes</a>
                </li>
                <li class="nav-item {{ (request()->is('download*')) ? 'active' : '' }}"><a href="{{ route('front.catalouge.download.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Catalogue</a>
                </li>

                {{-- not for Retailer --}}
                @if ($userType != 6)
                    {{-- <li class="nav-item {{ (request()->is('sales/report*')) ? 'active' : '' }}"><a href="{{ route('front.sales.report.index') }}"> --}}
                    @if ($userType == 4)
                        <li class="nav-item {{ (request()->is('sales/report*')) ? 'active' : '' }}"><a href="{{ route('front.sales.report.detail.updated', ['ase' => auth()->guard('web')->user()->name, 'state' => auth()->guard('web')->user()->state]) }}">
                    @else
                        <li class="nav-item {{ (request()->is('sales/report*')) ? 'active' : '' }}"><a href="{{ route('front.sales.report.index') }}">
                    @endif
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                        Sales report</a>
                    </li>

                
                    <li class="nav-item {{ (request()->is('target*')) ? 'active' : '' }}"><a href="{{ route('front.target.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                        Target</a>
                    </li>
                @endif

                <li class="nav-item ">
                    <h5 class="acountText">Account</h5>
                </li>

                <li class="nav-item {{ (request()->is('user/profile*')) ? 'active' : '' }}">
                    <a href="{{ route('front.user.profile') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profile</a>
                </li>

                @if($userType == 6)
                    @php
                        $store_id = DB::select('SELECT id FROM stores WHERE store_name = "'.Auth::guard('web')->user()->name.'"');
                    @endphp
                    @if (count($store_id) > 0)
                    <li class="nav-item {{ (request()->is('user/order*')) ? 'active' : '' }}">
                        <a href="{{ route('front.user.order', ['store' => $store_id[0]->id]) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        Order</a>
                    </li>
                    @endif
                @else
                <li class="nav-item {{ (request()->is('user/order*')) ? 'active' : '' }}">
                    <a href="{{ route('front.user.order') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Order</a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout</a>
                </li>
            </ul>
        </aside>
        @endif

        <main class="mainbody {{ (request()->is('login*')) ? 'mainbodyNomargin' : '' }}">
            @yield('content')
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <script src="{{ asset('js/jquery-equal-height.min.js') }}" defer></script>

    <script>
        // tooltip
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        // sweetalert fires | type = success, error, warning, info, question
        function toastFire(type = 'success', title, body = '') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: title,
                text: body,
                showConfirmButton: false,
                confirmButtonColor: '#c10909',
                timer: 1000
            })
        }

        // on session toast fires
        @if (Session::get('success'))
            toastFire('success', '{{ Session::get('success') }}');
        @elseif (Session::get('failure'))
            toastFire('danger', '{{ Session::get('failure') }}');
        @endif

        $('.storeCatgoryList a' ).on( 'click', function(e){
            var href = $(this).attr( 'href' );
            $( 'html, body' ).animate({
                scrollTop: $( href ).offset().top - 140
            } );
            e.preventDefault();
            $(this).parent().addClass("current");
            $(this).parent().siblings().removeClass("current");

        });

        $("document").ready(function(){
            $('.jQueryEqualHeight').jQueryEqualHeight('.store_card');
        })

        function onlyNumberKey(evt) {
            // Only ASCII character in that range allowed
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }


        $('body').click(function(){
            $('.postcode-dropdown').removeClass('show');
        });
        $('input.dropdown-toggle').click(function(event){
            event.stopPropagation();
        });
        $('input.dropdown-toggle').click(function(){
            $('.postcode-dropdown').addClass('show');
        });
    </script>

    @yield('script')
</body>
</html>
