<div class="header d-flex justify-content-between align-items-center">
    <div class="nav-link">
        <span class="pages1">Pages / {{ ucfirst(explode('.', Route::currentRouteName())[0]) }}</span>
        <br>
        <span class="pages2">{{ ucfirst(explode('.', Route::currentRouteName())[0]) }}</span>
    </div>

    <div class="d-flex align-items-center">
        {{-- Email User --}}
        <span class="user-email">{{ auth()->user()->email }}</span>

        {{-- Menu Button --}}
        <div id="menu-button">
            <input type="checkbox" id="menu-checkbox">
            <label for="menu-checkbox" id="menu-label">
                <div id="hamburger"></div>
            </label>
        </div>
    </div>
</div>
