var count_check = 0;
setInterval(function () {
    count_check = $(".b-checkbox__input:checked").length;
    if (count_check==10){
        $(".b-checkbox__input:not(:checked)").attr( "disabled", "disabled" );
    }else{
        $(".b-checkbox__input").removeAttr( "disabled");
    }
}, 500);