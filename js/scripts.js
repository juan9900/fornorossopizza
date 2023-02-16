// boton de opciones de platos
const btnPlatesOptions = $('.promo-plates-container');
const platesOptions = $('.promo-plates');
const listArrow = $('.arrow-down');

const bgMusic = $('#bgMusic')[0];
const musicBtn = $('#music-btn');
const pauseImg = $('.pause-img');
    bgMusic.volume = 0.05; // sets the volume to 5%
$(musicBtn).on('click',function(){
    if(bgMusic.paused){
        bgMusic.play();
        $(pauseImg).attr('src','/img/Simple_Music.-whitesvg.svg');

    }else{
        bgMusic.pause();
        $(pauseImg).attr('src','/img/Simple_Music.-nosvg.svg');
    }
})
$(btnPlatesOptions).on('click', () => {
    $(platesOptions).toggleClass('active');
    $(btnPlatesOptions).toggleClass('active');
    $(listArrow).toggleClass('rotate');
})

const alertPlaceholder = $('#liveAlertPlaceholder')
var timerCloseAlert;

// Enviar suscripcion al club forno
$('#club-form').on('submit',(e) => {
    firstName = $('#firstName').val();
    lastName = $('#lastName').val();
    email = $('#email').val();
    phoneNumber = $('#phoneNumber').val();
    birthdate = $('#birthdate').val();
    
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: '/modules/addClient.php',
        data: {
            firstName,
            lastName,
            email,
            phoneNumber,
            birthdate,
        },
        dataType: 'json',
        success: (response) => {
            if(response.status === 'success'){
                $('.club-form-container').addClass('d-none');
                $('.subscribed-text').removeClass('d-none');
            }else{
                alert(response.errors,'danger');
                timer = setTimeout(() => {
                    $('#liveAlertPlaceholder').fadeOut(500, function(){
                        $(this).empty().show();
                    })
                },4000)
            }
        },
        error: (error) => {
            console.log('error: ' , error);
        },
      });
})

const alert = (message, type) => {
    let icon;
    switch(type){
        case 'danger':
            icon = `<i class="fa-solid fa-xmark pe-2"></i>`
        break;
        case 'success':
            icon = `<i class="fa-solid fa-check pe-2"></i>`
        break;

    }   
    
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible mb-0" role="alert" id="alert">`,
        `   <div>${message.join("<p></p>")}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    // const wrapper = 
    // `<div class="alert alert-${type} alert-dismissible mb-0" role="alert" id="alert">
    //     <div>${icon}
    //         <div>
    //             ${message.forEach(msg => {
    //                 return message
    //             })}
    //         </div>
    //     </div>
    //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    // </div>`
    
    
    
    if($('#liveAlertPlaceholder').is(':empty')){
        alertPlaceholder.append(wrapper)
    }else{
        //If the event have been saved in less than 3 seconds, then the previous setTimeout will be deleted
        //so the new alert has its own 3 seconds.
        clearTimeout(timer);
        $('#liveAlertPlaceholder').empty();
        alertPlaceholder.append(wrapper);
       
    }

}

$(document).ready(()=>{
    const date = new Date();
    const today = date.toLocaleDateString();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var  day = date.getDate();
    
    {month < 10 ? month = '0' + month : null};
    {day < 10 ? day = '0' + day : null}

    $('#birthdate').attr('max', `${year}-${month}-${day}`);
})