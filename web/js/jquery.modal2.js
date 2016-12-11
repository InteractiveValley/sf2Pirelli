(function($){
	$.fn.dialogModalRS=function(urlPage,callback){
	//function showMensajeModal(){
		// fondo transparente
                $("#preloader").show('fast');
		var bgdiv = $('<div>').attr({
					class: 'bgtransparent',
					id: 'bgtransparent'
					});

		$('body').append(bgdiv);
		
		var wscr = $(window).width();
		var hscr = $(window).height();
				
		/*$('#bgtransparent').css("width", wscr);
		$('#bgtransparent').css("height", hscr);*/

                $('#bgtransparent').animate({width:wscr ,height:20}, 'slow',null,function(){
                    $(this).animate({height:hscr},'slow',null,function(){
                     	var moddiv = $('<div>').attr({
							class: 'bgmodal2',
							id: 'dialog-modal',
						});
                                                
                        var cerrar = $('<span class="cerrar-modal2" onclick="javascript:closeDialogModalRS()"><i class="fa fa-times"></i></span>')
                                
                        $('#bgtransparent').append(cerrar);
                        $('body').append(moddiv);
                        
                        $('#dialog-modal').load(urlPage, function(){
                        	//alguna accion que realice na la pagina 
                        	if(callback)   
                                callback();
                        });

                        $(window).resize();
                    });
                });
		
		
		// ventana flotante original
		/*var moddiv = $('<div>').attr({
					className: 'bgmodal',
					id: 'bgmodal'
					});	
		
		$('body').append(moddiv);
		$('#bgmodal').append(contenidoHTML);
		
		$(window).resize();*/
	}
})(jQuery);

$(window).resize(function(){
		// dimensiones de la ventana
        var ancho = 1100;
        var alto = 500;
                
		var wscr = $(window).width();
		var hscr = $(window).height();

		// estableciendo dimensiones de background
		$('#bgtransparent').css("width", wscr);
		$('#bgtransparent').css("height", hscr);
		
		// definiendo tama�o del contenedor
		$('#dialog-modal').css("width", '90%');
		$('#dialog-modal').css("height", alto+'px');


		
		// obtiendo tama�o de contenedor
		var wcnt = $('#dialog-modal').width();
		var hcnt = $('#dialog-modal').height();
		
		// obtener posicion central
		var mleft = ( wscr - wcnt ) / 2;
		var mtop = ( hscr - hcnt ) / 2;
		
		// estableciendo posicion
		$('#dialog-modal').css("left", mleft+'px');
		$('#dialog-modal').css("top", mtop+'px');

});
$(window).keyup(function(event){
    if (event.keyCode == 27) {
         closeDialogModalRS();
    }
});

function closeDialogModalRS(){
       $('#dialog-modal').fadeOut('slow',function(){
          $(this).remove();
          $('#bgtransparent').slideUp('slow',function(){
              $(this).remove();
          });
       });
       
}

