<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content='{{csrf_token()}}'>

    <script src="https://cdn.tiny.cloud/1/kr5qpo97olmh4kbfsbp2bl8ur559ahx7zyjmrv9cx7sv8eux/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        var editor_config = {
            path_absolute: "http://localhost/laravel/unimart/",
            selector: 'textarea',
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality",
                "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };

        tinymce.init(editor_config);
    </script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('css')
    <title>Admintrator</title>
</head>

<body>
    {{-- <style>
        :root{
            --transition-effect: 0.25s cubic-bezier(.25,-0.59,.82,1.66) .3s;
        }
        body{
            background: #fff;
            transition: var(--transition-effect);
        }
        body.dark{
            background: #111!important ;
        }
        #wrapper{
             display: flex; 
             justify-content: center; 
             align-items: center; 
            min-height: 60vh; 
        }
        .switch-toggle{
            width: 90px;
            height: 50px;
            appearance: none;
            background: #83d8ff;
            border-radius: 26px;
            position: relative;
            cursor: pointer;
            box-shadow: inset 0px 0px 16px rgba(0, 0, 0, .3);
            transition: var(--transition-effect);
        }
        .switch-toggle::before{
            content: "";
            width: 44px;
            height: 44px;
            position: absolute;
            top: 3px;
            left: 3px;
            background: #efe2bf;
            border-radius: 50%;
            box-shadow: 0px 0px 6px rgba(0, 0, 0, .3);
            transition: var(--transition-effect);
        
        }
        .switch-toggle:checked{
            background: #749dd6;
        }
        .switch-toggle:checked:before{
            left: 40px;
        }
    </style> --}}
    <div id="wrapper" class="nav-fixed">

        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="{{url("dashboard")}}">ESHOPPER ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        @can('post-add')
                        <a class="dropdown-item" href="{{url("admin/post/add")}}">Thêm bài viết</a>
                        @endcan
                        @can('product-add')
                        <a class="dropdown-item" href="{{url("admin/product/add")}}">Thêm sản phẩm</a>
                        @endcan

                        <a class="dropdown-item" href="{{url("admin/order/list")}}">Xem đơn hàng</a>

                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{Auth::user()->name}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Tài khoản</a>

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    @php
                    $module_active=session('module_active');
                    @endphp

                    @can('dashboard-list')
                    <li class="nav-link {{$module_active=='dashboard'?'active':''}}">
                        <a href="{{url("dashboard")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>


                    </li>
                    @endcan

                    @can('menu-list')
                    <li class="nav-link {{$module_active=='menu'?'active':''}}">
                        <a href="{{url("admin/menu/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Menu
                        </a>
                    </li>
                    @endcan

                    @can('slider-list')
                    <li class="nav-link {{$module_active=='slider'?'active':''}}">
                        <a href="{{url("admin/slider/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Slider
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('slider-add')
                            <li><a href="{{url("admin/slider/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/slider/list")}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('comment-list')
                    <li class="nav-link {{$module_active=='comment'?'comment':''}}">
                        <a href="{{url("admin/comment/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Comment
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">

                            @can('slider-add')
                            <li><a href="{{url("admin/slider/add")}}">Thêm mới</a></li>
                            @endcan

                            <li><a href="{{url("admin/slider/list")}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('video-list')
                    <li class="nav-link {{$module_active=='video'?'active':''}}">
                        <a href="{{url('admin/video/list')}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Video
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('video-add')
                            <li><a href="{{url('admin/video/add')}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url('admin/video/list')}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('setting-list')
                    <li class="nav-link {{$module_active=='setting'?'active':''}}">
                        <a href="{{url("admin/setting/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Setting
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">

                            @can('setting-add')
                            <a data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapse">
                                Thêm mới
                            </a>
                            <div class="collapse" id="collapse">

                                <a href="{{route('admin.setting.add').'?type=Text'}}"> Text</a><br>
                                <a href="{{route('admin.setting.add').'?type=Textarea'}}"> Textarea</a>
                            </div>
                            @endcan

                            <li><a href="{{url("admin/setting/list")}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('page-list')
                    <li class="nav-link {{$module_active=='page'?'active':''}}">
                        <a href="{{url("admin/page/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Trang
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('page-add')
                            <li><a href="{{url("admin/page/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/page/list")}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('post-list')
                    <li class="nav-link {{$module_active=='post'?'active':''}}">
                        <a href="{{url("admin/post/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bài viết
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            @can('post-add')
                            <li><a href="{{url("admin/post/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/post/list")}}">Danh sách</a></li>
                            @can('category-post-list')
                            <li><a href="{{url("admin/post/cat/list")}}">Danh mục</a></li>
                            @endcan


                        </ul>
                    </li>
                    @endcan

                    @can('product-list')
                    <li class="nav-link {{$module_active=='product'?'active':''}}">
                        <a href="{{url("admin/product/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Sản phẩm
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            @can('product-add')
                            <li><a href="{{url("admin/product/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/product/list")}}">Danh sách</a></li>

                            @can('category-product-list')
                            <li><a href="{{url("admin/product/cat/list")}}">Danh mục</a></li>
                            @endcan

                        </ul>
                    </li>

                    @endcan

                    @can('product-brand-list')
                    <li class="nav-link {{$module_active=='product_brand'?'active':''}}">
                        <a href="{{url("admin/brand/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Thương hiệu
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            <li><a href="{{url("admin/brand/add")}}">Thêm mới</a></li>
                            <li><a href="{{url("admin/brand/list")}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('order-list')
                    <li class="nav-link {{$module_active=='order'?'active':''}}">
                        <a href="{{url("admin/order/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Bán hàng
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{url("admin/order/list")}}">Đơn hàng</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('order-coupon-list')
                    <li class="nav-link {{$module_active=='order_coupon'?'active':''}}">
                        <a href="{{url("admin/order/coupon/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Mã giảm giá
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            @can('order-coupon-add')
                            <li><a href="{{url("admin/order/coupon/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/order/coupon/list")}}">Danh sách</a></li>
                            @can('event-list')
                            <li><a href="{{url("admin/event/list")}}">Sự kiện</a></li>
                            @endcan

                        </ul>
                    </li>
                    @endcan

                    @can('fee-shipping-list')
                    <li class="nav-link {{$module_active=='fee_shipping'?'active':''}}">
                        <a href="{{url("admin/Fee_shipping/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Phí vận chuyển
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <!-- <ul class="sub-menu">
                            <li><a href="{{url("admin/order/coupon/add")}}">Thêm mới</a></li>
                            <li><a href="{{url("admin/order/coupon/list")}}">Danh sách</a></li>
                            <li><a href="{{url("admin/event/list")}}">Sự kiện</a></li>

                        </ul> -->
                    </li>
                    @endcan

                    @can('user-list')

                    <li class="nav-link {{$module_active=='user'?'active':''}}">
                        <a href="{{url("admin/user/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Users
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('user-add')
                            <li><a href="{{url("admin/user/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/user/list")}}">Danh sách</a></li>
                        </ul>
                    </li>
                    @endcan

                    @can('role-list')
                    @can('permission-list')
                    <li class="nav-link {{$module_active=='role'?'active':''}}">
                        <a href="{{url("admin/role/list")}}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Phân quyền
                        </a>
                        <i class="arrow fas fa-angle-right"></i>

                        <ul class="sub-menu">
                            @can('role-add')
                            <li><a href="{{url("admin/role/add")}}">Thêm mới</a></li>
                            @endcan
                            <li><a href="{{url("admin/role/list")}}">Danh sách</a></li>

                            <li><a href="{{url("admin/permission/list")}}">Tạo vai trò</a></li>
                        </ul>
                    </li>
                    @endcan
                    @endcan

                </ul>
            </div>
            <div id="wp-content">
                @yield('content')


            </div>
        </div>
        {{-- <input type="checkbox" name="" class="switch-toggle" id="light-dark"> --}}



    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="{{asset('js/app.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="{{asset('admin/format_currentcy/format_currentcy.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#keyword').on('keyup', function(event) {
                event.preventDefault();
                /* Act on the event */
                var keyword = $(this).val().toLowerCase();
                $('#myTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(keyword) > -1);

                });

            });



        });
    </script>

    @yield('js')
</body>


</html>