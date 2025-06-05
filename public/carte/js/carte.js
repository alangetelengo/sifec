
var carte=document.querySelector('.carte');
var paths =carte.querySelectorAll('.carte__image a');

var activeArea=function(id){
    carte.querySelectorAll('.actived').forEach(function (item){
        item.classList.remove('actived');
    });
    document.querySelector('#CG-'+id).classList.add('actived');
}

paths.forEach(function (path){
    path.addEventListener('click', function(e){
        var id=this.id.replace('CG-','');
        activeArea(id);
    });
});

