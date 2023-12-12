// Product Add Modal
$(document).ready(function () {
    $('.open-modal').click(function () {
        var serialValue = $("#normalinvoice tbody tr").length;
        $("#serial_no").val(serialValue);
    });
});
// Purchase Add Modal
// $(document).ready(function () {
//     $('.open-modal-purchase').click(function () {
//         var rowID = $(this).closest("tr").data("rowid");
//         var serialValue = $("#normalinvoice tbody tr").length;
//         $("#serial_no").val(serialValue);
//         alert(rowID);
//         var product_name = $('#product_name_' + serialValue).val();
//         var product_id = $('#product_id_' + serialValue).val();
//         $("#modal_purchase_product_name").val(product_id);
//         var inputElement = document.getElementById("modal_purchase_product_name");
//         inputElement.placeholder = product_name;
//     });
// });
$(document).ready(function() {
    $(document).on('click', '.open-modal-purchase', function() {
        var rowID = $(this).closest("tr").data("rowid");
        var serialValue = $("#normalinvoice tbody tr").length;
        $("#serial_no").val(serialValue);
        // alert(rowID);
        var product_name = $('#product_name_' + rowID).val();
        // alert(product_name);
        var product_id = $('#product_id_' + rowID).val();
        // alert(product_id);
        // $("#modal_purchase_product_name").val(product_id);
        var modal_purchase_product_name = document.getElementById("modal_purchase_product_name");
        modal_purchase_product_name.value = product_name;
        var modal_purchase_product_id = document.getElementById("modal_purchase_product_id");
        modal_purchase_product_id.value = product_id;
    });
});

$(document).ready(function() {
    $(document).on('click', '.open-modal-deduction', function() {
        var rowID = $(this).closest("tr").data("rowid");
        var serialValue = $("#normalinvoice tbody tr").length;
        $("#serial_no").val(serialValue);
        // alert(rowID);
        var product_name = $('#product_name_' + rowID).val();
        // alert(product_name);
        var product_id = $('#product_id_' + rowID).val();
        // alert(product_id);
        $("#modal_purchase_product_name").val(product_id);
        var inputElement = document.getElementById("modal_purchase_product_name");
        inputElement.placeholder = product_name;
    });
});
// Supplier Add Modal
$(document).ready(function () {
    $('.open-modal-supplier').click(function () {
        var serialValue = $("#normalinvoice tbody tr").length;
        $("#serial_no").val(serialValue);
        var element = document.getElementById('modal_purchase_supplier_id');
        element.setAttribute('id', 'modal_purchase_supplier_id_'+ serialValue);
        
    });
});

$(document).ready(function() {
    // Modal Add Customer Function
    "use strict";
    $("#add_customer").submit(function(e){
        e.preventDefault();
        var customer_message   = $("#customer_message");
        var customer_id      = $("#autocomplete_customer_id");
        var customer_name    = $("#customer_name");
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            beforeSend: function()
            {
                customer_message.removeClass('hide');
            
            },
            success: function(data)
            {
                // console.log(data);
                if (data.status == true) {
                    customer_message.addClass('alert-success').removeClass('alert-danger').html(data.message);
                    customer_id.val(data.customer_id);
                    customer_name.val(data.customer_name);
                    $("#customer_add").modal('hide');
                } else {
                    customer_message.addClass('alert-danger').removeClass('alert-success').html(data.error_message);
                }
            },
            error: function(xhr)
            {
                alert('failed!');
            }

        });

    });
    // Modal Add service Function
    $("#add_service").submit(function(e){
        e.preventDefault();
        var service_message   = $("#service_message");
        var service_id      = $("#autocomplete_service_id");
        var service_name    = $("#service_name");
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            beforeSend: function()
            {
                service_message.removeClass('hide');
            
            },
            success: function(data)
            {
                console.log(data);
                if (data.status == true) {
                    service_message.addClass('alert-success').removeClass('alert-danger').html(data.message);
                    service_id.val(data.service_id);
                    service_name.val(data.service_name);
                    $("#service_add").modal('hide');
                } else {
                    service_message.addClass('alert-danger').removeClass('alert-success').html(data.error_message);
                }
            },
            error: function(xhr)
            {
                alert('failed!');
            }

        });

    });
    // Modal Add Product Function
    $("#add_product").submit(function(e){
        e.preventDefault();
        var serial = $("#serial_no").val();
        alert(serial)
        // console.log(serial)
        var product_message   = $("#product_message");
        var product_id      = $("#product_id_" + serial); //"product_id_" + serial;
        var product_name    = $("#product_name_"+serial);
        var selling_price    = $("#selling_price_"+serial);
        var stock    = $("#stock_"+serial);
        console.log(product_id)
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            beforeSend: function()
            {
                product_message.removeClass('hide');
            
            },
            success: function(data)
            {
                console.log(data);
                if (data.status == true) {
                    product_message.addClass('alert-success').removeClass('alert-danger').html(data.message);
                    product_id.val(data.product_id);
                    product_name.val(data.product_name);
                    selling_price.val(data.price);
                    stock.val(0);
                    $("#product_add").modal('hide');
                } else {
                    product_message.addClass('alert-danger').removeClass('alert-success').html(data.error_message);
                }
            },
            error: function(xhr)
            {
                alert('failed!');
            }

        });

    });
    // Modal Add Supplier Function
    $("#add_supplier").submit(function(e){
        e.preventDefault();
        var serial = $("#serial_no").val();
        // console.log(serial)
        var supplier_message   = $("#supplier_message");
        var modal_id      = $("#modal_purchase_supplier_id_" + serial); //"product_id_" + serial;
        // console.log(modal_id)
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            beforeSend: function()
            {
                supplier_message.removeClass('hide');
            
            },
            success: function(data)
            {
                console.log(data);
                if (data.status == true) {
                

                    supplier_message.addClass('alert-success').removeClass('alert-danger').html(data.message);
                    var selectElement = document.getElementById(modal_id);

                    if (selectElement) {
                        // Get the option element you want to change by its index
                        var optionToChange = selectElement.options[1]; // Change the index as needed
        
                        // Set the new text for the option element
                        optionToChange.textContent = 'New Option Text';
                    }
                    $("#supplier_add").modal('hide');
                } else {
                    supplier_message.addClass('alert-danger').removeClass('alert-success').html(data.error_message);
                }
            },
            error: function(xhr)
            {
                alert('failed!');
            }

        });

    });
    // Modal Add Product Function   purchase_price
    $("#add_purchase").submit(function(e){
        e.preventDefault();
        var serial = $("#serial_no").val();
        var purchase_message   = $("#purchase_message");
        var purchase_price      = $("#purchase_price_" + serial);
        var stock      = $("#stock_" + serial);
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            beforeSend: function()
            {
                purchase_message.removeClass('hide');
            
            },
            success: function(data)
            {
                // console.log(data);
                if (data.status == true) {
                    purchase_message.addClass('alert-success').removeClass('alert-danger').html(data.message);
                    purchase_price.val(data.purchase_price);
                     let product_id = $("#product_id_" + serial).val();
                     let outlet_id = data.outlet_id
                     var csrf_test_name = $('[name="csrf_test_name"]').val();
                     var base_url = $("#base_url").val();
                    $.ajax
                        ({
                            type: "POST",
                            url: base_url + "Cinvoice/retrieve_product_data_inv",
                            data: { product_id: product_id,outlet_id: outlet_id, csrf_test_name: csrf_test_name },
                            cache: false,
                            success: function (data) {
                                var obj = jQuery.parseJSON(data);
                                console.log(obj);
                                stock.val(obj.stock);
                            }
                        });
                    
                    $("#purchase_add").modal('hide');
                } else {
                    purchase_message.addClass('alert-danger').removeClass('alert-success').html(data.error_message);
                }
            },
            error: function(xhr)
            {
                alert('failed!');
            }

        });

    });
});
    // Sevice Search
    $(document).ready(function() {
    // Initialize autocomplete when the page is ready
    var csrf_test_name = $("[name=csrf_test_name]").val();
    var base_url = $("#base_url").val();

    $('#service_name').autocomplete({
        minLength: 0,
        source: function(request, response) {
            var service_name = $('#service').val();
            $.ajax({
                url: base_url + "Cservice/service_autocomplete",
                method: 'post',
                dataType: "json",
                data: {
                    term: request.term,
                    service_name: service_name,
                    csrf_test_name: csrf_test_name
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        focus: function(event, ui) {
            console.log(ui)
            $(this).val(ui.item.label);
            return false;
        },
        select: function(event, ui) {
            $(this).parent().parent().find("#autocomplete_service_id").val(ui.item.value);
            $(this).unbind("change");
            return false;
        }
    });
    });
    // Purchase Calculation
    "use strict";
    function purchase_modal_calculation() {
    var modal_purchase_quantity = $("#modal_purchase_quantity").val();
    var modal_purchase_price = $("#modal_purchase_price").val();
    $("#modal_purchase_total_price").val((parseFloat(modal_purchase_price) || 0) * (parseFloat(modal_purchase_quantity) || 0));
    $("#modal_purchase_due_price").val((parseFloat(modal_purchase_price) || 0) * (parseFloat(modal_purchase_quantity) || 0));

    }
//Add Input Field Of Row
    "use strict";
function addInputField(t) {
    var row = $("#normalinvoice tbody tr").length;
    var count = row + 1;
       var tab1 = 0;
       var tab2 = 0;
       var tab3 = 0;
       var tab4 = 0;
       var tab5 = 0;
       var tab6 = 0;
       var tab7 = 0;
       var tab8 = 0;
       var tab9 = 0;
    var limits = 500;
    var taxnumber = $("#txfieldnum").val();
    var tbfild ='';
    if (count == limits)
        alert("You have reached the limit of adding " + count + " inputs");
    else {
        // var a = "service_name" + count,
                tabindex = count * 5,
                e = document.createElement("tr");
                e.setAttribute("data-rowid", count);
        tab1 = tabindex + 1;
        tab2 = tabindex + 2;
        tab3 = tabindex + 3;
        tab4 = tabindex + 4;
        tab5 = tabindex + 5;
        tab6 = tabindex + 6;
        tab7 = tabindex + 7;
        tab8 = tabindex + 8;
        tab9 = tabindex + 9;
        e.innerHTML = "<td><input type='text' name='product_name' onkeypress='invoice_productList(" + count + ");' class='form-control productSelection common_product' placeholder='Product Name' id='product_name_" + count + "' required tabindex='" + tab1 + "'><input type='hidden' class='common_product autocomplete_hidden_value  product_id_" + count + "' name='product_id[]' id='product_id_" + count + "'>" +
        "<a href='#' class='client-add-btn btn btn-success open-modal-button' data-serial='" + count + "' aria-hidden='true' data-toggle='modal' data-target='#product_add'>" +
        "<i class='ti-plus m-r-6'></i>" +
        "</a>" +
        "</td><td> <input type='text' name='stock[]' required='required' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='stock_" + count + "' class='stock_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab2 + "'/></td><td><input type='text' name='purchase_price_[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='purchase_price_" + count + "' class='purchase_price form-control text-right' required placeholder='0.00' min='0' tabindex='" + tab3 + "'><input type='text' name='total_purchase_quantity_price[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='total_purchase_quantity_price_" + count + "' class='total_purchase_quantity_price form-control text-right' required placeholder='0.00' min='0' tabindex='" + tab3 + "'><br>" +
        "<a href='#' class='btn btn-info open-modal-purchase open-modal-button' data-serial='" + count + "' aria-hidden='true' data-toggle='modal' data-target='#purchase_add'>" +
        "Add Purchase" +
        "</a>" +
        "</td><td><input type='text' name='selling_price[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='selling_price_" + count + "' class='form-control text-right selling_price' placeholder='0.00' min='0' tabindex='" + tab4 + "' /><input type='hidden' name='total_selling_price[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='total_selling_price_" + count + "' class='form-control text-right total_selling_price' placeholder='0.00' min='0' tabindex='" + tab4 + "' /></td><td><input type='text' name='quantity[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='quantity_" + count + "' class='form-control text-right quantity' placeholder='0.00' min='0' tabindex='" + tab4 + "' /></td><td> <input type='text' name='deduction_fund[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='deduction_fund_" + count + "' class='deduction_fund_" + count + " deduction_fund form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab2 + "'><br>" +
        "<a href='#' class='btn btn-info open-modal-deduction_button open-modal-button' data-serial='" + count + "' aria-hidden='true' data-toggle='modal' data-target='#deduction_add'>" +
        "Add Deduction" +
        
        "</td><td><input class='datepicker warranty_date form-control' type='text' size='50' name='warranty_date[]' id='warranty_date_" + count + ";' value='' tabindex='6'><input type='text' name='claim_percentage[]' onkeyup='quantity_calculate(" + count + ");' onchange='quantity_calculate(" + count + ");' id='claim_percentage_" + count + "' class='claim_percentage form-control text-right' placeholder='0.00' min='0' tabindex='" + tab3 + "'><button tabindex='" + tab5 + "' style='text-align: right;' class='btn btn-danger' type='button' value='Delete' onclick='deleteRow(this)'><i class='fa fa-close'></i></button></td>",
                document.getElementById(t).appendChild(e),
                
                // document.getElementById(a).focus(),
                document.getElementById("add_invoice_item").setAttribute("tabindex", tab6);
        document.getElementById("full_paid_tab").setAttribute("tabindex", tab8);
        document.getElementById("add_invoice").setAttribute("tabindex", tab9);
        $(".warranty_date").datepicker({
            dateFormat: 'yy-mm-dd', // Set the desired date format
        });
        count++
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('keypress', function(event) {
        if (event.target.classList.contains('productSelection')) {
            // Get the serial number from the element's ID
            var sl = event.target.id.split('_').pop();
            invoice_productList(sl);
        }
    });
});
"use strict";
function invoice_productList(sl) {
// alert("test")
    var outlet_id = $("#outlet_id").val();
    var selling_price = '#selling_price_' + sl;
    var product_id = '#product_id_' + sl;
    var purchase_price = '#purchase_price_' + sl;
    var stock = '#stock_' + sl;
    var csrf_test_name = $('[name="csrf_test_name"]').val();
    var base_url = $("#base_url").val();
    // Auto complete
    var options = {
        minLength: 0,
        source: function (request, response) {
            var product_name = $('#product_name_' + sl).val();
            // alert(product_name)
            $.ajax({
                url: base_url + "Cinvoice/autocompleteproductsearch",
                method: 'post',
                dataType: "json",
                data: {
                    term: request.term,
                    product_name: product_name,
                    pr_status: 1,
                    csrf_test_name: csrf_test_name,
                },
                success: function (data) {
                    response(data);

                }
            });
        },
        focus: function (event, ui) {
            $(this).val(ui.item.label);
            return false;
        },
        select: function (event, ui) {
            $(this).parent().parent().find(".autocomplete_hidden_value").val(ui.item.value);
            $(this).val(ui.item.label);
            var id = ui.item.value;
            $(product_id).val(id);
            var base_url = $('.baseUrl').val();
            var customer_id = $('#autocomplete_customer_id').val();
            console.log(id);
            $.ajax
                ({
                    type: "POST",
                    url: base_url + "Cinvoice/retrieve_product_data_inv",
                    data: { product_id: id, customer_id: customer_id, outlet_id: outlet_id, csrf_test_name: csrf_test_name },
                    cache: false,
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        //  console.log(obj);
                        //  console.log(selling_price);
                         $(selling_price).val(obj.price);
                         $(purchase_price).val(obj.price);
                         $(stock).val(obj.stock);
                         quantity_calculate(sl);

                    }
                });

            $(this).unbind("change");
            return false;
        }
    }

    $('body').on('keypress.autocomplete', '.productSelection', function () {
        $(this).autocomplete(options);
    });

}
//Invoice full paid
"use strict";
function full_paid() {
    var grandTotal = $("#net_total").val();
    $("#paid_amount").val(grandTotal);
}

//Quantity calculate
    "use strict";
    function quantity_calculate(item) {
        let total_selling_price = 0;
        let total_deduction_price = 0;
        let total_service_price = 0;
        let total_purchase_price = 0;
        let net_total = 0;
        let service_charge = $("#service_charge").val(); total_purchase_quantity_price_1
        let selling_price = $("#selling_price_"+item).val();
        let purchase_price = $("#purchase_price_"+item).val();
        
        let quantity = $("#quantity_"+item).val();
        if(quantity > 0)
        {
            if(selling_price < purchase_price)
            {
                alert("Sellling price should be greater !")
                $("#selling_price_"+item).val(purchase_price);
            }
        }
        $("#total_purchase_quantity_price_"+item).val(parseFloat(purchase_price) * parseFloat(quantity));
        parseFloat($("#total_selling_price_"+item).val(parseFloat(selling_price) * parseFloat(quantity)));
        $(".total_selling_price").each(function () {
            isNaN(this.value) || 0 == this.value.length || (total_selling_price += parseFloat(this.value))
        });
        $(".total_purchase_quantity_price").each(function () {
            isNaN(this.value) || 0 == this.value.length || (total_purchase_price += parseFloat(this.value))
        });
        $(".deduction_fund").each(function () {
            isNaN(this.value) || 0 == this.value.length || (total_deduction_price += parseFloat(this.value))
        });
        total_service_price = total_selling_price + parseFloat(service_charge); 
        $("#total_service_price").val(total_service_price);
        $("#total_deduction").val(total_deduction_price);
        let discount_amount = $("#discount_amount").val() ? $("#discount_amount").val() : 0;
        let discount_percentage = $("#discount_percentage").val() ? $("#discount_percentage").val() : 0;
        let total_discount = (parseFloat(discount_amount) + (discount_percentage == 0 ? 0 : ((discount_percentage*(total_service_price-discount_amount))/100)));
        $("#total_discount_amount").val(total_discount);
        $("#total_selling_price").val(total_selling_price);
        $("#total_purchase_price").val(total_purchase_price);
        $("#grand_total").val(total_service_price - total_discount);
        let previous_due =  parseFloat($("#previous").val());       
        let grand_previous = (total_service_price - total_discount) + previous_due;
        let rounding = parseFloat(Math.round(grand_previous) - grand_previous); 
        $("#rounding").val(rounding); 
        net_total = rounding + grand_previous;
        $("#net_total").val(net_total);
        let paid_amount =  $("#paid_amount").val();
        if(net_total >= paid_amount)
        {
            $("#due_amount").val(net_total - paid_amount);
            $("#change_amount").val(0);
        }
        else{
            $("#change_amount").val(paid_amount - net_total);
            $("#due_amount").val(0);
        }
        
        console.log("Total Service Price "+ total_service_price)
        console.log("Total Deduction Price "+ total_deduction_price)
        console.log("Total Discount Price "+ total_discount)
        console.log("Total Previous Due "+ previous_due)
    }
   "use strict";
    function customer_due(id){
    var csrf_test_name = $("[name=csrf_test_name]").val();
    var base_url  = $("#base_url").val();
    $.ajax({
    url: base_url + 'Cinvoice/previous',
    type: 'post',
    data: {customer_id:id,csrf_test_name:csrf_test_name}, 
    success: function (response){

        // $("#previous").val(msg.balance);
        var jsonObject = JSON.parse(response);
        console.log(jsonObject)
        $("#previous").val(jsonObject.balance);
    },
    error: function (xhr, desc, err){
            alert('failed');
    }
    });        
    }
    "use strict";
    function customer_autocomplete(sl) {

    // var customer_id = $('#customer_id').val();
    var csrf_test_name = $("[name=csrf_test_name]").val();
    var base_url  = $("#base_url").val();
    var options = {
        minLength: 0,
        source: function( request, response ) {
            var customer_name = $('#customer_name').val();
            
        $.ajax( {
            url: base_url + "Cinvoice/customer_autocomplete",
            method: 'post',
            dataType: "json",
            data: {
            term: request.term,
            customer_id:customer_name,
            csrf_test_name:csrf_test_name
            },
            success: function( data ) {
            console.log(data)
            response( data );

            }
        });
        },
        focus: function( event, ui ) {
            $(this).val(ui.item.label);
            return false;
        },
        select: function( event, ui ) {
            $(this).parent().parent().find("#autocomplete_customer_id").val(ui.item.value.id); 
            // var customer_id          = ui.item.value.id;
            customer_due(ui.item.value.id);
            // $('#customer_id').val(customer_id)

            $(this).unbind("change");
            return false;
        }
    }

    $('body').on('keydown.autocomplete', '#customer_name', function() {
        $(this).autocomplete(options);
    });

    }
    "use strict";
    function deleteRow(t) {
        var a = $("#normalinvoice > tbody > tr").length;

        if (1 == a)
            alert("There only one row you can't delete.");
        else {
            var e = t.parentNode.parentNode;
            e.parentNode.removeChild(e),
                    calculateSum();
            invoice_paidamount();

            var current = 1;
            $("#normalinvoice > tbody > tr td input.productSelection").each(function () {
                current++;
                $(this).attr('id', 'product_name' + current);
            });
            var common_qnt = 1;
            $("#normalinvoice > tbody > tr td input.common_qnt").each(function () {
                common_qnt++;
                $(this).attr('id', 'total_qntt_' + common_qnt);
                $(this).attr('onkeyup', 'quantity_calculate('+common_qnt+');');
                $(this).attr('onchange', 'quantity_calculate('+common_qnt+');');
            });
            var common_rate = 1;
            $("#normalinvoice > tbody > tr td input.common_rate").each(function () {
                common_rate++;
                $(this).attr('id', 'price_item_' + common_rate);
                $(this).attr('onkeyup', 'quantity_calculate('+common_qnt+');');
                $(this).attr('onchange', 'quantity_calculate('+common_qnt+');');
            });
            var common_discount = 1;
            $("#normalinvoice > tbody > tr td input.common_discount").each(function () {
                common_discount++;
                $(this).attr('id', 'discount_' + common_discount);
                $(this).attr('onkeyup', 'quantity_calculate('+common_qnt+');');
                $(this).attr('onchange', 'quantity_calculate('+common_qnt+');');
            });
            var common_total_price = 1;
            $("#normalinvoice > tbody > tr td input.common_total_price").each(function () {
                common_total_price++;
                $(this).attr('id', 'total_price_' + common_total_price);
            });




        }
    }
    
'use strict';
function delete_row(e) {
    // alert(e)
    e.closest('.row_div').remove();
}
"use strict"
function calc_paid() {
    let paid_amount = 0;

    $(".p_amount").each(function () {
        isNaN(this.value) || 0 == this.value.length || (paid_amount += parseFloat(this.value))
    });

    $("#paid_amount").val((paid_amount).toFixed(2, 2));
    quantity_calculate()
}
function modal_calc_paid() {
    let paid_amount = 0;

    $(".p_amount_modal").each(function () {
        isNaN(this.value) || 0 == this.value.length || (paid_amount += parseFloat(this.value))
    });
    let total_price = $("#modal_purchase_total_price").val();
    $("#modal_purchase_paid_price").val((paid_amount).toFixed(2, 2));
    if(paid_amount > total_price)
    {
    $("#modal_purchase_due_price").val(0.00);

    }
    else{
        $("#modal_purchase_due_price").val((total_price - paid_amount).toFixed(2, 2));
    }
    
}
    'use strict';
    function add_pay_row(sl) {
        var count = $("#count");
        sl = count.val();
        sl += 1;
        var bkash_list = $("#bkash_list").val();
        var nagad_list = $("#nagad_list").val();
        var rocket_list = $("#rocket_list").val();
        var bank_list = $("#bank_list").val();
        var card_list = $("#card_list").val();
        var pay_div = $("#pay_div");
        pay_div.append(
    
            '<div class="row_div">'
            + '<div class="row">'
                + ' <div class="col-sm-2">'
                + ' <p style="margin-left: 12px; padding: 0;"> <b>Payment Type <i class="text-danger">*</i></b></p>'
                + '</div>'
            + ' <div class="form-group col-md-2">'
                    + '  <div class="selectWrapper">'
                    + '<select name="paytype[]" class="form-control selectBox" required="" onchange="bank_paymet(this.value, ' + sl + ')" tabindex="3">'
                    + '<option value="1">Cash</option>'
                    + '<option value="2">Cheque</option>'
                    + '<option value="4">Bank</option>'
                    + ' <option value="3">Bkash</option>'
                    + ' <option value="5">Nagad</option>'
                    + ' <option value="7">Rocket</option>'
                    + ' <option value="6">Card</option>'
                    + '</select>'
                    + '</div>'
            + '</div>'
            + ' <div class="col-sm-2">'
            +    ' <p style="margin-left: 12px; padding: 0;"> <b>Amount<i class="text-danger">*</i></b></p>'
             + '</div>'
            + ' <div style="margin-top: 8px; padding: 0;" class="col-sm-2" id="ammnt_' + sl + '">'
                 + '<input class="form-control p_amount" placeholder="0.00" type="text" name="p_amount[]" onchange="calc_paid()" onkeyup="calc_paid()">'
            + '</div>'
    
            + '<div class="col-sm-1">'
            + '<a id="delete_btn" onclick="delete_row(this)" class=" client-add-btn btn btn btn btn-danger"><i class="fa fa-trash"></i></a>'
            + '</div>'
    
            + '</div>'
    
            + '<div class="row margin-top10  m-0" id="bank_div_' + sl + '"  style="display:none;">'
    
            + ' <div class="col-sm-12">'
    
            + ' <div class="form-group row">'
    
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bank Name<i class="text-danger">*</i></b></p>'
            + '</div>'
    
            + ' <div class="col-sm-3">'
            + ' <input type="text" name="bank_id" class="form-control" id="bank_id_' + sl + '" placeholder="Bank" required>'
            + ' </div>'
    
            + '<div class="col-sm-1">'
            + ' <a href="#" class="client-add-btn btn btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>'
            + '</div>'
    
            + ' </div>'
    
            + '</div>'
    
            + '</div>'
    
    
    
            + '<div class="row margin-top10  m-0"  id="bank_div_m_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
    
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bank Name<i class="text-danger">*</i></b></p>'
            + '</div>'
    
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="bank_id_m[]" required class="form-control bankpayment" id="bank_id_m_' + sl + '">'
            + bank_list
            + '</select>'
            + '</div>'
    
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
    
    
            + '<div class="row margin-top10  m-0" id="bkash_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bkash Number<i class="text-danger">*</i></b></p>'

            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="bkash_id[]" required class="form-control bankpayment" id="bkash_id_' + sl + '">'
            + bkash_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + ' </div>'
    
    
            + '<div class="row margin-top10  m-0"  id="nagad_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Nagad Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="nagad_id[]" class="form-control bankpayment" id="nagad_id_' + sl + '">'
            + nagad_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
    
            + '</div>'
            + ' </div>'
            + '</div>'
    
            + '<div class="row margin-top10  m-0" id="rocket_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Rocket Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="rocket_id[]" class="form-control bankpayment" id="rocket_id_' + sl + '">'
            + rocket_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="row margin-top10  m-0" id="card_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Card Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="card_id[]" class="form-control bankpayment" id="card_id_' + sl + '">'
            + card_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
    
            + '</div>'
    
            + '</div>'
    
    
    
            + '</div>'
    
            + '</div>'
    
    
    
    
            + '</div>'
    
            + '</div>'
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
        );
        count.val(sl + 1);
    }
    function add_pay_row_modal(sl) {
        var count = $("#count_modal");
        sl = count.val();
        sl += 1;
        var bkash_list = $("#bkash_list").val();
        var nagad_list = $("#nagad_list").val();
        var rocket_list = $("#rocket_list").val();
        var bank_list = $("#bank_list").val();
        var card_list = $("#card_list").val();
        var pay_div_modal = $("#pay_div_modal");
        pay_div_modal.append(
    
            '<div class="row_div">'
            + '<div class="row">'
                + ' <div class="col-sm-2">'
                + ' <p style="margin-left: 12px; padding: 0;"> <b>Payment Type <i class="text-danger">*</i></b></p>'
                + '</div>'
            + ' <div class="form-group col-md-2">'
                    + '  <div class="selectWrapper">'
                    + '<select name="paytype[]" class="form-control selectBox" required="" onchange="bank_paymet(this.value, ' + sl + ')" tabindex="3">'
                    + '<option value="1">Cash</option>'
                    + '<option value="2">Cheque</option>'
                    + '<option value="4">Bank</option>'
                    + ' <option value="3">Bkash</option>'
                    + ' <option value="5">Nagad</option>'
                    + ' <option value="7">Rocket</option>'
                    + ' <option value="6">Card</option>'
                    + '</select>'
                    + '</div>'
            + '</div>'
            + ' <div class="col-sm-2">'
            +    ' <p style="margin-left: 12px; padding: 0;"> <b>Amount<i class="text-danger">*</i></b></p>'
             + '</div>'
            + ' <div style="margin-top: 8px; padding: 0;" class="col-sm-2" id="ammnt_' + sl + '">'
                 + '<input class="form-control p_amount_modal" placeholder="0.00" type="text" name="p_amount[]" onchange="modal_calc_paid()" onkeyup="modal_calc_paid()">'
            + '</div>'
    
            + '<div class="col-sm-1">'
            + '<a id="delete_btn" onclick="delete_row(this)" class=" client-add-btn btn btn btn btn-danger"><i class="fa fa-trash"></i></a>'
            + '</div>'
    
            + '</div>'
    
            + '<div class="row margin-top10  m-0" id="bank_div_' + sl + '"  style="display:none;">'
    
            + ' <div class="col-sm-12">'
    
            + ' <div class="form-group row">'
    
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bank Name<i class="text-danger">*</i></b></p>'
            + '</div>'
    
            + ' <div class="col-sm-3">'
            + ' <input type="text" name="bank_id" class="form-control" id="bank_id_' + sl + '" placeholder="Bank" required>'
            + ' </div>'
    
            + '<div class="col-sm-1">'
            + ' <a href="#" class="client-add-btn btn btn-info" aria-hidden="true" data-toggle="modal" data-target="#cheque_info"><i class="fa fa-file m-r-2"></i></a>'
            + '</div>'
    
            + ' </div>'
    
            + '</div>'
    
            + '</div>'
    
    
    
            + '<div class="row margin-top10  m-0"  id="bank_div_m_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
    
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bank Name<i class="text-danger">*</i></b></p>'
            + '</div>'
    
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="bank_id_m[]" required class="form-control bankpayment" id="bank_id_m_' + sl + '">'
            + bank_list
            + '</select>'
            + '</div>'
    
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
    
    
            + '<div class="row margin-top10  m-0" id="bkash_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Bkash Number<i class="text-danger">*</i></b></p>'

            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="bkash_id[]" required class="form-control bankpayment" id="bkash_id_' + sl + '">'
            + bkash_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + ' </div>'
    
    
            + '<div class="row margin-top10  m-0"  id="nagad_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Nagad Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="nagad_id[]" class="form-control bankpayment" id="nagad_id_' + sl + '">'
            + nagad_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
    
            + '</div>'
            + ' </div>'
            + '</div>'
    
            + '<div class="row margin-top10  m-0" id="rocket_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Rocket Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="rocket_id[]" class="form-control bankpayment" id="rocket_id_' + sl + '">'
            + rocket_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="row margin-top10  m-0" id="card_div_' + sl + '"  style="display:none;">'
            + ' <div class="col-sm-12">'
            + ' <div class="form-group row">'
            + ' <div class="col-sm-3 ">'
            + ' <p> <b>Card Number<i class="text-danger">*</i></b></p>'
            + '</div>'
            + '<div class="col-sm-3">'
            + ' <div class="selectWrapper">'
            + '<select name="card_id[]" class="form-control bankpayment" id="card_id_' + sl + '">'
            + card_list
    
            + '</select>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + ' </div>'
            + '</div>'
            + '</div>'
            + '</div>'
    
            + '</div>'
    
            + '</div>'
    
    
    
            + '</div>'
    
            + '</div>'
    
    
    
    
            + '</div>'
    
            + '</div>'
        );
        count.val(sl + 1);
    }
    "use strict";
    function bank_paymet(val, sl) {

        if (val == 2 || 3 || 4 || 5 || 6 || 7) {

            if (val == 2) {
                var style = 'block';
                document.getElementById('bank_id_' + sl).setAttribute("required", true);
                document.getElementById('ammnt_' + sl).style.display = 'none';
            } else {
                var style = 'none';
                document.getElementById('bank_id_' + sl).removeAttribute("required");
                document.getElementById('ammnt_' + sl).style.display = 'block';
            }

            document.getElementById('bank_div_' + sl).style.display = style;

            if (val == 3) {
                var style = 'block';
                document.getElementById('bkash_id_' + sl).setAttribute("required", true);

            } else {
                var style = 'none';
                document.getElementById('bkash_id_' + sl).removeAttribute("required");

            }

            document.getElementById('bkash_div_' + sl).style.display = style;


            if (val == 4) {
                var style = 'block';
                document.getElementById('bank_id_m_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('bank_id_m_' + sl).removeAttribute("required");
            }

            document.getElementById('bank_div_m_' + sl).style.display = style;

            if (val == 5) {
                var style = 'block';
                document.getElementById('nagad_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('nagad_id_' + sl).removeAttribute("required");
            }

            document.getElementById('nagad_div_' + sl).style.display = style;

            if (val == 7) {
                var style = 'block';
                document.getElementById('rocket_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('rocket_id_' + sl).removeAttribute("required");
            }

            document.getElementById('rocket_div_' + sl).style.display = style;

            if (val == 6) {
                var style = 'block';
                document.getElementById('card_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('card_id_' + sl).removeAttribute("required");
            }

            document.getElementById('card_div_' + sl).style.display = style;
        }
    }
    function bank_payment_modal(val, sl) {
        console.log(val + " " + sl)
        if (val == 2 || 3 || 4 || 5 || 6 || 7) {

            if (val == 2) {
                var style = 'block';
                document.getElementById('modal_bank_id_' + sl).setAttribute("required", true);
                document.getElementById('modal_ammnt_' + sl).style.display = 'none';
            } else {
                var style = 'none';
                document.getElementById('modal_bank_id_' + sl).removeAttribute("required");
                document.getElementById('modal_ammnt_' + sl).style.display = 'block';
            }

            document.getElementById('modal_bank_div_' + sl).style.display = style;

            if (val == 3) {
                var style = 'block';
                document.getElementById('modal_bkash_id_' + sl).setAttribute("required", true);

            } else {
                var style = 'none';
                document.getElementById('modal_bkash_id_' + sl).removeAttribute("required");

            }

            document.getElementById('modal_bkash_div_' + sl).style.display = style;


            if (val == 4) {
                var style = 'block';
                document.getElementById('modal_bank_id_m_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('modal_bank_id_m_' + sl).removeAttribute("required");
            }

            document.getElementById('modal_bank_div_m_' + sl).style.display = style;

            if (val == 5) {
                var style = 'block';
                document.getElementById('modal_nagad_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('modal_nagad_id_' + sl).removeAttribute("required");
            }

            document.getElementById('modal_nagad_div_' + sl).style.display = style;

            if (val == 7) {
                var style = 'block';
                document.getElementById('modal_rocket_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('modal_rocket_id_' + sl).removeAttribute("required");
            }

            document.getElementById('modal_rocket_div_' + sl).style.display = style;

            if (val == 6) {
                var style = 'block';
                document.getElementById('modal_card_id_' + sl).setAttribute("required", true);
            } else {
                var style = 'none';
                document.getElementById('modal_card_id_' + sl).removeAttribute("required");
            }

            document.getElementById('modal_card_div_' + sl).style.display = style;
        }
    }













 var count = 2,
        limits = 500;

      "use strict";
    function bank_info_show(payment_type)
    {
        if (payment_type.value == "1") {
            document.getElementById("bank_info_hide").style.display = "none";
        } else {
            document.getElementById("bank_info_hide").style.display = "block";
        }
    }


       "use strict";
    function active_customer(status)
    {
        if (status == "payment_from_2") {
            document.getElementById("payment_from_2").style.display = "none";
            document.getElementById("payment_from_1").style.display = "block";
            document.getElementById("myRadioButton_2").checked = false;
            document.getElementById("myRadioButton_1").checked = true;
        } else {
            document.getElementById("payment_from_1").style.display = "none";
            document.getElementById("payment_from_2").style.display = "block";
            document.getElementById("myRadioButton_2").checked = false;
            document.getElementById("myRadioButton_1").checked = true;
        }
    }

    $( document ).ready(function() {
            "use strict";
    $('#normalinvoice .toggle').on('click', function() {
    $('#normalinvoice .hideableRow').toggleClass('hiddenRow');
  })
});

        $('.ac').click(function () {
        $('.customer_hidden_value').val('');
    });
    $('#myRadioButton_1').click(function () {
        $('#customer_name').val('');
    });

    $('#myRadioButton_2').click(function () {
        $('#customer_name_others').val('');
    });
    $('#myRadioButton_2').click(function () {
        $('#customer_name_others_address').val('');
    });


    
    



