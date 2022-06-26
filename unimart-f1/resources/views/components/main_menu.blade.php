<div class="mainmenu pull-left ">
    <ul class="nav navbar-nav collapse navbar-collapse">

        @foreach ($menus as $menu_item )
        @if($menu_item->menu_children->count() > 0)
        <li class="dropdown"><a href="{{ $menu_item->url }}">{{$menu_item->name}}<i class=" fa fa-angle-down"></i></a>
            @else
        <li class=""><a href="{{ $menu_item->url }}">{{$menu_item->name}}</a>
            @endif

            @include('components.child_menu',['menu_item'=>$menu_item])
        </li>
        @endforeach


    </ul>
</div>