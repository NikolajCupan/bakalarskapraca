@aware(['user'])
@aware(['imagePath'])
@aware(['basket'])

<script>
    $(document).ready(function() {
        let offcanvas = document.getElementById('offcanvasRight')

        offcanvas.addEventListener('hidden.bs.offcanvas', function() {
            $('.collapseUserOutOfFocus').collapse('hide')
        })
    });
</script>

<script type="text/javascript" src="{{asset('js/showSearchNavbar.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/navbarStyles.css')}}">

<nav class="border-bottom navbar navbar-expand-lg bg-light navbar-light">
    <div class="container">
        <!-- Logo on the left side (always visible) -->
        <a class="navbar-brand" href="/">
            <img id="eshopLogo" src="{{asset('/images/logo.png')}}" alt="eshopLogo" draggable="false" height="30"/>
        </a>


        <!-- Search in center (always visible) -->
        <div class="navbarItemCenter">
            <svg style="cursor: pointer" id="openSearchNavbar" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </div>


        <!-- Small screen -->
        <div class="d-lg-none">
            <a type="button" class="btn btn-light btn-lg" data-bs-toggle="offcanvas" href="#offcanvasRight" role="button" aria-controls="offcanvasRight">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-list-ul" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
            </a>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasRightLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <a class="mt-0 navbarButton sideMenuItem row nav-link mx-2" href="/contact">Kontakt</a>
                    <a class="navbarButton sideMenuItem row nav-link mx-2" href="/about">O nas</a>

                    @auth
                        <a class="navbarButton sideMenuItem row nav-link mx-2" href="/user/basket/show">
                            <div style="text-align: center">
                                <div style="display: inline-block; vertical-align: middle;">
                                    Kosik
                                </div>

                                <div style="display: inline-block; vertical-align: middle;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="cart-icon bi bi-cart" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <a class="navbarButton sideMenuItem row nav-link mx-2" data-bs-toggle="collapse" href="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                            <div style="text-align: center">
                                <div style="display: inline-block; vertical-align: middle;">
                                    Ucet
                                </div>

                                <div style="display: inline-block; vertical-align: middle;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                        <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>

                        <div class="collapseUserOutOfFocus collapse" id="collapseUser">
                            <div class="pt-0 card card-body">

                                @if (!is_null($imagePath))
                                    <img class="mt-2 imageBorder navbarImage img-fluid bg-light" src="{{asset('/storage/images/users/' . $imagePath)}}" alt="">
                                @else
                                    <img class="mt-2 imageBorder navbarImage img-fluid bg-light" src="{{asset('/images/userNoImage.png')}}" alt="">
                                @endif
                                <p class="fw-bold mt-1 mb-0">{{$user->first_name . ' ' . $user->last_name}}</p>

                                @if ($user->hasRole(['accountManager', 'productManager', 'purchaseManager']))
                                    <a class="navbarButton sideMenuItemSmall" href="/admin">Sprava</a>
                                @endif

                                <a class="navbarButton sideMenuItemSmall" href="/user/select">Zmenit udaje</a>
                                <a class="navbarButton sideMenuItemSmall" href="/user/purchaseHistory">Objednavky</a>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="navbarButton sideMenuItemSmall">Odhlasit</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a class="navbarButton sideMenuItem row nav-link mx-2" href="/login">Login</a>
                        <a class="navbarButton sideMenuItem row nav-link mx-2" href="/register">Register</a>
                    @endauth
                </div>
            </div>
        </div>



        <!-- Big screen -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="navbarButton nav-link mx-2" href="/contact">Kontakt</a>
                </li>

                <li class="nav-item">
                    <a class="navbarButton nav-link mx-2" href="/about">O nas</a>
                </li>

                @auth
                    <li class="nav-item ms-5">
                        <div class="dropdown">
                            <a class="navbarButton nav-link mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                                Kosik
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="userMenuItemText noWrap">
                                @php($variousProductsCount = $basket->getVariousProductsCount())

                                {{$variousProductsCount}}
                                @if ($variousProductsCount == 1)
                                    produkt
                                @elseif ($variousProductsCount > 1 && $variousProductsCount < 5)
                                    rozne produkty
                                @else
                                    roznych produktov
                                @endif
                                </li>

                                <li class="userMenuItemText">{{$basket->getTotalPrice()}} &euro;</li>

                                <div class="dropdown-divider"></div>
                                <li><a class="userMenuItem dropdown-item" href="/user/basket/show">Zobrazit</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="navbarButton nav-link mx-2" data-bs-toggle="dropdown" aria-expanded="false">
                                Ucet
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                                    <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                                </svg>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <h6 class="text-center dropdown-header">
                                    @if (!is_null($imagePath))
                                        <img class="imageBorder navbarImage img-fluid bg-light" src="{{asset('/storage/images/users/' . $imagePath)}}" alt="">
                                    @else
                                        <img class="imageBorder navbarImage img-fluid bg-light" src="{{asset('/images/userNoImage.png')}}" alt="">
                                    @endif
                                    <p style="color: black" class="fw-bold mt-1 mb-0">{{$user->first_name . ' ' . $user->last_name}}</p>
                                </h6>

                                @if ($user->hasRole(['accountManager', 'productManager', 'purchaseManager']))
                                    <div class="dropdown-divider"></div>
                                    <li><a class="userMenuItem dropdown-item" href="/admin">Sprava</a></li>
                                @endif

                                <div class="dropdown-divider"></div>
                                <li><a class="userMenuItem dropdown-item" href="/user/select">Zmenit udaje</a></li>
                                <li><a class="userMenuItem dropdown-item" href="/user/purchaseHistory">Objednavky</a></li>

                                <div class="dropdown-divider"></div>
                                <li>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="userMenuItem dropdown-item">Odhlasit</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item ms-5">
                        <a class="userMenuItem navbarButton nav-link mx-2" href="/login">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="userMenuItem navbarButton nav-link mx-2" href="/register">Register</a>
                    </li>
                @endauth
            </ul>
        </div>

    </div>
</nav>

<!-- Standard navbar always contains search button -->
<x-navbar.search/>
