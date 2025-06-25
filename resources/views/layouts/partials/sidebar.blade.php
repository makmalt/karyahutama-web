<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-4">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo4fix.png') }}" alt="Logo" width="40" height="50">
            </span>
            <span class="app-brand-text menu-text fw-bolder ms-2 fs-6">Karya Hutama Oxygen</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data</span>
        </li>
        <li class="menu-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <a href="{{ route('barang.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Analytics">Barang</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('supplier.*') ? 'active' : '' }}">
            <a href="{{ route('supplier.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Analytics">Supplier</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <a href="{{ route('transaksi.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Analytics">Transaksi</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('tagihan.*') ? 'active' : '' }}">
            <a href="{{ route('tagihan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Analytics">Tagihan</div>
            </a>
        </li>
        <!-- Components -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Lainnya</span></li>

        <li class="menu-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <a href="{{ route('kategori.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Analytics">Kategori</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
            <a href="{{ route('user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">User</div>
            </a>
        </li>
    </ul>
</aside>
