<form name="contactUsForm" id="contactUsForm"  method="post" action="javascript:void(0)" class="validation-wizard wizard-circle">
    <!-- Step 1 -->
    <h6>Enfant</h6>
    <div class="d-none">
        <input type="text" value="DECLARATION DE NAISSANCE" id="type_declaration">
    </div>
    <section>
        <div class="ligne">
               <h4>INFORMATIONS SUR L'IDENTITE</h4>
           </div>
       <div class="row">

           <div class="mb-2 col-md-4">
               <label class="form-label">Nom(s) enfant <span class="text-danger">*</span></label>
               <input type="text" name="nom_enfant" value="{{ $dummy }}"  class="form-control required  @error('nom_enfant') is-invalid @enderror"  placeholder="Nom enfant" readonly id="nom_enfant">
           </div>

           <div class="mb-2 col-md-4">
               <label class="form-label">Prénom(s) enfant</label>
               <input type="text" class="form-control" value="{{ $dummy }}" placeholder="Prénom enfant" id="prenom_enfant" onkeyup="verif_lettre(this);" style="text-transform: capitalize;">
           </div>

           <div class="mb-2 col-md-4">
               <label class="form-label">Sexe <span class="text-danger">*</span></label>
               <select id="sexe_enfant" name="sexe_enfant" class="form-control required  @error('sexe_enfant') is-invalid @enderror">
                   <option value="" disabled selected>Selectionner</option>
                   <option value="M">Masculin</option>
                   <option value="F">Féminin</option>
               </select>
           </div>

       </div>
       <div class="ligne">
               <h4>INFORMATIONS SUR LA NAISSANCE</h4>
           </div>
       <div class="row">
           <div class="mb-2 col-md-3">
               <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
               <input type="date" name="date_naissance_enfant" class="form-control" id="date_naissance_enfant">
           </div>

           <div class="mb-2 col-md-3">
               <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
               <input type="text" class="form-control d-none" id="lieu_naissance_enfant" value="{{ Auth::user()->affectationActive()->institution->lieu->localiteParent->lib_localite }}">
               <select id="code_localite_enfant" class="form-control required" readonly>
                    <option value="{{ Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite }}">{{ Auth::user()->affectationActive()->institution->lieu->localiteParent->lib_localite }}</option>
               </select>
           </div>
           <div class="mb-2 col-md-3">
               <label class="form-label">Heure de naissance <span class="text-danger">*</span></label>
               <input type="time" value="00:00" name="heure_naissance_enfant" class="form-control"  id="heure_naissance_enfant">
           </div>
           @if(Auth::user()->affectationactive()->institution->TypeInstitution->code_type_institution == "TPINS_0002")
              <div class="mb-2 col-md-3">
               <label class="form-label">Formation sanitaire de naissance</label>
               <input type="text" value="{{ $dummy }}" id="formation_sanitaire_naissance" name="formation_sanitaire_naissance" class="form-control" readonly>
           </div>
           @endif
       </div>
       <div class="ligne">
               <h4>AUTRES INFORMATIONS</h4>
           </div>
       <div class="row">
           <div class="mb-2 col-md-4">
               <label class="form-label">Situation matrimoniale des parents<span class="text-danger">*</span></label>
               <select id="code_situation_matrimoniale" name="code_situation_matrimoniale" class="form-control required  @error('code_situation_matrimoniale') is-invalid @enderror" readonly>
                   <option value="SMAT_0008">{{ $dummy }}</option>
               </select>
           </div>
           <div class="mb-2 col-md-4">
               <label class="form-label">Nombre d'enfants (y compris le sujet)<span class="text-danger">*</span></label>
               <input type="text" value="1" name="nombre_enfants"class="form-control required  @error('nombre_enfants') is-invalid @enderror " placeholder="0" id="nombre_enfants" readonly>
           </div>
           <div class="mb-2 col-md-4">
               <label class="form-label">Date déclaration</label>
               <input type="text" value="{{ date("d-m-Y") }}" id="date_heure_declaration" name="date_heure_declaration" class="form-control" readonly>
           </div>
           <div class="mb-2 col-md-4 d-none">
               <label class="form-label">Statut <span class="text-danger">*</span></label>
               <select id="statut_personne_enfant" class="form-control">
                   <option selected value="VIVANT">Vivant</option>
                   <option value="DECEDE">Décédé</option>
               </select>
           </div>
       </div>
   </section>
    <!-- Step 2 -->
    @include("naissance::enfant-abandonne.parent")

</form>
