@php
    use App\Support\SifecSidebarMenuBuilder;
@endphp
@foreach($nodes as $item)
    <li>
        @if($item->children->isNotEmpty())
            <a href="{{ SifecSidebarMenuBuilder::href($item) }}"
               class="{{ trim(SifecSidebarMenuBuilder::anchorClasses($item, $depth).' '.($item->anchor_extra_classes ?? '')) }}"
               aria-expanded="false">
                @if($item->lib_icone)
                    <i class="{{ $item->lib_icone }}"></i>
                @endif
                @if($depth === 0 || $item->lib_icone)
                    <span class="nav-text">{{ $item->libelle }}</span>
                @else
                    {{ $item->libelle }}
                @endif
            </a>
            <ul aria-expanded="false">
                @include('layout.partials.sidebar-menu-branch', ['nodes' => $item->children, 'depth' => $depth + 1])
            </ul>
        @else
            <a href="{{ SifecSidebarMenuBuilder::href($item) }}"
               class="{{ trim((($item->anchor_class ?? SifecSidebarMenuBuilder::anchorClasses($item, $depth))).' '.($item->anchor_extra_classes ?? '')) }}">
                @if($depth === 0 && $item->lib_icone)
                    <i class="{{ $item->lib_icone }}"></i>
                    <span class="nav-text">{{ $item->libelle }}</span>
                @else
                    {{ $item->libelle }}
                @endif
            </a>
        @endif
    </li>
@endforeach
