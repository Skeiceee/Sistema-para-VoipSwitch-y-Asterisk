$('#menu_toggle').click(function(){
    let hamburger = $('#hamburger')
    $('#menu_wrapper').slideToggle(150);
    text = hamburger.attr('class')
    if(text.includes('is-active')){
        hamburger.removeClass('is-active')
    }else{
        hamburger.addClass('is-active')
    }
});