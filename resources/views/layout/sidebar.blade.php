<div class="deznav deznav-sifec-gradient">
    <div class="deznav-sifec-mesh" aria-hidden="true">
        {{-- Même recette que .db-header : halos blanc + jaune SIFEC, puis cercles verts --}}
        <span class="deznav-sifec-orb deznav-sifec-orb--light"></span>
        <span class="deznav-sifec-orb deznav-sifec-orb--jaune"></span>
        <span class="deznav-sifec-blob deznav-sifec-blob--1"></span>
        <span class="deznav-sifec-blob deznav-sifec-blob--2"></span>
        <span class="deznav-sifec-blob deznav-sifec-blob--3"></span>
    </div>
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            @isset($sidebarMenu)
                @include('layout.partials.sidebar-menu-branch', ['nodes' => $sidebarMenu, 'depth' => 0])
            @endisset
        </ul>
        <div class="copyright" style="position: fixed; bottom:0px; margin-left: 0px">
            <p style="font-size: 10px; text-align:center">SYSTEME INTEGRE DES FAITS D'ETAT-CIVIL(SIFEC)</p>
        </div>
    </div>
</div>
