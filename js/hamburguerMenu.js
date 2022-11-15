window.onload = function (){
    const menu_btn = document.querySelector('.hamburguer');
    const mobile_menu = document.querySelector('.mobile-nav');
    const menu_items = document.querySelectorAll('.mobile-nav a');
    menu_btn.addEventListener('click', function (){
        menu_btn.classList.toggle('is-active');
        mobile_menu.classList.toggle('is-active')
    });
    for (let i = 0; i < menu_items.length; i++) {
        menu_items[i].addEventListener('click',function(){
            menu_btn.classList.toggle('is-active');
            mobile_menu.classList.toggle('is-active')
        })
            
}
}