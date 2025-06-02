@php
    $menus = [
        ['route' => 'dashboard', 'icon' => 'fas fa-home', 'color' => '#4e73df', 'label' => 'Dashboard'],

        ['header' => 'Management'],
        ['route' => 'locations.index', 'icon' => 'fas fa-location-dot', 'color' => '#1cc88a', 'label' => 'Locations'],
        ['route' => 'inventories.index', 'icon' => 'fas fa-boxes-stacked', 'color' => '#f6c23e', 'label' => 'Inventories'],
        ['route' => 'allocates.index', 'icon' => 'fas fa-map-marker-alt', 'color' => '#e74a3b', 'label' => 'Allocates'],
        ['route' => 'transfers.index', 'icon' => 'fas fa-right-left', 'color' => '#36b9cc', 'label' => 'Transfers'],

        ['header' => 'Account / User'],
        ['route' => 'profile.index', 'icon' => 'fas fa-user', 'color' => '#858796', 'label' => 'Profile'],
        ['route' => 'logout', 'icon' => 'fas fa-right-from-bracket', 'color' => '#fd7e14', 'label' => 'Logout'],
    ];
@endphp

<div class="sidebar">
    <div class="main">
        <div class="list-item">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logo-iflab.png') }}" alt="">
                <span class="description-header">Informatics Lab</span>
            </a>
        </div>
        <hr style="height: 1px; background-color: #000; border: none;" class="mb-6">
        @foreach($menus as $menu)
            @if(isset($menu['header']))
                <div class="nav-item">
                    <span class="description">{{ $menu['header'] }}</span>
                </div>
            @elseif($menu['route'] === 'logout')
                <div class="nav-link mb-3">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="icon {{ $menu['icon'] }}" style="color: {{ $menu['color'] }};"></i>
                        <span class="description">{{ $menu['label'] }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @else
                <div class="nav-link mb-3 {{ Route::currentRouteName() === $menu['route'] ? 'active' : '' }}">
                    <a href="{{ route($menu['route']) }}">
                        <i class="icon {{ $menu['icon'] }}" style="color: {{ $menu['color'] }};"></i>
                        <span class="description">{{ $menu['label'] }}</span>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>
