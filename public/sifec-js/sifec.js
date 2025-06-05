
function numerique(key) {
    return (key >= '0' && key <= '9') || key == '+' || key == '(' || key == ')' || key == '-' || key == 'ArrowLeft' || key == 'ArrowRight' || key == 'Delete' || key == 'Backspace';
}

$(document).ready(function(){
    /* ------------ Debut -------------
     formatage des nombres
    <div className="col-sm-4 formate">
        <input className="form-control separe text-end text-primary" type="text" name="montantP" onKeyDown="return numerique(event.key)" required>
    </div>*/

    $(".formate").on("keyup", ".separe", function (event) {
        this.value = this.value.replace(/ /g,'');
        var number = this.value;
        this.value = number.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    });

    /* -------------- Fin ----------------*/

    // Faire disparaitre les messages
    $("#fadeOut").fadeOut(7000);

    // lancer le modal automatique
    $("#automatique").modal();

    // Forcer la saisie en majuscule dans une input
    $('.majuscule').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });

    // Forcer la saisie en minuscule dans une input
    $('.minuscule').keyup(function(){
        $(this).val($(this).val().toLowerCase());
    });

    /* Forcer la saisie de la premiere lettre en majuscule de chaque mot */
    $('.majusculeplm').on('keyup', function (e) {
        var txt = $(this).val();
        $(this).val(txt.replace(/^(.)|\s(.)/g, function ($1) {
            return $1.toUpperCase();
        }));
    });

    /* Forcer la saisie de la premiere lettre en majuscule de la phrase */
    $('.majusculeplp').keyup(function() {
        var caps = jQuery('.majusculeplp').val();
        caps = caps.charAt(0).toUpperCase() + caps.slice(1);
        jQuery('.majusculeplp').val(caps);
    });

    // Cocher tous les checkbox
    $('#cocherTous').on('click', function(e) {
        if($(this).is(':checked',true)) {
            $(".cases").prop('checked', true);
        } else {
            $(".cases").prop('checked',false);
        }
    });

});

//----------------------------------------------------------------

// Griser un champ
function desactiverUnChamp(input){
    document.getElementById(""+input+"").disabled=true;
}

// Degriser un champ
function activerUnChamp(input){
    document.getElementById(""+input+"").disabled=false;
}

// Griser quelques les inputs
function desactiverQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").disabled=true;
    }
}

// degriser quelques les inputs
function activerQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").disabled=false;
    }
}

// Griser les inputs
function desactiverTousLesInput(){
    var elements = document.getElementsByTagName("input")
    for (var i = 0; i < elements.length; i++) {
       elements[i].disabled=true;
    }
}

// DeGriser les inputs
function activerTousLesInput(){
    var elements = document.getElementsByTagName("input")
    for (var i = 0; i < elements.length; i++) {
       elements[i].disabled=false;
    }
}

// griser les select
function desactiverTousLesSelect(){
    var elements = document.getElementsByTagName("select")
    for (var i = 0; i < elements.length; i++) {
       elements[i].disabled=true;
    }
}

// Griser les Radio
function desactiverTousLesRadio(){
    var elements = document.getElementsByTagName("radio")
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled=true;
    }
}

// Griser les Checkbox
function desactiverTousLesCheckbox(){
    var elements = document.getElementsByTagName("checkbox")
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled=true;
    }
}

// Griser les Textarea
function desactiverTousLesTextArea(){
    var elements = document.getElementsByTagName("textarea")
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled=true;
    }
}

//----------------------------------------------------------------

// Griser quelques les inputs
function griserQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").readOnly=true;
    }
}

// Afficher les inputs
function degriserQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").readOnly=false;
    }
}

// Griser un champ en mode lecture
function griserUnChamp(input){
    document.getElementById(""+input+"").readOnly=true;
}

// Activer un champ en mode lecture
function degriserUnChamp(input){
    document.getElementById(""+input+"").readOnly=false;
}

//----------------------------------------------------------------

// Cacher un champ
function cacherUnChamp(input){
    document.getElementById(""+input+"").setAttribute("hidden","true");
}

// Cacher les inputs
function cacherTousLesInput(){
    var elements = document.getElementsByTagName("input")
    for (var i = 0; i < elements.length; i++) {
       elements[i].setAttribute("hidden","true");
    }
}

// Cacher les Textarea
function cacherTousLesTextarea(){
    var elements = document.getElementsByTagName("textarea")
    for (var i = 0; i < elements.length; i++) {
        elements[i].setAttribute("hidden","true");
    }
}

// Cacher les Checkbox
function cacherTousLesCheckbox(){
    var elements = document.getElementsByTagName("checkbox")
    for (var i = 0; i < elements.length; i++) {
        elements[i].setAttribute("hidden","true");
    }
}

// Cacher quelques les inputs
function cacherQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").setAttribute("hidden","true");
    }
}

// Cacher les select
function cacherTousLesSelect(){
    var elements = document.getElementsByTagName("select")
    for (var i = 0; i < elements.length; i++) {
       elements[i].setAttribute("hidden","true");
    }
}

// Cacher les Radio
function cacherTousLesRadio(){
    var elements = document.getElementsByTagName("radio")
    for (var i = 0; i < elements.length; i++) {
        elements[i].setAttribute("hidden","true");
    }
}

//----------------------------------------------------------------

// Afficher les inputs
function afficherQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").removeAttribute("hidden");
    }
}

// Afficher un champ
function afficherUnChamp(input){
    document.getElementById(""+input+"").removeAttribute("hidden");
}

// Afficher les inputs
function afficherTousLesInput(){
    var elements = document.getElementsByTagName("input")
    for (var i = 0; i < elements.length; i++) {
       elements[i].removeAttribute("hidden");
    }
}

// Afficher les select
function afficherTousLesSelect(){
    var elements = document.getElementsByTagName("select")
    for (var i = 0; i < elements.length; i++) {
       elements[i].removeAttribute("hidden");
    }
}

// Afficher les Textarea
function afficherTousLesTextarea(){
    var elements = document.getElementsByTagName("textarea")
    for (var i = 0; i < elements.length; i++) {
       elements[i].removeAttribute("hidden");
    }
}

// Afficher les Radio
function afficherTousLesRadio(){
    var elements = document.getElementsByTagName("radio")
    for (var i = 0; i < elements.length; i++) {
       elements[i].removeAttribute("hidden");
    }
}

// Afficher les Checkbox
function afficherTousLesCheckbox(){
    var elements = document.getElementsByTagName("checkbox")
    for (var i = 0; i < elements.length; i++) {
       elements[i].removeAttribute("hidden");
    }
}

//---------------------------------------------------------------

// vider quelques les inputs
function viderQuelquesInput(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").value='';
    }
}

//----------------------------------------------------------------
// cocher radion
function cocherRadio(champ){
    document.getElementById(""+champ+"").checked = true;
}

// decocher
function decocherRadio(champ){
    document.getElementById(""+champ+"").checked = false;
}

//----------------------------------------------------------------

// Rendre un champ obligatoire
function rendreChampObligatoire(input){
    document.getElementById(""+input+"").setAttribute("required", "required");
}

// Rendre un champ obligatoire
function enleverChampObligatoire(input){
    document.getElementById(""+input+"").removeAttribute("required");;
}

// Mettre un attribut
function mettreAttribut(champ,attribut){
    document.getElementById(""+champ+"").setAttribute(""+attribut+"","true");
}

// Enlever un attribut
function enleverAttribut(champ,attribut){
    document.getElementById(""+champ+"").removeAttribute(""+attribut+"");
}

// Rendre quelques champs obligqtoire
function rendreQuelquesChampObligatoire(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").setAttribute("required", "required");
    }
}
// Enlever quelques champs obligqtoire
function enleverQuelquesChampObligatoire(champs=[]){
    for (let x of champs) {
        document.getElementById(""+x+"").removeAttribute("required");
    }
}
//----------------------------------------------------------------

function remplaceButtonText(buttonId, text){
    if (document.getElementById){
        var button = document.getElementById(buttonId);
        if (button){
            if (button.childNodes[0]) {
                button.childNodes[0].nodeValue = text;
            } else if (button.value) {
                button.value = text;
                // } else //if (button.innerHTML)
            } else {
                button.innerHTML = text;
            }
        }
    }
}

// Actualiser la page
function actualiserPageAjax() {
    setTimeout(function() {
        window.location.reload();
    }, 500);
}

// Alimenter les champs
function alimenterChampsJS(champs={}){
    for (var nom_cle in champs){
        if (document.getElementById(""+nom_cle+"").getAttribute('type')=='checkbox') {
            if (champs[nom_cle]=="1") {
                document.getElementById(""+nom_cle+"").checked=1;
            }
        }else{
            document.getElementById(""+nom_cle+"").value=""+champs[nom_cle]+"";
        }
    }
}









