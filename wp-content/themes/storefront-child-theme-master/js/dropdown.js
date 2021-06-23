jQuery(function($){ 

$('.variations select').each(function (selectIndex, selectElement) {

    var select = $(selectElement);
    buildSelectReplacements(selectElement);

    select.parent().on('click', '.radioControl', function(){
        var selectedValue,
        currentlyChecked = $(this).hasClass('checked');
        $(this).parent().parent().find('.radioControl').removeClass('checked');
        if(!currentlyChecked){
            $(this).addClass('checked');
            selectedValue = $(this).data('value');
        } else {
            selectedValue = '';
        }

        select.val(selectedValue);
        select.find('option').each(function(){
            $(this).prop('checked', ($(this).val()==selectedValue) ? true : false);
        });
        select.trigger('change');
    });
    $('.reset_variations').on('mouseup', function(){
        $('.radioControl.checked').removeClass('checked');
    });

});

$('.variations_form').on('woocommerce_update_variation_values', function(){
    selectValues = {};
    $('.variations_form select').each(function(selectIndex, selectElement){
        var id = $(this).attr('id');
        selectValues[id] = $(this).val();
        $(this).parent().find('label').remove();

        //Rebuild Select Replacement Spans
        buildSelectReplacements(selectElement);

        //Reactivate Selectd Values
        $(this).parent().find('span').each(function(){
            if(selectValues[id]==$(this).data('value')){
                $(this).addClass('checked');
            }
        });
    });
});

function buildSelectReplacements(selectElement){
    var select = $(selectElement);
    var container = select.parent().hasClass('radioSelectContainer') ? select.parent() : $("<div class='radioSelectContainer' />");
    select.after(select.parent().hasClass('radioSelectContainer') ? '' : container);
    container.addClass(select.attr('id'));
    container.append(select);

    select.find('option').each(function (optionIndex, optionElement) {
        if($(this).val()=="") return;
        var label = $("<label />");
        container.append(label);

        $("<span class='radioControl' data-value='"+$(this).val()+"'>" + $(this).text() + "</span>").appendTo(label);
    });
}

});
