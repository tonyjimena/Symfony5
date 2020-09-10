jQuery(document).ready(function ($) {
  $(".checkboxer").delay(1000).animate({
    opacity: "1",
  });

  //Añade clase activa a la navbar cuando la pagina coincide
  let pathname = window.location.pathname;
  let link = document.getElementsByClassName("nav-link");

  for (var i = 0; i < link.length; i++) {
    let href = link[i].getAttribute("href");
    if (href == pathname) {
      link[i].parentNode.className += " active";
      link[i].className += " current-nav";
    }
  }

  //MOSTRAMOS LA IMAGEN QUE ACABAS DE SELECCIONAR PARA SUBIR
  function archivo(evt) {
    $(".thumb").remove();
    var files = evt.target.files; // FileList object
    // Obtenemos la imagen del campo "file".
    for (var i = 0, f; (f = files[i]); i++) {
      //Solo admitimos imágenes.
      if (!f.type.match("image.*")) {
        continue;
      }
      var reader = new FileReader();

      reader.onload = (function (theFile) {
        return function (e) {
          // Insertamos la imagen
          document.getElementById("list").innerHTML = [
            '<img class="thumb" src="',
            e.target.result,
            '" title="',
            escape(theFile.name),
            '"/>',
          ].join("");
        };
      })(f);

      reader.readAsDataURL(f);
    }
  }
  document
    .getElementById("projects_form_image")
    .addEventListener("change", archivo, false);
});

//NO PUEDES SUBIR UNA IMAGEN QUE PESE MUCHO
$("#projects_form_image").on("change", function () {
  const size = (this.files[0].size / 1024 / 1024).toFixed(2);

  if (size > 5) {
    $("#projects_form_image").val("");
    $("#alerta").append("La imagen Seleccionada es demasiado grande, MAX.5MB");
    $("#alerta").delay(5000).fadeOut();
  }
});

//LOADER

$(window).on("load", function () {
  $("#page-loader").fadeOut(700);
});

window.onbeforeunload = function () {
  $("#front").removeClass("fade-in");
  $("#front").addClass("fade-out-up");
  $("#page-loader").delay(400).fadeIn(500);
  $(
    "#navbarsExampleDefault > ul.navbar-nav.mr-auto > li.nav-item.active > a"
  ).addClass("bye-current-nav");
};

//ICONO ANIMADO DE LA NAVBAR
$(document).ready(function () {
  $(".first-button").on("click", function () {
    $(".animated-icon1").toggleClass("open");
  });

  $(".second-button").on("click", function () {
    $(".animated-icon2").toggleClass("open");
  });

  $(".third-button").on("click", function () {
    $(".animated-icon3").toggleClass("open");
  });
});

//ALERTAS

function alerta(tipo) {
  if (tipo == "Eliminado") {
    var clase = "danger";
  } else {
    var clase = "success";
  }
  let idalert = Math.floor(Math.random() * 10);
  $(".alertas")
    .append(`<div id="alert-success${idalert}" class="alert alert-${clase} alert-dismissible fade show" role="alert">
                    <strong>Proyecto ${tipo}!</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button></div>`);

  $("#alert-success" + idalert)
    .delay(5000)
    .fadeOut(500);
  setTimeout(function () {
    $("#alert-success" + idalert).remove();
  }, 5600);
}

//Navbar TGGLE SLIDE
jQuery(function ($) {
  var $navbar = $(".navbar"),
    navheight = $navbar.outerHeight(),
    $window = $(window);

  function toggleNavBar() {
    var scroll = $window.scrollTop();

    if (scroll >= navheight) {
      if (!$navbar.hasClass("navbar-fixed-top")) {
        $navbar.addClass("navbar-fixed-top");
        //$navbar.addClass('bg-dark');
        $navbar.css("margin-top", -navheight);
        $navbar.animate({ marginTop: 0 }, 500, function () {
          $window.one("scroll", toggleNavBar);
        });
      } else {
        $window.one("scroll", toggleNavBar);
      }
    } else {
      if ($navbar.hasClass("navbar-fixed-top")) {
        $navbar.removeClass("navbar-fixed-top");
        //$navbar.removeClass('bg-dark');
      }

      $window.one("scroll", toggleNavBar);
    }
  }

  $window.one("scroll", toggleNavBar);
});

//BUSQUEDA DIRECTA
function myFunction() {
  var input, filter, ul, li, a, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  ul = document.getElementById("projectsajax");
  li = ul.getElementsByClassName("col-md-4");

  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("h2")[0];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}

// INTERSECTION OBSERVER //

const components = document.querySelectorAll('.anim')

        observer = new IntersectionObserver((entries) => {
            console.log(entries)
            entries.forEach(entry => {

                if(entry.intersectionRatio > 0) {
                    entry.target.style.animation = `anim1 .5s ${entry.target.dataset.delay} forwards ease-out`
                }else {
                    entry.target.style.animation = 'none'
                }
                
            })
        })
        components.forEach(component => {
            observer.observe(component)
        })
