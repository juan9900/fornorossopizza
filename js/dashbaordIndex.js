

//Eliminar doctores del directorio medico 
let clientId;
$(document).on('click','#btn-delete-client', function(e){
    console.log('pressed');
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
        console.log(response);
        if(response.result === 'success'){
            window.location.replace("dashboardIndex.php");

        }
    });

    request.fail(function(e, textStatus) {
        console.log(JSON.stringify(e));
    });
})

z*"3XFES+!A59@v#d

