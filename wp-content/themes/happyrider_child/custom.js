jQuery(document).ready(function($){
    $(".btn1").click(function(){
        $(".logo_2").hide("slow");
        $(".menu_2").show("slow");

        $(".btn1").hide("slow");
        $(".btn2").show("slow");
    });
    $(".btn2").click(function(){
    	$(".logo_2").show("slow");
        $(".menu_2").hide("slow");

        $(".btn1").show("slow");
        $(".btn2").hide("slow");
    });
    
});