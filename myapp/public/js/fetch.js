const API_URL = 'http://157.97.183.124'

//Obtener los proyectos y renderizarlos

function fetchProjects(){
    $("#projectsajax").empty();
    $('#cargador').show();
    console.log("usando FETCH");

        fetch(`${API_URL}/api/projects`)
            .then(resp => resp.json())
                .then(renderProjects)
                .then($("#more").fadeOut(500))
                .then($("#myInput").fadeIn(1500))

    }

function renderProjects(projects) {
    console.log(projects);
    console.log(userGranted)
    projects.forEach(function (project, i) {
        setTimeout (() => {
            $("#projectsajax").append(`
            <div class="col-md-4" id="card-${project.id}">
                <div class="card mb-4"><a href="/projects/view/${project.id}">
                    <img id="image-${project.id}" src="uploads/images/${project.image}" class="bd-placeholder-img card-img-top"
                    width="100%" height="225">
                    </img></a>
                        <div class="card-body"><h2>${project.name}</h2>
                        <p class="card-text">${project.info}</p>
                            <div class="d-flex justify-content-between align-items-center"><div class="btn-group btns-${project.id}">
                                
                                <!-- <button id="view" value="${project.id}" class="btn btn-sm btn-outline-secondary" data-toggle="modal"
                                 data-target=".bd-example-modal-lg" onClick="viewProject(${project.id})">View</button> -->
                                
                                </div><small class="text-muted">${project.place}</small>
                        </div>
                    </div>
                </div>
            </div>`
            );
            //Si el usuario esta registrado se podra acceder a la edit y al delete de la card
            if (userGranted == 1){
                $(`.btns-${project.id}`).append(`<button data-toggle="modal" data-target="#exampleModal" id="edit" value="${project.id}"
                class="btn btn-sm btn-outline-secondary" onClick="editJson(${project.id})">Edit</button>
                <button id="${project.id}" class="btn btn-sm btn-outline-secondary" onClick="deleteProject(${project.id})">Delete</button>`);
            }
        }, i * 200);
        });
    $("#cargador").fadeOut(500);
}


//Crear un nuevo proyecto y renderizarlo

$("#fupForm").on('submit', function(event){
    $('#cargador').show();
    event.preventDefault();
    const data = new FormData(document.getElementById('fupForm'));

    $([document.documentElement, document.body]).animate({
          scrollTop: $("#album").height()
      }, 1500);

        fetch(`${API_URL}/api/projects/new`, {
            method: 'POST',
            body: data
        })
            .then(resp => resp.json())
            .then(renderProjects)
            .then(alerta("Creado"))
})

//Eliminar proyectos
function deleteProject(id) {
    //let id = event.target.parentElement.dataset.id
    let conf = confirm('Esta seguro?');
    if (conf == true)
    fetch(`${API_URL}/api/projects/delete/${id}`, {
      method: 'GET'
    })
    .then(response => response.json())
    .then($("#card-"+ id ).fadeOut(1000))
    .then(alerta("Eliminado"))
}

//Editar

function editJson (id){
    
    $('#fupForm2').trigger("reset");
  
      let image = $('#card-'+id).find("img").attr('src');
      let names = $('#card-'+id).find("h2").text();
      let place = $('#card-'+id).find(".card-text").text();
      let info = $('#card-'+id).find("small").text();
  
      $("#idmodal").val(id);
      $("#namemodal").attr('placeholder', names);
      //$("#file").attr('placeholder', image);
      $("#placemodal").attr('placeholder', place);
      $("#infomodal").attr('placeholder', info);
  
  };

$("#fupForm2").on('submit', function(event){

    let id = $("#idmodal").val();
    $('#cargador').show();
    event.preventDefault();
    const data = new FormData(document.getElementById('fupForm2'));

    fetch(`${API_URL}/api/projects/edit/${id}`, {
        method: 'POST',
                body: data
        })
        //.then($('#card-'+id).fadeOut(300))
        .then(resp => resp.json())
        .then(reloadProject)
        .then(alerta("Editado"))

})

function reloadProject(project) {
    //console.log(project)
    project.forEach(function (valor) {
        $('#card-'+valor.id).fadeOut(300)
      setTimeout(function() {
        $('#card-'+valor.id).find("h2").text(valor.name);
        $('#card-'+valor.id).find("img").attr('src', "uploads/images/"+ valor.image);
        $('#card-'+valor.id).find(".card-text").text(valor.info);
        $('#card-'+valor.id).find("small").text(valor.place);
      }, 350);
      $('#card-'+valor.id).fadeIn(1500);
    });
    
    $('#cargador').fadeOut();
}

/*
function fetchId(id) {

    fetch(`${API_URL}/api/find?id=${id}`, {
            method: 'GET',
        })
            .then(resp => resp.json())
            .then(reloadProject)
}
*/

