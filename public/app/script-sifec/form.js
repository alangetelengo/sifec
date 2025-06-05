function majuscule(lalettre){

    // var lettres = new RegExp("[0-9]");
    var lettres =  RegExp("^[a-z A-Z é î ï è ë ' ô ç -]*$");
    var verif;
    var points = 0;

    for(x = 0; x < lalettre.value.length; x++)
    {
        verif = lettres.test(lalettre.value.charAt(x));
        if(lalettre.value.charAt(x) == "."){points++;}
        if(points > 1){verif = false; points = 1;}
        if(verif == false){lalettre.value = lalettre.value.substr(0,x) + lalettre.value.substr(x+1,lalettre.value.length-x+1); x--;}
    }
}
