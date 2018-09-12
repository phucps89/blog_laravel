<li class="nav-item mT-30 active">
    <a class='sidebar-link' href="{{ route(ADMIN . '.dash') }}" default>
        <span class="icon-holder">
            <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Quản trị</span>
    </a>
</li>
<li class="nav-item">
    <a class='sidebar-link' href="{{ route(ADMIN . '.users.index') }}" >
        <span class="icon-holder">
            <i class="c-brown-500 ti-user"></i>
        </span>
        <span class="title">Người dùng</span>
    </a>
</li>
<li class="nav-item">
    <a class='sidebar-link' href="{{ route(ADMIN . '.category.index') }}" >
        <span class="icon-holder">
            <i class="c-brown-500 ti-book"></i>
        </span>
        <span class="title">Category</span>
    </a>
</li>
<li class="nav-item">
    <a class='sidebar-link' href="{{ route(ADMIN . '.farm.index') }}" >
        <span class="icon-holder">
            <i class="c-brown-500 ti-book"></i>
        </span>
        <span class="title">Chuồng trại</span>
    </a>
</li>

<li class="nav-item dropdown">
    <a class="dropdown-toggle" href="javascript:void(0);">
        <span class="icon-holder"><i
                class="c-teal-500 ti-view-list-alt"></i> </span>
        <span class="title">Gia súc & cây trồng</span>
        <span class="arrow"><i class="ti-angle-right"></i></span>
    </a>
    <ul class="dropdown-menu">
        <li class="nav-item dropdown"><a href="{{ route(ADMIN . '.breed_crop.index') }}"><span>Danh sách cá thể</span></a></li>
        <li class="nav-item dropdown"><a href="{{ route(ADMIN . '.group_breed_crop.index') }}"><span>Danh sách nhóm</span></a></li>
    </ul>
</li>
