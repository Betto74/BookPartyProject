

document.addEventListener("DOMContentLoaded", () => {

    //  debugger

    
    
    $(document).ready(function(){
        $('#calendario').daterangepicker({
            
            locale: {
                format: ''
            },
            opens: 'right',
            autoApply: true,
            showDropdowns: true,
            linkedCalendars: false,
            alwaysShowCalendars: true,
            showCustomRangeLabel: false,
            startDate: moment(),
            endDate: moment().add(1, 'month').startOf('month'),
            minDate: moment().startOf('day'), 
            autoUpdateInput: false, 
            drops: 'down',
            opens: 'left',
            isInvalidDate: function(date) {
                return isDateInReservas(date);
            }
        });

        $('#calendario').on('apply.daterangepicker', function(ev, picker) {

            var startDate = picker.startDate;
            var endDate = picker.endDate;
            var days = endDate.diff(startDate, 'days') + 1; // Incluye el último
            updatePrice(days);
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' / ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#calendario').val('Click para reservar');
    });

  

});


function updatePrice(days) {
    var precioBase = parseFloat(document.getElementById('precio').getAttribute('data-precio'));
    var startDate = $('#calendario').data('daterangepicker').startDate;
    var endDate = $('#calendario').data('daterangepicker').endDate;
    var fechaActual = startDate.clone();
    var diasHabiles = 0;

    // Itera sobre cada día del rango seleccionado
    while (fechaActual <= endDate) {
        // Verifica si el día actual no está bloqueado
        if (!isDateInReservas(fechaActual)) {
            diasHabiles++;
        }
        // Avanza al siguiente día
        fechaActual.add(1, 'day');
    }

    var precioTotal = precioBase * diasHabiles;
    document.getElementById('precioTotal').textContent = `$${precioTotal.toFixed(2)} MXN por ${diasHabiles} día(s)`;
}

function isDateInReservas(date) {
    //debugger
    // Convertimos la fecha a string en el mismo formato que las fechas en 'reservas'
    var dateString = date.format('YYYY-MM-DD');
    // Usamos 'find' para buscar la fecha en el arreglo de reservas
    return !!reservas.find(function(reserva) {
        return reserva === dateString;
    });
}

function enviarReserva() {
    debugger;
    // Captura el valor del input
    var calendarioValue = document.getElementById('calendario').value;
    
    // Captura el parámetro GET de la URL
    var parametroGet = new URLSearchParams(window.location.search).get('id');
    
    // Crea un formulario oculto
    var formulario = document.createElement('form');
    formulario.method = 'post'; // Método POST
    formulario.action = 'registrarReserva.php'; // La URL del script que maneja la reserva
    
    // Añade el valor del input al formulario
    var inputCalendario = document.createElement('input');
    inputCalendario.type = 'hidden';
    inputCalendario.name = 'calendario';
    inputCalendario.value = calendarioValue;
    formulario.appendChild(inputCalendario);
    
    // Añade el parámetro GET al formulario si existe
    if (parametroGet) {
        var inputParametroGet = document.createElement('input');
        inputParametroGet.type = 'hidden';
        inputParametroGet.name = 'nombreParametro'; // Cambia 'nombreParametro' por el nombre real de tu parámetro GET
        inputParametroGet.value = parametroGet;
        formulario.appendChild(inputParametroGet);
    }
    
    // Añade el formulario al documento y envía
    document.body.appendChild(formulario);
    formulario.submit();
}