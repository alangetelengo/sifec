{{--
  Menu principal : aligné sur .db-header (charte SIFEC)
  — Dégradé 135° #006B31 → #009E49 → #21B931
  — Halos décoratifs blanc + jaune (#FBDE4A)
  — Cercles verts semi-transparents (#2eb85c) superposés à droite
--}}
<style id="deznav-sifec-gradient-theme">
.deznav.deznav-sifec-gradient {
    /* Identique au bandeau tableau de bord (.db-header) */
    background-color: #009E49 !important;
    background-image: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%) !important;
    box-shadow: 4px 0 32px rgba(0, 158, 73, 0.35);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    overflow: hidden;
}
/* Calque décoratif (tout le motif sous le menu) */
.deznav.deznav-sifec-gradient .deznav-sifec-mesh {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
/* Halos = mêmes proportions que .db-header::before / ::after */
.deznav.deznav-sifec-gradient .deznav-sifec-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.deznav.deznav-sifec-gradient .deznav-sifec-orb--light {
    width: 220px;
    height: 220px;
    background: rgba(255, 255, 255, 0.06);
    top: -70px;
    right: -50px;
    z-index: 1;
}
.deznav.deznav-sifec-gradient .deznav-sifec-orb--jaune {
    width: 140px;
    height: 140px;
    background: rgba(251, 222, 74, 0.08);
    bottom: 18%;
    right: -42px;
    z-index: 1;
}
/* Cercles verts (#2eb85c) — au-dessus des halos, comme sur la maquette */
.deznav.deznav-sifec-gradient .deznav-sifec-blob {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 2;
    background: rgba(46, 184, 92, 0.38);
    box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
}
.deznav.deznav-sifec-gradient .deznav-sifec-blob--1 {
    width: 240px;
    height: 240px;
    top: 3%;
    right: -75px;
    background: rgba(46, 184, 92, 0.44);
}
.deznav.deznav-sifec-gradient .deznav-sifec-blob--2 {
    width: 200px;
    height: 200px;
    top: 14%;
    right: -40px;
    background: rgba(54, 198, 102, 0.36);
}
.deznav.deznav-sifec-gradient .deznav-sifec-blob--3 {
    width: 175px;
    height: 175px;
    top: 26%;
    right: -15px;
    background: rgba(46, 184, 92, 0.3);
}
.deznav.deznav-sifec-gradient > .deznav-scroll {
    position: relative;
    z-index: 2;
    background: transparent !important;
}
/* Les <li> actifs ne doivent pas avoir de fond plein (règles globales .mm-active) — laisser voir dégradé + cercles */
.deznav.deznav-sifec-gradient .metismenu > li.mm-active {
    background: transparent !important;
    background-color: transparent !important;
}
/* Liens : style proche des badges du bandeau */
.deznav.deznav-sifec-gradient .metismenu > li > a {
    color: rgba(255, 255, 255, 0.96) !important;
}
.deznav.deznav-sifec-gradient .metismenu a {
    color: rgba(255, 255, 255, 0.9) !important;
}
.deznav.deznav-sifec-gradient .metismenu ul {
    background: rgba(0, 0, 0, 0.14) !important;
    margin-right: 6px;
    border-radius: 0 10px 10px 0;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
.deznav.deznav-sifec-gradient .metismenu ul a:hover,
.deznav.deznav-sifec-gradient .metismenu ul a:focus,
.deznav.deznav-sifec-gradient .metismenu ul a.mm-active {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.12);
}
.deznav.deznav-sifec-gradient .metismenu > li:hover > a,
.deznav.deznav-sifec-gradient .metismenu > li:focus > a {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.12) !important;
    border-radius: 0 10px 10px 0;
}
.deznav.deznav-sifec-gradient .metismenu > li:hover > a g [fill],
.deznav.deznav-sifec-gradient .metismenu > li:focus > a g [fill] {
    fill: #fff !important;
}
.deznav.deznav-sifec-gradient .metismenu > li.mm-active > a {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.18) !important;
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 0 12px 12px 0;
    box-shadow: inset 3px 0 0 #FBDE4A;
}
.deznav.deznav-sifec-gradient .metismenu > li.mm-active > a g [fill] {
    fill: #fff !important;
}
.deznav.deznav-sifec-gradient .metismenu > li > a svg {
    color: #fff !important;
}
.deznav.deznav-sifec-gradient .metismenu > li > a > i {
    color: #fff !important;
    background: rgba(255, 255, 255, 0.16) !important;
    border: 1px solid rgba(255, 255, 255, 0.26) !important;
}
.deznav.deznav-sifec-gradient .metismenu .has-arrow:after {
    border-color: rgba(255, 255, 255, 0.78) !important;
}
[data-sidebar-style=full] .deznav.deznav-sifec-gradient .metismenu ul a:before {
    background: rgba(255, 255, 255, 0.5) !important;
}
.deznav.deznav-sifec-gradient .copyright p {
    color: rgba(255, 255, 255, 0.52) !important;
}
@media only screen and (max-width: 1400px) {
    .deznav.deznav-sifec-gradient .deznav-sifec-orb--light {
        width: 190px;
        height: 190px;
        top: -55px;
        right: -45px;
    }
    .deznav.deznav-sifec-gradient .deznav-sifec-orb--jaune {
        width: 120px;
        height: 120px;
        right: -36px;
    }
    .deznav.deznav-sifec-gradient .deznav-sifec-blob--1 {
        width: 200px;
        height: 200px;
        right: -65px;
    }
    .deznav.deznav-sifec-gradient .deznav-sifec-blob--2 {
        width: 170px;
        height: 170px;
        right: -35px;
    }
    .deznav.deznav-sifec-gradient .deznav-sifec-blob--3 {
        width: 145px;
        height: 145px;
        right: -10px;
    }
}

/* -------------------------------------------------------------------------
   Modal OTP paraphe registre : même dégradé + mini-motif que le menu SIFEC
   (évite texte blanc sur fond clair des .badge Bootstrap du thème)
   ------------------------------------------------------------------------- */
.sifec-otp-badge {
    position: relative;
    display: inline-flex;
    align-items: center;
    min-height: 2.35rem;
    padding: 0.4rem 0.95rem 0.4rem 0.85rem;
    border-radius: 0.5rem;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.22);
    box-shadow: 0 2px 10px rgba(0, 107, 49, 0.28);
    background-color: #009E49;
    background-image: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%);
    color: #fff !important;
    font-size: 0.8125rem;
    font-weight: 600;
    line-height: 1.35;
    vertical-align: middle;
}
.sifec-otp-badge__mesh {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.sifec-otp-badge__orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.sifec-otp-badge__orb--light {
    width: 72px;
    height: 72px;
    background: rgba(255, 255, 255, 0.08);
    top: -28px;
    right: -18px;
    z-index: 1;
}
.sifec-otp-badge__orb--jaune {
    width: 48px;
    height: 48px;
    background: rgba(251, 222, 74, 0.12);
    bottom: -14px;
    left: -12px;
    z-index: 1;
}
.sifec-otp-badge__blob {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 2;
    background: rgba(46, 184, 92, 0.42);
    box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.06) inset;
}
.sifec-otp-badge__blob--1 {
    width: 56px;
    height: 56px;
    top: -8px;
    right: -12px;
}
.sifec-otp-badge__label {
    position: relative;
    z-index: 3;
    color: #fff !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
}
.sifec-otp-badge__label #otp-lockout-countdown {
    color: #fff !important;
    font-weight: 700;
    letter-spacing: 0.04em;
}
/* Jauge OTP modal paraphe : piste neutre, remplissage dégradé SIFEC (hors bloc vert) */
#modal-registre-paraphage .sifec-otp-meter--surface {
    width: 100%;
}
#modal-registre-paraphage .sifec-otp-meter--surface .sifec-otp-meter__track {
    position: relative;
    height: 24px;
    border-radius: 999px;
    padding: 4px;
    background: #e9ecef;
    border: 1px solid #dee2e6;
    box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.08);
}
#modal-registre-paraphage .sifec-otp-meter--surface .sifec-otp-meter__fill {
    height: 100%;
    min-width: 8px;
    border-radius: 999px;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    background: linear-gradient(105deg, #006B31 0%, #009E49 40%, #21B931 75%, #4dd865 100%) !important;
    background-size: 200% 100% !important;
    animation: sifecOtpMeterShineGreen 3s ease-in-out infinite;
    box-shadow: 0 2px 10px rgba(0, 158, 73, 0.4);
    transition: width 0.35s linear;
}
#modal-registre-paraphage .sifec-otp-meter--surface .sifec-otp-meter__fill.sifec-otp-meter__fill--urgent {
    background: linear-gradient(105deg, #bf360c 0%, #ff9800 45%, #ffb74d 100%) !important;
    animation: sifecOtpMeterUrgentPulse 0.65s ease-in-out infinite, sifecOtpMeterShineGreen 1.1s linear infinite;
    box-shadow: 0 2px 12px rgba(255, 152, 0, 0.55);
}
@keyframes sifecOtpMeterShineGreen {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
@keyframes sifecOtpMeterUrgentPulse {
    0%, 100% { filter: brightness(1); }
    50% { filter: brightness(1.1); }
}
@media (prefers-reduced-motion: reduce) {
    #modal-registre-paraphage .sifec-otp-meter--surface .sifec-otp-meter__fill {
        animation: none !important;
    }
    #modal-registre-paraphage .sifec-otp-meter--surface .sifec-otp-meter__fill.sifec-otp-meter__fill--urgent {
        animation: none !important;
    }
}
#modal-registre-paraphage .sifec-otp-attempts-count {
    color: #006B31;
    font-size: 1.1rem;
    letter-spacing: 0.02em;
}
/* Variante compacte (ex. temporisation dans une alerte) */
.sifec-otp-badge--compact {
    min-height: auto;
    padding: 0.2rem 0.55rem 0.2rem 0.5rem;
    font-size: 0.8rem;
}
.sifec-otp-badge--compact .sifec-otp-badge__orb--light {
    width: 40px;
    height: 40px;
    top: -14px;
    right: -10px;
}
.sifec-otp-badge--compact .sifec-otp-badge__blob--1 {
    width: 32px;
    height: 32px;
    top: -6px;
    right: -8px;
}
</style>
