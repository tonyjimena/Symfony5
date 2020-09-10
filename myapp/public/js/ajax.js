
//CARGA LOS PROJECTSS
$(".users, #myInput").on('click', function (event) {
    $("#projectsajax").empty();
    $('#cargador').show();
    $.ajax("http://192.168.1.195:8080/api/projects", {
        dataType: 'json',
        success: function (data) {
            data.forEach(function (valor, i) {
              setTimeout (() => {
                $("#projectsajax").append(
                  '<div class="col-md-4" id="card-'+ valor.id +'"><div class="card mb-4"><a href="/projects/view/'+ valor.id + '">'+
                  '<img id="image-'+ valor.image +'" src="uploads/images/'+ valor.image +'" class="bd-placeholder-img card-img-top" width="100%" height="225"></img></a>'+
                  '<div class="card-body"><h2>'+ valor.name +'</h2>'+
                  '<p class="card-text">'+ valor.info +'</p>'+
                  '<div class="d-flex justify-content-between align-items-center"><div class="btn-group">'+
                  '<button id="view" value="'+ valor.id +'" class="btn btn-sm btn-outline-secondary">View</button>'+
                  '<button data-toggle="modal" data-target="#exampleModal" id="edit" value="'+ valor.id +'" class="btn btn-sm btn-outline-secondary" onClick="editJson('+ valor.id +')">Edit</button>'+
                  '<button id="'+ valor.id +'" class="btn btn-sm btn-outline-secondary" onClick="deletejson('+ valor.id + ')">Delete</button>'+
                  '</div><small class="text-muted">'+ valor.place +'</small></div></div></div></div>'
                );
              }, i * 200);
            });
            $("#cargador").fadeOut(500);
        },
        error: function (jqXHR, texStatus, error) {
            alert("Error:" + texStatus + " " + error);
        }
    });
});

//ELIMINA LOS PROJECTSS 

function deletejson(id) {
    let conf = confirm('Esta seguro?');
    if (conf == true) {
    $("#card-"+ id ).fadeOut(1000);
    $.get(`http://192.168.1.195:8080/api/projects/delete/${id}`)
        .done(function (data) {
          $('.alertas')
                  .append('<div id="alert-danger'+ id +'" class="alert alert-danger alert-dismissible fade show" role="alert">'+
                            '<strong>Proyecto Eliminado!</strong>'+
                              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                  '<span aria-hidden="true">&times;</span></button></div>');
                                  
          $('#alert-danger'+id).delay(5000).fadeOut(500);
          setTimeout(function() {
            $('#alert-danger'+id).remove();
          }, 5600);
        })
      }
};


//CREAR PROYECTOS

$(document).ready(function(e){
  // Submit form data via Ajax
  $("#fupForm").on('submit', function(e){
      $('#cargador').show();
      e.preventDefault();

      $([document.documentElement, document.body]).animate({
            scrollTop: $("#album").height()
        }, 1500);

      $.ajax({
          type: 'POST',
          url: 'http://192.168.1.195:8080/api/projects/new',
          data: new FormData(this),
          dataType: 'json',
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function(){
              $('.submitBtn').attr("disabled","disabled");
              $('#fupForm').css("opacity",".5");
          },
          success: function(response){ //console.log(response);
              $('.statusMsg').html('');
              let idalert = Math.floor(Math.random() * 10); 
              $('.alertas')
              .append('<div id="alert-success'+idalert+'" class="alert alert-success alert-dismissible fade show" role="alert">'+
                        '<strong>Proyecto creado!</strong>'+
                          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                              '<span aria-hidden="true">&times;</span></button></div>');

              $('#alert-success'+idalert).delay(5000).fadeOut(500);
              setTimeout(function() {
                $('#alert-success'+idalert).remove();
              }, 5600);

              //SI EL ALBUM ESTA ABIERTO, AÑADIMOS LA CARD DEL PROYECTO CREADO
              if ($('.col-md-4').length) {

                         lastproject();
              }
              
              $('#fupForm').css("opacity","");
              $(".submitBtn").removeAttr("disabled");
              $('#cargador').fadeOut();
          }
      });
  });
});

//EDITAR PROYECTOS

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

$("#fupForm2").submit(function(e){
  $('#cargador').show();
  e.preventDefault();
  let id = $("#idmodal").val();

  $.ajax({
      type: 'POST',
      url: `http://192.168.1.195:8080/api/projects/edit/${id}`,
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function(){
          $('.submitBtn').attr("disabled","disabled");
          $('#fupForm2').css("opacity",".5");
      },
      success: function(response){
        console.log(response);
          alerta('Editado');
          $('#fupForm2').css("opacity","");
          $(".submitBtn").removeAttr("disabled");
          $('#card-'+id).fadeOut(300);

          response.forEach(function (valor) {
            setTimeout(function() {
              $('#card-'+id).find("h2").text(valor.name);
              $('#card-'+id).find("img").attr('src', "uploads/images/"+ valor.image);
              $('#card-'+id).find(".card-text").text(valor.info);
              $('#card-'+id).find("small").text(valor.place);
            }, 350);
          });
          
          $('#cargador').fadeOut();
          
          //oneproject(id);

          $('#card-'+id).fadeIn(2000);
      }
  });
});

function oneproject(id) {
  $.ajax("http://192.168.1.195:8080/api/find?id="+id, {
      dataType: 'json',
      success: function (data) {
          data.forEach(function (valor) {
              $('#image-'+id).src(valor.image);
              /*$("#projectsajax").append(
                '<div class="col-md-4" id="card-'+ valor.id +'"><div class="card mb-4"><a href="/projects/view/'+ valor.id + '">'+
                '<img id="image-'+ valor.image +'" src="uploads/images/'+ valor.image +'" class="bd-placeholder-img card-img-top" width="100%" height="225"></img></a>'+
                '<div class="card-body"><h2>'+ valor.name +'</h2>'+
                '<p class="card-text">'+ valor.info +'</p>'+
                '<div class="d-flex justify-content-between align-items-center"><div class="btn-group">'+
                '<button id="view" value="'+ valor.id +'" class="btn btn-sm btn-outline-secondary">View</button>'+
                '<button id="edit" value="'+ valor.id +'" class="btn btn-sm btn-outline-secondary"data-toggle="modal" data-target="#exampleModal">Edit</button>'+
                '<button id="'+ valor.id +'" class="btn btn-sm btn-outline-secondary" onClick="deletejson('+ valor.id + ')">Delete</button>'+
                '</div><small class="text-muted">'+ valor.place +'</small></div></div></div></div>'
              );*/
          });
          },
          error: function (jqXHR, texStatus, error) {
          alert("Error:" + texStatus + " " + error);
          }
  });
  }

//EL ULTIMO PROYECTO CREADO y LO AÑADE AL ALBUM
function lastproject() {
$.ajax("http://192.168.1.195:8080/api/projects", {
    dataType: 'json',
    success: function (data) {
        data.forEach(function (valor) {
            $("#projectsajax").append(
              '<div class="col-md-4" id="card-'+ valor.id +'"><div class="card mb-4"><a href="/projects/view/'+ valor.id + '">'+
              '<img id="image-'+ valor.image +'" src="uploads/images/'+ valor.image +'" class="bd-placeholder-img card-img-top" width="100%" height="225"></img></a>'+
              '<div class="card-body"><h2>'+ valor.name +'</h2>'+
              '<p class="card-text">'+ valor.info +'</p>'+
              '<div class="d-flex justify-content-between align-items-center"><div class="btn-group">'+
              '<button id="view" value="'+ valor.id +'" class="btn btn-sm btn-outline-secondary">View</button>'+
              '<button id="edit" value="'+ valor.id +'" class="btn btn-sm btn-outline-secondary"data-toggle="modal" data-target="#exampleModal" onClick="editJson('+ valor.id +')">Edit</button>'+
              '<button id="'+ valor.id +'" class="btn btn-sm btn-outline-secondary" onClick="deletejson('+ valor.id + ')">Delete</button>'+
              '</div><small class="text-muted">'+ valor.place +'</small></div></div></div></div>'
            );
        });
        },
        error: function (jqXHR, texStatus, error) {
        alert("Error:" + texStatus + " " + error);
        }
});
}
