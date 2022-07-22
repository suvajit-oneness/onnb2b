<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    <title>{{ config('app.name', 'Laravel') }} | @yield('page')</title>
</head>

<body>
    <aside class="side__bar shadow-sm">
        <div class="admin__logo">
            <div class="logo">
                <svg width="322" height="322" viewBox="0 0 322 322" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="231.711" y="47.8629" width="60" height="260" rx="30" transform="rotate(45 231.711 47.8629)" fill="#c10909" />
                    <rect x="236.66" y="137.665" width="60" height="180" rx="30" transform="rotate(45 236.66 137.665)" fill="#c10909" />
                    <rect x="141.908" y="42.9132" width="60" height="180" rx="30" transform="rotate(45 141.908 42.9132)" fill="#c10909" />
                </svg>
            </div>
            <div class="admin__info" style="width: 100% ; overflow : hidden" >
                <h1>{{ Auth()->guard('admin')->user()->name }}</h1>
                <h4 style=" overflow : hidden ; whitespace: narrow" >{{ Auth()->guard('admin')->user()->email }}</h4>
            </div>
        </div>
        <nav class="main__nav">
            <ul>
                <li class="{{ ( request()->is('admin/home*') ) ? 'active' : '' }}"><a href="{{ route('admin.home') }}"><i class="fi fi-br-home"></i> <span>Dashboard</span></a></li>

                <li class="@if(request()->is('admin/category*') || request()->is('admin/subcategory*') || request()->is('admin/collection*')|| request()->is('admin/catalogue*')) { {{'active'}} }  @endif">
                    <a href="#"><i class="fi fi-br-cube"></i> <span>Master</span></a>
                    <ul>
                        <li class="{{ ( request()->is('admin/category*') ) ? 'active' : '' }}"><a href="{{ route('admin.category.index') }}"><i class="fi fi-br-database"></i> <span>Category</span></a></li>
                        <li class="{{ ( request()->is('admin/collection*') ) ? 'active' : '' }}"><a href="{{ route('admin.collection.index') }}"><i class="fi fi-br-database"></i> <span>Collection</span></a></li>
                        <li class="{{ ( request()->is('admin/catalogue*') ) ? 'active' : '' }}"><a href="{{ route('admin.catalogue.index') }}"><i class="fi fi-br-database"></i> <span>Catalogue</span></a></li>
                    </ul>
                </li>

                <li class="@if(request()->is('admin/product*') || request()->is('admin/faq*')) { {{'active'}} }  @endif">
                    <a href="#"><i class="fi fi-br-cube"></i> <span>Product Management</span></a>
                    <ul>
                        <li class="{{ ( request()->is('admin/product/list*') ) ? 'active' : '' }}"><a href="{{ route('admin.product.index') }}">All Product</a></li>

                        <li class="{{ ( request()->is('admin/product/create*') ) ? 'active' : '' }}"><a href="{{ route('admin.product.create') }}">Add New</a></li>
                    </ul>
                </li>

                <li class="{{ ( request()->is('admin/store*') ) ? 'active' : '' }}"><a href="{{ route('admin.store.index') }}"><i class="fi fi-br-database"></i> <span>Store Management</span></a></li>
                <li class="{{ ( request()->is('admin/noorderreason') ) ? 'active' : '' }}"><a href="{{ route('admin.noorderreasonview') }}"><i class="fi fi-br-database"></i> <span>No Order Reason</span></a></li>

                <li class="{{ ( request()->is('admin/user') ) ? 'active' : '' }}"><a href="{{ route('admin.user.index') }}"><i class="fi fi-br-database"></i> <span>User Management</span></a></li>
                <li class="@if(request()->is('admin/distributor*') || request()->is('admin/distributor/directory*')) { {{'active'}} }  @endif">
                    <a href="#"><i class="fi fi-br-cube"></i> <span>Distributor</span></a>
                    <ul>
                        <li class="{{ ( request()->is('admin/distributor*') ) ? 'active' : '' }}"><a href="{{ route('admin.distributor.index') }}"><i class="fi fi-br-database"></i> <span>Distributor Management</span></a></li>
                       
                        <li class="{{ ( request()->is('admin/distributor/directory*') ) ? 'active' : '' }}"><a href="{{ route('admin.distributor.directory') }}"><i class="fi fi-br-database"></i> <span>Distributor MOM</span></a></li>
                    </ul>
                </li>


                {{-- <li class="@if(request()->is('admin/retailer*') || request()->is('admin/retailer/order*')|| request()->is('admin/invoice*')|| request()->is('admin/image*')) { {{'active'}} }  @endif">
                    <a href="#"><i class="fi fi-br-cube"></i> <span>Retailer</span></a>
                    <ul>
                        <li class="{{ ( request()->is('admin/retailer*') ) ? 'active' : '' }}"><a href="{{ route('admin.retailer.index') }}"><i class="fi fi-br-database"></i> <span>Retailer Management</span></a></li>
                        <li class="{{ ( request()->is('admin/retailer/order*') ) ? 'active' : '' }}"><a href="{{ route('admin.order.index') }}"><i class="fi fi-br-database"></i> <span>Retailer Orders</span></a></li>
                        {{-- <li class="{{ ( request()->is('admin/retailer/invoice*') ) ? 'active' : '' }}"><a href="{{ route('admin.retailer.invoice.index') }}"><i class="fi fi-br-database"></i> <span>Invoice </span></a></li> --}}
                        {{-- <li class="{{ ( request()->is('admin/retailer/image*') ) ? 'active' : '' }}"><a href="{{ route('admin.retailer.image.index') }}"><i class="fi fi-br-database"></i> <span>Store Images</span></a></li>
                    </ul>
                </li> --}}


                <li class="{{ ( request()->is('admin/useractivity*') ) ? 'active' : '' }}"><a href="{{ route('admin.useractivity.index') }}"><i class="fi fi-br-database"></i> <span>User Activity</span></a></li>

                <li class="{{ ( request()->is('admin/offer*') ) ? 'active' : '' }}"><a href="{{ route('admin.offer.index') }}"><i class="fi fi-br-database"></i> <span>Scheme Management</span></a></li>

                <li class="{{ ( request()->is('admin/target*') ) ? 'active' : '' }}"><a href="{{ route('admin.target.index') }}"><i class="fi fi-br-database"></i> <span>Target Management</span></a></li>
                <!--<li class="{{ ( request()->is('admin/achievement*') ) ? 'active' : '' }}"><a href="{{ route('admin.achievement.index') }}"><i class="fi fi-br-database"></i> <span>Invoice</span></a></li>-->

                <li class="@if(request()->is('admin/order*')) { {{'active'}} }  @endif">
                    <a href="javascript: void(0)"><i class="fi fi-br-cube"></i> <span>Order Management</span></a>
                    <ul>
                        <li class="{{ ( request()->is('admin/order') ) ? 'active' : '' }}"><a href="{{ route('admin.distributor.order.index') }}"><i class="fi fi-br-database"></i> <span>Primary Orders</span></a></li>
                        <li class="{{ ( request()->is('admin/order') ) ? 'active' : '' }}"><a href="{{ route('admin.order.index') }}"><i class="fi fi-br-database"></i> <span>Secondary Orders</span></a></li>


                    </ul>
                </li>
                <li class="{{ ( request()->is('admin/sales*') ) ? 'active' : '' }}"><a href="{{ route('admin.sales.report.index') }}"><i class="fi fi-br-database"></i> <span>Sales Report</span></a></li>

                <li class="@if(request()->is('admin/settings*') || request()->is('admin/faq*')) { {{'active'}} }  @endif">
                    <a href="#"><i class="fi fi-br-cube"></i> <span>Settings</span></a>
                    <ul>
                        <li class="{{ ( request()->is('admin/settings*') ) ? 'active' : '' }}"><a href="{{ route('admin.settings.index') }}"><i class="fi fi-br-database"></i> <span>Site Settings</span></a></li>

                        <li class="{{ ( request()->is('admin/faq*') ) ? 'active' : '' }}"><a href="{{ route('admin.faq.index') }}"><i class="fi fi-br-database"></i> <span>FAQs</span></a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="nav__footer">
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fi fi-br-sign-out"></i> <span>Log Out</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>
    <main class="admin">
        <header>
            <div class="row align-items-center">
                {{-- <div class="col-auto">
                    <input type="search" name="" class="form-control header__search" placeholder="Quick Search here">
                </div> --}}
                {{-- <div class="col-auto ms-auto">
                    <a href="#" class="notify__bell"><i class="fi fi-br-bell"></i></a>
                </div> --}}
                <div class="col-auto ms-auto">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::guard('admin')->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="{{route('admin.admin.profile')}}">Profile</a></li>
                            <li> <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fi fi-br-sign-out"></i> <span>Log Out</span></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <section class="admin__title">
            <h1>@yield('page')</h1>
        </section>

        @yield('content')

        <footer>
            <div class="row">
                <div class="col-12 text-end">Total Comfort 2021-{{date('Y')}}</div>
            </div>
        </footer>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ asset('admin/js/custom.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.22/sweetalert2.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->


    <script>
        // click to select all checkbox
        function headerCheckFunc() {
            if ($('#flexCheckDefault').is(':checked')) {
                $('.tap-to-delete').prop('checked', true);
                clickToRemove();
            } else {
                $('.tap-to-delete').prop('checked', false);
                clickToRemove();
            }
        }

        // sweetalert fires | type = success, error, warning, info, question
        function toastFire(type = 'success', title, body = '') {
            Swal.fire({
                icon: type,
                title: title,
                text: body,
                // confirmButtonColor: '#c10909',
                showConfirmButton: false,
                timer: 1000
            })
        }

        // on session toast fires
        @if (Session::get('success'))
            toastFire('success', '{{ Session::get('success') }}');
        @elseif (Session::get('failure'))
            toastFire('danger', '{{ Session::get('failure') }}');
        @endif
    </script>

    @yield('script')
</body>
</html>
