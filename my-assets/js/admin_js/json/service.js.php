<?php
$cache_file = "service.json";
    header('Content-Type: text/javascript; charset=utf8');
?>

var serviceList = <?php echo file_get_contents($cache_file); ?> ;


APchange = function(event, ui){
	$(this).data("autocomplete").menu.activeMenu.children(":first-child").trigger("click");
}


"use strict";
function bank_paymet(val){

    if (val== 3 || 4){



        if(val==3){
            var style = 'block';
            document.getElementById('bkash_id').setAttribute("required", true);
        }else{
            var style ='none';
            document.getElementById('bkash_id').removeAttribute("required");
        }

        document.getElementById('bkash_div').style.display = style;

        if(val==4){
            var style = 'block';
            document.getElementById('bank_id_m').setAttribute("required", true);
        }else{
            var style ='none';
            document.getElementById('bank_id_m').removeAttribute("required");
        }

        document.getElementById('bank_div_m').style.display = style;


    }



}


