'use strict';
(function(){

  //Variable con las propiedades del mail
  var mailInfo = {
    cssValido:{
      'border': '2px solid rgb(25, 203, 91)',
      'color': 'rgb(25, 203, 91)',
      'font-weight': 'bold'
    },
    cssError:{
      'border': '2px solid rgb(213, 48, 19)',
      'color': 'rgb(213, 48, 19)',
      'font-weight': 'bold'
    },
    cssDefault:{
      'border': '1px solid #ddd',
      'color': '#ddd',
      'font-weight': 'normal'
    },
    valido: false
  };
  //Variables que determinan si un campo del formulario es valido o no
  var imgValido = false, ingsValido = false, procValido = false, tituloValido = false;

  //Estilos para los campos de un formulario en función de si son validos o no
  var cssError = {'border': '2px solid red','background': 'rgba(247, 167, 177, 0.23)','color': 'red'};
  var cssDefault = {'border': '1px solid #ddd', 'background': 'white', 'color': 'black'};

  //Funcion para aplicar los estilos de la lista al objeto pasado como primer parametro
  var addEstilo = function(item, estilos){
    for(var est in estilos)
      item.style[est] = estilos[est];
  };


  /*==============================================================================
  *========================  FORMULARIO ============================================
  *==============================================================================*/

/*
* Introduce un ingrediente nuevo a la lista y activa el evento de borrar de la lista
*/
var addLista = function(){
  if($('#newCantidadgr').val().length === 0 || $('#newIngrediente').val().length === 0)
    alert('Introduce todos los campos');
  else{
    var gramos = "  -  " + $('#newCantidadgr').val() + "gr";
    var list = "<li class='ingrediente'>" + $('#newIngrediente').val()  + "&nbsp&nbsp&nbsp" + gramos + "<span class='delete'>X</span><input type='hidden' name='ingrediente[]' value='" + $('#newIngrediente').val() + gramos +  "'</li>";
    $("#lista-ingredientes").append(list);
    $('#newCantidadgr').val('');
    $('#newIngrediente').val('');
    $("li.ingrediente:last-child>.delete").click(function() {
      $(this).parent().fadeOut(function(){
        $(this).remove();
      });
    });
}
};

//Evento para añadir ingredientes a la lista clickando sobre el label
$(".label-newIngred").click(addLista);

//Evento para añadir ingredientes presionando enter
$('#newIngrediente').keypress(function(e){
  if(e.keyCode === 13){
    e.preventDefault();
    addLista();
  }
});

$('#newCantidadgr').keypress(function(e){
  if(e.keyCode === 13){
    e.preventDefault();
    addLista();
  }
});

//ValidarTitulo
var validarTitulo = function(){
  var titulo = $('#newNombre');

  if(titulo.val().length > 0){
    addEstilo(titulo[0], cssDefault);
    tituloValido = true;
  }else{
    addEstilo(titulo[0], cssError);
    tituloValido = false;
  }
};
//Validar ingredientes

var validarIngredientes = function(){

  if($('#lista-ingredientes li').length > 0){
    addEstilo($('#newIngrediente')[0], cssDefault);
    addEstilo($('#newCantidadgr')[0], cssDefault);
    ingsValido = true;
  }else{
    addEstilo($('#newIngrediente')[0], cssError);
    addEstilo($('#newCantidadgr')[0], cssError);
    ingsValido = false;
  }
};
// Validar elaboración
var validarPreparacion = function(){

  if($('#nueva-preparacion').val().length >100){
    addEstilo($('#nueva-preparacion')[0], cssDefault);
    procValido = true;
  }else{
    addEstilo($('#nueva-preparacion')[0], cssError);
    procValido = false;

  }
};

//Validar imagen
var validarImagen = function(){
  var img = $('#newImg');
  if(img.val().match(/\.(jpeg|jpg|gif|png)$/) !== null || img.val().length == 0){
    addEstilo(img[0], cssDefault);
    imgValido = true;
  }else{
    addEstilo(img[0], cssError);
    imgValido = false;
  }

};

//Validar correo electronico
var validarMail = function (email) {
    var regex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i;
    return regex.test(email);
};

var comprobarMail = function(){
  var mail = $('#newCorreo');
  if($(mail).val().length === 0){
    addEstilo(mail[0], mailInfo.cssDefault);
    mailInfo.valido = true;
  }else{
    if(validarMail($(mail).val())){
      addEstilo(mail[0], mailInfo.cssValido);
      mailInfo.valido = true;
    }
    else{
      addEstilo(mail[0], mailInfo.cssError);
      mailInfo.valido = false;
    }
  }
};
//Comprobar si el correo es correcto y cambiar estilos del input
$('#newCorreo').on("keydown blur", comprobarMail);
$('#formulario').on('keydown', comprobarMail);

//Validar el envio del formulario
$('#formulario').on("submit", function(e){

  validarIngredientes();
  validarImagen();
  validarPreparacion();
  validarTitulo();
  if(!(procValido && imgValido && mailInfo.valido && ingsValido && tituloValido)
      && ($('#newIngrediente').is(':focus') || !$('#newCantidadgr').is(':focus'))){
    e.preventDefault();
    alert("Por favor rellena todos los campos!");
    return false;
  }


});

//Cierra el formulario clickando fuera de él
  $('.modal-nueva').on("click", function(e){
    if(e.target != this) return;
    $(this).css('display', 'none');
  });

//Abre el formulario al presionar el boton
  $('#btn-nueva').on("click", function(){
    $('.modal-nueva').css('display', 'block');
  });
/*==============================================================================
*========================  BUSCADOR ============================================
*==============================================================================*/

var select = $('#buscador-select');

//Cambia el valor del placeholder del buscador
select.on("change", function(){
  $('.buscador-titulo').attr('placeholder', 'Buscar la receta por ' + this.value + ' aquí...').blur();
});

//Cierra el buscador al clickar fuera
  $('.buscador').on("click", function(e){
    if(e.target != this) return;
    $(this).css('display', 'none');
  });
//Cierra el buscador al clickar sobre la X
  $('.buscador-close').on("click", function(){
    $('.buscador').css('display', 'none');
  });
//Hace visible el buscador
$('#btn-buscador').on("click", function(){
  $('.buscador').css('display', 'block');
});

//AJAX

//Busca una receta y actualiza en vivo usando ajax
$('.buscador-titulo').keyup(function(){
  var campoBusqueda = $('#campo-buscador').val();
  var miRegEx = new RegExp(campoBusqueda, "i");
  $.get('./datos.xml', function(data){
    var out = '<ul class="buscador-live">';
    $(data).find('recetas').find('receta').each(function(i){
      if($(this).find(document.getElementById('buscador-select').value).text().search(miRegEx) != -1){
        out += '<a href="./receta.php?id=' + $(this).attr('id') + '"><li>';
        out += '<img src="'+ $(this).find('imagen').text() +'" alt="" width="30px" height="30px">';
        out += '<h3>' + $(this).find('titulo').text() + '</h3>';
        out += '<p>' + $(this).find('descripcion').text() + '</p>';
        out += '</li></a>';
      }
    });
    out += '</ul>';
    $('#actualizar').html(out);
  });
});

/*==============================================================================
*===========================  INDEX ============================================
*==============================================================================*/
var RECETAS_PP = 12; //Numero máximo de recetas a mostrar por página
var index_recetas_pp = 0;
var num_recetas;

var cargarUltimas = function(){
  var limite = index_recetas_pp + RECETAS_PP;
  $.get('./datos.xml', function(data){
    var s_recetas = "";
    var xml_recetas = $($(data).find('recetas').find('receta').get().reverse());
    num_recetas = $(data).find("recetas").find('receta').length;
    xml_recetas.each(function(i) {
      if(i>=index_recetas_pp){
      s_recetas += "<a href='./receta.php?id=" + $(this).attr('id') + "' class='contenedor'>";
      s_recetas += "<img src='" + $(this).find('imagen').text() + "' height='200px' width='200px' alt=''>";
      s_recetas += "<div class='contenedor-up'><p>"+ $(this).find('titulo').text()+"</p></div></a>";
    }
    if(i>=index_recetas_pp + RECETAS_PP-1)
      return false;

    });
    $('#ultimas-recetas').html(s_recetas);
  });
};

cargarUltimas();

//Vuelve a la "pagina" anterior
$('#prev').click(function(){
  if(index_recetas_pp>=RECETAS_PP){
    index_recetas_pp -= RECETAS_PP;
    cargarUltimas();
  }
});
//Avanza a la siguiente
$('#next').click(function(){
  if(index_recetas_pp+RECETAS_PP<num_recetas){
    index_recetas_pp += RECETAS_PP;
    cargarUltimas();
  }
});


/*==============================================================================
*========================  SUGERENCIAS ============================================
*==============================================================================*/
/**
* Carga en la parte de sugerencias 4 recetas al azar
*/
$.get('./datos.xml', function(data){
  var xml_recetas = $(data).find('recetas').find('receta');
  var s_sugerencias = "";
  var first= Math.floor(Math.random() * ($(data).find("recetas").find('receta').length-4));
  var last = first+4;
  var index = 0;
  xml_recetas.each(function(){

    if(index>first){

      s_sugerencias += "<a href='./receta.php?id=" + $(this).attr('id') + "'><li>";
      s_sugerencias += "<img src='" + $(this).find('imagen').text() +"' alt='' title='" + $(this).find('titulo').text() + "' height='150px' width='200px'/>";
      s_sugerencias += "</li></a>";
    }
    if(index>=last){
     return false;
    }
  index++;

  });
  $('#sugerencias').html(s_sugerencias);
});

/*==============================================================================
*========================  COMENTARIOS ============================================
*==============================================================================*/

//Gestiona y valida el envio de comentarios para que no tenga el campo de usuario y
//de texto vacios
$('#form-coment').on("submit", function(e){

  if($('#comentario-usuario').val().length === 0 || $('#comentario-text').val().length === 0){
    alert('Campos incorrectos');
    e.preventDefault();
    if($('#comentario-usuario').val().length === 0)
      addEstilo($('#comentario-usuario')[0], cssError);
    else
      addEstilo($('#comentario-usuario')[0], cssDefault);

    if($('#comentario-text').val().length === 0)
      addEstilo($('#comentario-text')[0], cssError);
    else
      addEstilo($('#comentario-text')[0], cssDefault);

    return false;
  }
});
})();//Fin
