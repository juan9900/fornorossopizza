$(document).ready(function(){
    var request = $.ajax({
        url: 'modules/getAllClients.php',    //Leerá la url en la etiqueta action del formulario (archivo.php)
        method: 'POST', //Leerá el método en etiqueta method del formulario
        dataType: "json"            //puede ser de otro tipo
    });

    //Este bloque se ejecutará si no hay error en la petición
    request.done(function(response) {
        
        response.forEach((client) => {
            $('.clients-body').append(
                `<tr class="table-row">
                        <td data-title="Nombre:">${client.firstName}</td>
                        <td data-title="Apellido:">${client.lastName}</td>
                        <td data-title="Teléfono:">${client.phoneNumber}</td>
                        <td data-title="Correo Electrónico">${client.email}</td>
                        <td data-title="Fecha de suscripción">${client.subscriptionDate}</td>
                    </tr>`
            )
        })
       
        
    });
    //Este bloque se ejecuta si hay un error
    request.fail(function(jqXHR, textStatus) {
        console.error("Hubo un error: " + JSON.stringify(jqXHR));
    });
})

//Eliminar clientes
let clientId;
$(document).on('click','#btn-delete-client', function(e){
    clientId = $(this).closest('tr').find('.d-none').text();
})

$(document).on('click','#btn-delete-confirm',(e) => {
    var request = $.ajax({
        url: 'modules/deleteClient.php',
        type: 'Post',
        data: {
            id: clientId
        },
        dataType: 'json',
    })

    request.done(function(response){
        if(response.result === 'success'){
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const page = urlParams.get('page');
            window.location.replace(`dashboardIndex.php?page=${page}`);

        }
    });

    request.fail(function(e, textStatus) {
        console.log(JSON.stringify(e));
    });
})


// EXPORT TABLE TO EXCEL

const downloadTable = () => {
    const clientsTable = document.getElementById('full-clients');
    const wb = XLSX.utils.table_to_book(clientsTable);


    // Get current date 
    const date = new Date();

    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    let currentDate = `${day}-${month}-${year}`;


    var wscols = [
        {wch: 6}, // "characters"
        {wpx: 50}, // "pixels"
        ,
        {hidden: true} // hide column
    ];
    wb['!cols'] = wscols;

    /* Export to file (start a download) */
    XLSX.writeFile(wb, `Clientes-club-forno-${currentDate}.xlsx`);
}

$('#btn-export-excel').on('click',()=>{
    downloadTable();
})

// function exportTable() {
//     $("#full-clients").table2excel({
//         name: "Club Forno Clientes",
//         filename: "file.xls",
//         preserveColors: false,
//     });
//   }

