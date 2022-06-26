@if($menu_item->menu_children->count() > 0)
    <ul role="menu" class="sub-menu">
        @foreach ( $menu_item->menu_children as $menu_child_item)

        @if($menu_child_item->menu_children->count() > 0)
        <li class="dropdown"><a href="#">{{$menu_child_item->name}}<i class="fa fa-angle-down"></i></a>
            @include('components.child_menu',['menu_item'=>$menu_child_item])

        @else   
        <li class=""><a href="">{{$menu_child_item->name}}</a>
        @endif
        
         </li>
        @endforeach
   
    </ul>
    
      
@endif