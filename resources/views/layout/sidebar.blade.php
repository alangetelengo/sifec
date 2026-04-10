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
            <li>
                <a href="{{ url('/') }}">
                    <i class="flaticon-381-networking"></i><span class="nav-text">ACCUEIL</span>
                </a>
            </li>
            @can("module.menus.referentiel")
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-edit"></i>
                    <span class="nav-text">REFERENTIEL</span>
                </a>
                <ul aria-expanded="false">
                    <li>
                        <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">Territoire</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('typelocalite.index') }}">Types de localité</a></li>
                            <li><a href="{{ route('localite.index') }}">Localités</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">Institutions</a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('typeCategorieInstitution.index') }}">Type catégorie</a></li>
                            <li><a href="{{ route('typeInstitution.index') }}">Type institution</a></li>
                            <li><a href="{{ route('institution.index') }}">Institutions</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route("profession.index") }}">Profession</a>
                    </li>
                    <li>
                        <a href="{{ route("causedeces.index") }}">Cause de décès</a>
                    </li>
                    <li>
                        <a href="{{ route("religion.index") }}">Religion</a>
                    </li>
                    <li>
                        <a href="{{ route("nationalite.index") }}">Nationalité</a>
                    </li>
                </ul>
            </li>
            @endcan
            @can("module.menus.formationSanitaire")
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text"> SANTE </span>
                </a>
                <ul aria-expanded="false">
                    @can("module.ActeNaissance.declarationNaissance.create")
                    <li>
                        <a href="{{ route('declarationNaissance.index') }}">Certificat de naissance</a>
                    </li>

                    @endcan
                    @can("module.acteDeces.declarationacteDeces.create")
                    <li>
                        <a href="{{ route('declarationDeces.index') }}">Certificat de décès</a>
                    </li>
                    @endcan
                    {{-- @can("module.ActeNaissance.declarationNaissance.create.EnfantAbandonne") --}}
                    <li>
                        <a href="{{ route('declarationNaissance.as') }}">Enfant abandonné (certificat)</a>
                    </li>
                    {{-- @endcan --}}
                </ul>
            </li>
            @endcan
            @can("module.menus.centreHygiene")
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text">CENTRE D'HYGIENE</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('centreHygiene.index') }}">Certificat de constatation de décès</a></li>
                </ul>
            </li>
            @endcan
            @can("module.menus.pompesFunebres")

            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text">POMPES FUNEBRES</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('registre.index') }}">Registre</a></li>
                    <li><a href="{{ route('acteDeces.index') }}">Production acte</a></li>
                    <li><a href="{{ route('statistiquesDeces.listedece') }}">Repertoire alphabétique</a></li>
                    <li>
                        <a href="{{route('certificatTranscriptionDeces.index')}}">Certificat de transcription</a>
                        <a href="{{route('certificatNonInscriptionDeces.index')}}">Certificat de non inscription de décès</a>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <span class="nav-text">Réquisition </span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{route('RequisitionNonInscriptionDeces.index')}}">Aux fins de déclaration tardive décès</a></li>
                            <li><a href="{{route('RequisitionTranscriptionDeces.index')}}">Aux fins de transcription</a></li>
                        </ul>
                    </li>
                    <li><a href="{{route("declarationTardiveDeces.index")}}">Déclaration tardive</a></li>
                </ul>
            </li>
            @endcan
            @can('module.menus.mairie_centrale')
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text"> ETAT CIVIL</span>
                </a>
                <ul aria-expanded="false">
                    <li>
                        <a href="{{ route('registre.index') }}">Registre</a>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <span class="nav-text">Naissance</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('certificatTranscription.index') }}">Fiche de transcription de l'acte</a></li>
                            <li><a href="{{ route('acteNaissance.index') }}">Production acte</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false"><span class="nav-text">Mariage </span>
                        </a>
                        <ul aria-expanded="false">
                            <li>
                                <a href="{{route('declarationMariage.index')}}">Formulaire type</a>
                            </li>
                            <li><a href="{{route('etatMariage.requisition')}}">Réquisition</a></li>
                            <li>
                                <a href="{{route('publicationMariage.index')}}">Publication</a>
                            </li>
                            <li>
                                <a href="#">Certificat de la dot</a>
                            </li>
                            <li><a href="{{route('acteMariage.index')}}">Production acte</a></li>
                            <li>
                                <a href="{{ route('acteMariage.repertoire') }}">Répertoire alphabétique</a>
                            </li>
                            <li>
                                <a href="{{route('etatMariage.livretFamilles')}}">Consultation livret de famille</a>
                            </li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <span class="nav-text">Divorce</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="#">Déclaration divorce</a></li>
                            <li><a href="#">Production acte</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('retrait.index') }}">Consultation acte retiré</a></li>
                    <li><a href="{{ route('rectification.index') }}">Rectification d'acte </a></li>
                </ul>
            </li>
            @endcan


            @can("module.menus.cec")
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text"> ETAT CIVIL</span>
                </a>
                <ul aria-expanded="false">
                    @if(Auth::check() && Auth::user()->affectationActive() && Auth::user()->affectationActive()->fonction->code_fonction != "FONC_0017")
                    <li>
                        <a href="{{ route('registre.index') }}">Registre</a>
                    </li>
                    @endif

                    @can("module.menus.naissance")
                    <li>
                        <a href="#">Naissance</a>
                        <ul aria-expanded="false">

                            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                                <span class="nav-text">Déclaration </span>
                                </a>
                                <ul aria-expanded="false">
                                    <li>
                                        <a href="{{route("declarationNaissance.index")}}">Déclaration de naissance</a>
                                    </li>
                                </ul>
                            </li>

                            <li><a href="{{ route('declarationNaissance.enfantTrouve') }}">Enfant trouvé</a></li>
                            <li><a href="javascript:void(0)" class="chercheacte">Paiement document</a></li>
                            <li><a href="{{ route('acteNaissance.index') }}">Production acte</a></li>
                            <li>
                                <a href="#">Certificat</a>
                                <ul aria-expanded="false">
                                    <li><a href="{{route("certificatNonInscription.index")}}">Certificat de non inscription</a></li>
                                    <li><a href="{{ route('certificatDestruction.index') }}">Certificat de destruction</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    {{-- @can('module.acteMariage.menu') --}}
                    @can("module.menus.mariage")
                    <li>
                        <a href="#">Mariage</a>
                        <ul aria-expanded="false">
                            <li>
                                <a href="{{route('declarationMariage.index')}}">Formulaire type</a>
                            </li>
                            <li><a href="{{route('etatMariage.requisition')}}">Réquisition</a></li>
							<li>
                                <a href="{{route('publicationMariage.index')}}">Publication</a>
                            </li>
                            <li>
                                <a href="#">Certificat de la dot</a>
                            </li>
                            <li><a href="{{route('acteMariage.index')}}">Production acte</a></li>
                            <li>
                                <a href="{{ route('acteMariage.repertoire') }}">Répertoire alphabétique</a>
                            </li>
                            <li>
                                <a href="{{route('etatMariage.livretFamilles')}}">Consultation livret de famille</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @if(Auth::check() && Auth::user()->affectationActive() && Auth::user()->affectationActive()->fonction->code_fonction != "FONC_0017")
                        <li><a href="{{ route('documents.requisitions') }}">Réquisitions importées</a></li>
                        <li><a href="{{ route('documents.jugements') }}">Jugements importés</a></li>
                        <li><a href="{{ route('retrait.index') }}">Actes retirés</a></li>
                        <li><a href="{{ route('rectification.index') }}">Rectifications d'actes</a></li>

                    @endif
                </ul>
            </li>
            @endcan
            @can('module.menus.ambassade')
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text">AMBASSADE</span>
                </a>
                <ul aria-expanded="false">
                    <ul aria-expanded="false">
                        <li><a href="{{ route('registre.index') }}">Registre</a></li>
                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <span class="nav-text">Naissance </span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('declarationNaissance.index') }}">Certificat de naissance</a></li>
                                <li><a href="{{ route('acteNaissance.index') }}">Production acte</a></li>
                            </ul>
                        </li>
                        <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                            <span class="nav-text">Décès </span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('declarationDeces.autorisationtransfert') }}">Autorisation de transfert</a></li>
                            </ul>
                        </li>
                    </ul>
                </ul>
            </li>
            @endcan
            @can("module.menus.tribunal")
            <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                <i class="flaticon-381-layer-1"></i>
                <span class="nav-text">TRIBUNAL</span>
                </a>
                <ul aria-expanded="false">

                    <li>
                        <a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                            <i class="flaticon-381-layer-1"></i>
                            <span class="nav-text">Dossiers reçus</span>
                        </a>
                        <ul aria-expanded="false">
                            <li>
                                <a href="{{ route('tribunal.document.index') }}">
                                    <span class="nav-text">Certificats</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tribunal.document.rectification') }}">
                                    <span class="nav-text">Rectifications</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li><a href="{{ route('tribunal.document.historique') }}">
                        <span class="nav-text">Historique des imports</span>
                    </a></li>
                    <li><a href="{{ route('tribunal.document.envoyes') }}">
                        <span class="nav-text">Documents envoyés</span>
                    </a></li>
                    <li><a href="{{ route('tribunal.document.stats') }}">
                        <span class="nav-text">Statistiques</span>
                    </a></li>
                    @can("module.fonctionnalites.parapher")
                    <li><a href="{{ route('registre.tribunal') }}">
                        <span class="nav-text">Registre</span>
                    </a></li>
                    @endcan
                    @if(Auth::check() && Auth::user()->affectationActive() && Auth::user()->affectationActive()->fonction->code_fonction == "FONC_0018")
                        <li><a href="{{ route('declarationDeces.index') }}">Certificat de décès</a></li>
                    @endif
                </ul>
            </li>
            @endcan
            @can("module.menus.administration")
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-settings-2"></i>
                    <span class="nav-text">ADMINISTRATION</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route("utilisateur.index") }}">Utilisateur</a></li>
                    <li><a href="{{ route('utilisateur.audit-journal') }}">Journal d'audit utilisateurs</a></li>
                    <li><a href="{{ route('fonction.index') }}">Fonction</a></li>
                    <li><a href="{{ route('module.index') }}">Module</a></li>
                    <li><a href="{{ route('fonctionnalite.index') }}">Fonctionalité</a></li>
                    <li><a href="{{ route('appareil.index') }}">Appareils</a></li>
                    <li><a href="{{ route('two-factor.index') }}">Double Authentification</a></li>
                </ul>
            </li>
            @endcan
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-controls-3"></i>
                <span class="nav-text">STATISTIQUE</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ url('carteducongo') }}">Globale</a></li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <span class="nav-text">Naissance </span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('statistiquesNaissance.sexeDeclaration') }}">Déclaration de naissance</a></li>
                            <li><a href="{{route('statistiquesNaissance.sexeNaissance')}}">Acte de naissance</a></li>
                            <li><a href="{{route('dashboard.statgenredep')}}">Naissances par genre et département</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <span class="nav-text">Décès </span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{route("statistiquesDeces.age")}}">Décès par tranches d'âges</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="copyright" style="position: fixed; bottom:0px; margin-left: 0px">
            <p style="font-size: 10px; text-align:center">SYSTEME INTEGRE DES FAITS D'ETAT-CIVIL(SIFEC)</p>
        </div>
    </div>
</div>
