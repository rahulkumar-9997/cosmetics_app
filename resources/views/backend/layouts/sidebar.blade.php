<!-- ========== App Menu Start ========== -->
<div class="main-nav">
   <!-- Sidebar Logo -->
   <div class="logo-box">
      <a href="{{ route('dashboard') }}" class="logo-dark">
         <img src="{{ asset('backend/assets/fav-icon.png') }}" class="logo-sm" alt="logo sm">
         <img src="{{ asset('backend/assets/fav-icon.png') }}" class="logo-lg" alt="logo dark">
      </a>
      <a href="{{ route('dashboard') }}" class="logo-light" style="text-align: center;">
         <img src="{{ asset('backend/assets/fav-icon.png') }}" class="logo-sm" alt="logo sm">
         <img src="{{ asset('backend/assets/logo.png') }}" style="width: 177px; height: 45px;" class="logo-lg" alt="logo light">
      </a>
   </div>
   <!-- Menu Toggle Button (sm-hover) -->
   <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
      <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
   </button>
   <div class="scrollbar" data-simplebar>
      <ul class="navbar-nav" id="navbar-nav">
         @php
         $colors = [
         '#e74c3c','#3498db','#2ecc71','#f1c40f','#9b59b6','#e67e22','#1abc9c',
         '#ff6b6b','#00b894','#0984e3','#fdcb6e','#6c5ce7','#d63031','#00cec9',
         '#fab1a0','#55efc4','#ffeaa7','#81ecec','#636e72','#fd79a8',
         ];
         @endphp

         @foreach($menus as $menu)
         @php $iconColor = $colors[array_rand($colors)]; @endphp

         @if($menu->children->isEmpty())
         <li class="nav-item">
            <a class="nav-link" href="{{ $menu->resolved_url ?? url($menu->url) }}">
               <span class="nav-icon">
                  @if(str_starts_with($menu->icon, 'ti'))
                  <i class="{{ $menu->icon }}" style="color: {{ $iconColor }};"></i>
                  @else
                  <iconify-icon icon="{{ $menu->icon }}" style="color: {{ $iconColor }};"></iconify-icon>
                  @endif
               </span>
               <span class="nav-text">{{ $menu->name }}</span>
            </a>
         </li>
         @else
         <li class="nav-item">
            <a class="nav-link menu-arrow" href="#sidebar_{{ $menu->id }}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebar_{{ $menu->id }}">
               <span class="nav-icon">
                  @if(str_starts_with($menu->icon, 'ti'))
                  <i class="{{ $menu->icon }}" style="color: {{ $iconColor }};"></i>
                  @else
                  <iconify-icon icon="{{ $menu->icon }}" style="color: {{ $iconColor }};"></iconify-icon>
                  @endif
               </span>
               <span class="nav-text">{{ $menu->name }}</span>
            </a>
            <div class="collapse" id="sidebar_{{ $menu->id }}">
               <ul class="nav sub-navbar-nav">
                  @foreach($menu->children as $child)
                  @php $childColor = $colors[array_rand($colors)]; @endphp
                  <li class="sub-nav-item">
                     <a class="sub-nav-link" href="{{ $child->resolved_url ?? url($child->url) }}">
                        @if(str_starts_with($child->icon, 'ti'))
                        <i class="{{ $child->icon }}" style="color: {{ $childColor }};"></i>
                        @else
                        <iconify-icon icon="{{ $child->icon ?? 'mdi:circle-small' }}" style="color: {{ $childColor }};"></iconify-icon>
                        @endif
                        {{ $child->name }}
                     </a>
                  </li>
                  @endforeach
               </ul>
            </div>
         </li>
         @endif
         @endforeach
      </ul>
   </div>
</div>
<!-- ========== App Menu End ========== -->