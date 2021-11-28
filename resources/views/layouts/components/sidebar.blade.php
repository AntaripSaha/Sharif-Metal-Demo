@php
if(Auth::user()->isSuperAdmin()):
$menu=display_menu('Super admin',true);
elseif(Auth::user()->isOfficeAdmin()):
$menu=display_menu('admin',false);
elseif(Auth::user()->isSeller()):
$menu=display_menu('seller',false);
else:
$menu=display_menu('user',false);
endif;
@endphp

<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-2 sidebar-dark-orange">
    <!-- Brand Logo -->
    <a href="{{route('users.index')}}" class="brand-link text-center">
{{--        <img src="{{asset('img/logo.png')}}" alt="logo" />--}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">
                @foreach($menu->parent_items->sortBy('order') as $k=>$item)
                @php 
                    $route=(!empty($item->route))?(route($item->route)):''; 
                @endphp
                @if(Auth::user()->can('browse', app($item->model_name))|| Auth::user()->isSuperAdmin() ||
                is_null($item->model_name) || auth('web')->user()->role->id == 18 || auth('web')->user()->role->id == 19 || auth('web')->user()->role->id == 21 )
                
                    @if(!$item->children->isEmpty() )
                    
                        <!-- FOR WAREHOUSE -->
                        @if( auth('web')->user()->role->id == 20 ) 
                            @if( $item->id == 67 || $item->id == 71 || $item->id == 79 )
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas {{$item->icon_class}}"></i>
                                    <p> @lang('menu.'.$item->title) <i class="right fas fa-angle-left"></i> </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    
                                    <!-- only chalan role user get two option -->
                                    @foreach($item->children as $child)
                                    @php $route=(!empty($child->route))?(route($child->route)):''; @endphp
                                    
                                        @if($child->key ==null|| Auth::user()->isSuperAdmin() || Auth::user()->can($child->key,
                                        app($child->model_name)))
                                        
                                            <li class="nav-item">
                                                <a href="{{$route}}" class="nav-link {{ activeMenu($route)}}">
                                                    <p>{{$child->title}}
                                                    </p>
                                                </a>
                                            </li>
                                        
                                        @endif
                                    @endforeach
                                    
                                </ul>
                            </li>
                            @endif
                        
                        <!-- FOR CHALAN -->
                        @elseif( auth('web')->user()->role->id == 18 )  
                            @if( $item->id == 58 || $item->id == 71 )
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas {{$item->icon_class}}"></i>
                                    <p> @lang('menu.'.$item->title) <i class="right fas fa-angle-left"></i> </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    
                                    <!-- only chalan role user get two option -->
                                    @foreach($item->children as $child)
                                        @php $route=(!empty($child->route))?(route($child->route)):''; @endphp
                                        
                                            @if( $child->id == 168 || $child->id == 74 || $child->id == 80 || $child->id == 160 || $child->id == 146 || $child->id == 169 )
                                            <li class="nav-item">
                                                <a href="{{$route}}" class="nav-link {{ activeMenu($route)}}">
                                                    <p>{{$child->title}} 
                                                    </p>
                                                </a>
                                            </li>
                                            @endif
                                        
                                    @endforeach
                                    
                                </ul>
                            </li>
                            @endif
                            
                        <!-- FOR BILL -->
                        @elseif( auth('web')->user()->role->id == 19 )  
                            
                            @if( $item->id == 58 || $item->id == 148 )
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas {{$item->icon_class}}"></i>
                                    <p> @lang('menu.'.$item->title) <i class="right fas fa-angle-left"></i> </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    
                                    <!-- only chalan role user get two option -->
                                    @foreach($item->children as $child)
                                        @php $route=(!empty($child->route))?(route($child->route)):''; @endphp
                                        
                                            @if( $child->id == 168 || $child->id == 169 )
                                            <li class="nav-item">
                                                <a href="{{$route}}" class="nav-link {{ activeMenu($route)}}">
                                                    <p>{{$child->title}} 
                                                    </p>
                                                </a>
                                            </li>
                                            @endif
                                        
                                    @endforeach
                                    
                                </ul>
                            </li>
                            @endif
                            
                        <!-- FOR STOCK -->
                        @elseif( auth('web')->user()->role->id == 21 )  
                            @if( $item->id == 58 || $item->id == 71 || $item->id == 79 || $item->id == 148 )
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas {{$item->icon_class}}"></i>
                                    <p> @lang('menu.'.$item->title) <i class="right fas fa-angle-left"></i> </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    
                                    <!-- only chalan role user get two option -->
                                    @foreach($item->children as $child)
                                        @php $route=(!empty($child->route))?(route($child->route)):''; @endphp
                                        
                                            @if( $child->id == 168 || $child->id == 74 || $child->id == 80 || $child->id == 160 || $child->id == 146 || $child->id == 169 )
                                            <li class="nav-item">
                                                <a href="{{$route}}" class="nav-link {{ activeMenu($route)}}">
                                                    <p>{{$child->title}} 
                                                    </p>
                                                </a>
                                            </li>
                                            @endif
                                        
                                    @endforeach
                                    
                                </ul>
                            </li>
                            @endif
                         
                        @else
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas {{$item->icon_class}}"></i>
                                    <p> @lang('menu.'.$item->title) <i class="right fas fa-angle-left"></i> </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <!-- only chalan role user get two option -->
                                    @foreach($item->children->where("is_active",true) as $child)
                                    @php $route=(!empty($child->route))?(route($child->route)):''; @endphp
                                    
                                        @if($child->key ==null|| Auth::user()->isSuperAdmin() || Auth::user()->can($child->key,
                                        app($child->model_name)))
                                        
                                            @if( auth('web')->user()->role->id == 19 )
                                                @if( $child->id == 168 )
                                                <li class="nav-item">
                                                    <a href="{{$route}}" class="nav-link {{ activeMenu($route)}}">
                                                        <p>{{$child->title}} 
                                                        </p> 
                                                    </a>
                                                </li>
                                                @endif
                                            @else
                                                <li class="nav-item">
                                                    <a href="{{$route}}" class="nav-link {{ activeMenu($route)}}">
                                                        <p>{{$child->title}}   
                                                        </p>
                                                    </a>
                                                </li>
                                            @endif
                                        
                                        @endif
                                    @endforeach
                                    
                                </ul>
                            </li>
                        @endif
                    
                    @else
                        <li class="nav-item {{ activeMenu($route)}}">
                            <a href="{{$route}}" class="nav-link">
                                <i class="nav-icon fas {{$item->icon_class}}"></i>
                                <p>@lang('menu.'.$item->title)</p>
                            </a>
                        </li>
                    @endif
                @endif
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

