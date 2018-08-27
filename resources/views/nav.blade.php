<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-light " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
        <li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li>
        <li class="nav-item start active open">
            <a href="{{ url('admin') }}" class="nav-link nav-toggle">
                <i class="icon-home"></i>
                <span class="title">Dashboard</span>
                <span class="selected"></span>
            </a>
        </li>

        <li class="nav-item  ">
            <a href="{{ url('admin/users') }}" class="nav-link">
                <i class="fa fa-user"></i>
                <span class="title">Quản trị viên</span>
            </a>
        </li>

        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-book"></i>
                <span class="title">Quản lý truyện</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="{{ url('admin/mangas') }}" class="nav-link ">
                        <span class="title">Danh sách</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item  ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-sitemap"></i>
                <span class="title">Quản lý thể loại</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item  ">
                    <a href="{{ url('admin/categories') }}" class="nav-link ">
                        <span class="title">Danh sách</span>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</div>