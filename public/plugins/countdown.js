$(document).ready(function () {
    $.fn.countdown = function() {
        return this.each (function(indice) {
            if ($(this).attr('id') === undefined) $(this).attr('id', 'cntdwn'+indice);
            if ($(this).attr('title') === undefined) $(this).attr ('title', '¡Es la hora!');
            var fecha = new Date($(this).text()),
                contenedor = '#'+$(this).attr('id'),
                mensaje = $(this).attr('title');
            $.fn.countdown.refresca(Math.floor(new Date(fecha-new Date()).valueOf()/1000), contenedor, mensaje);
        });
    };

    $.fn.countdown.refresca = function(secs, id, mensaje) {

        function calcage(secs, num1, num2) {
            s = ((Math.floor(secs/num1))%num2).toString();
            if (s.length < 2)
                s = "0" + s;
            return s;
        }

        if (secs < 0) {
            location.reload();
            return false;
        } else {
            var dia = calcage(secs,86400,100000),
                hora = calcage(secs,3600,24),
                min = calcage(secs,60,60),
                seg = calcage(secs,1,60);

            $(id).html(
                '<h1><span class="badge badge-secondary mr-2">'+dia+'</span>' +
                '<span class="badge badge-secondary mr-2">'+hora+'</span>' +
                '<span class="badge badge-secondary mr-2">'+min+'</span>' +
                '<span class="badge badge-secondary mr-2">'+seg+'</span></h1>'
            );

            setTimeout("$.fn.countdown.refresca(" + (secs-1) + ", '" + id + "', '"+ mensaje +"')", 1000);
        }

        return this;
    };
});
