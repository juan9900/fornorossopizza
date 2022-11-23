console.log($('.promo-plates'));
// boton de opciones de platos
const btnPlatesOptions = $('.promo-plates-container');
const platesOptions = $('.promo-plates');
const listArrow = $('.arrow-down');

$(btnPlatesOptions).on('click', () => {
    $(platesOptions).toggleClass('active');
    $(btnPlatesOptions).toggleClass('active');
    $(listArrow).toggleClass('rotate');
})