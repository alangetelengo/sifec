@extends("layout.app")
@section("titre")
    Profile {{ $user->personne->nom.' '.$user->personne->prenom }}
@endsection
@section("corps")

<!-- row -->

<div class="row">

    <div class="col-xl-12">

        <div class="card">
            <div class="card-body">
                <div class="profile-tab">
                    <div class="custom-tab-1">
                        <ul class="nav nav-tabs">

                            <li class="nav-item"><a href="#about-me" data-bs-toggle="tab" class="nav-link active show">Informations Personnelles</a>
                            </li>
                            <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link">Modifier le compte</a>
                            </li>
                            <li class="nav-item"><a href="#signature-settings" data-bs-toggle="tab" class="nav-link">Signature</a>
                            </li>
                            <li class="nav-item"><a href="#permission-settings" data-bs-toggle="tab" class="nav-link">Ajouter une permission</a>
                        </ul>
                        <div class="tab-content">

                            <div id="about-me" class="tab-pane fade active show">

                                <div class="profile-personal-info">
                                    <h4 class="text-primary mb-4"></h4>
                                    <div class="row mb-2">
                                        <div class="col-sm-3 col-5">
                                            <h5 class="f-w-500">Nom <span class="pull-end">: </span></h5>
                                        </div>
                                        <div class="col-sm-6 col-7">{{ $user->personne->nom. ' '.$user->personne->prenom }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-3 col-5">
                                            <h5 class="f-w-500">Email <span class="pull-end">: </span>
                                            </h5>
                                        </div>
                                        <div class="col-sm-6 col-7"><span>{{ $user->email }} </span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-3 col-5">
                                            <h5 class="f-w-500">Pseudo <span class="pull-end">: </span>
                                            </h5>
                                        </div>
                                        <div class="col-sm-6 col-7"><span>{{ $user->pseudo }} </span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-3 col-5">
                                            <h5 class="f-w-500">Institution <span class="pull-end">: </span></h5>
                                        </div>
                                        <div class="col-sm-9 col-7"><span>{{ $user->affectationActive()->institution->lib_institution }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-3 col-5">
                                            <h5 class="f-w-500">Fonction <span class="pull-end">:</span></h5>
                                        </div>
                                        <div class="col-sm-9 col-7"><span>{{ $user->affectationActive()->fonction->lib_fonction }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-about-me">
                                    <div class="pt-4 border-bottom-1 pb-3">
                                        <h4 class="text-primary">Permissions</h4>
                                        <p class="mb-2">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence was created for the bliss of souls like mine.I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p>
                                        <p>A collection of textile samples lay spread out on the table - Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame.</p>
                                    </div>
                                </div>
                            </div>
                            <div id="profile-settings" class="tab-pane fade">
                                <div class="pt-3">
                                    <div class="settings-form">
                                        <h4 class="text-primary">Compte</h4>
                                        <form method="POST" action="{{ route("dashboard.update",$user->code_user) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" placeholder="Email" name="email" class="form-control" value="{{ $user->email }}">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Pseudo</label>
                                                    <input type="text" name="pseudo" value="{{ $user->pseudo }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Ancien mot de passe</label>
                                                    <input type="password" name="password"  class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Nouveau mot de passe</label>
                                                    <input type="password" name="new_password"  class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Confirmer mot de passe</label>
                                                    <input type="password" name="confirm_password"  class="form-control">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-control form-control">
                                                        <option value="1" {{ $user->status == "1" ? "selected" : "" }}>Activer</option>
                                                        <option value="0"  {{ $user->status == "0" ? "selected" : "" }}>Désactiver</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-primary" type="submit">Modifier</button>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="signature-settings" class="tab-pane fade">
                                <div class="profile-interest">
                                    <h5 class="text-primary d-inline">Signature</h5>
                                    <div class="row mt-4 sp4" id="lightgallery">
                                        @if($user->personne->signature == null)
                                            <form action="{{ route("utilisateur.signature",$user->code_user) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method("PUT")
                                                <div class="row">
                                                    <div class="mb-2 col-md-6">
                                                        <label for="">Importer la signature</label>
                                                        <input type="file" class="form-control" name="signature" id="signature">
                                                        @error("signature")
                                                        <div class="feed-back">
                                                            <span class="text-error">{{ $message }}</span>
                                                        </div>
                                                        @enderror
                                                    </div>


                                                </div>

                                                <input type="submit" value="Importer la signature" class="btn btn-primary btn-sm">
                                            </form>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div id="permission-settings" class="tab-pane fade">
                                <div class="profile-interest">
                                    <h5 class="text-primary d-inline">Ajouter une permission</h5>
                                    <form action="{{ route("utilisateur.assigner.permission",$user->code_user) }}" method="post">
                                        @csrf
                                        <div class="row mt-4 sp4">
                                            <div class="mb-2 col-md-12">
                                                <label for="">Autorisé à </label>
                                                {{-- afficher en large --}}

                                                <select name="permission[]" id="" class="form-control" multiple width="100%" style="height: 200px;">
                                                    @foreach ($permissions as $permission)
                                                        <option value="{{ $permission->code_fonctionnalite }}">{{ $permission->lib_fonctionnalite }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2 col-md-6">
                                                <input type="submit" value="Ajouter la permission" class="btn btn-primary btn-sm mt-4">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>
@endsection
