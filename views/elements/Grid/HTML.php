<?php
print "$returnHTML";
?>
<script>
    function _G_foundCheckedRowInGrid_returnObject(idGreed)
    { //возвращает массив выбранных элементов
        var nameObject = "#" + idGreed;
        var allCheckedLS = $(nameObject).find(".id").find("input");
        var retData = {};
        var j = 0;
        for (var i = 0; i < allCheckedLS.length; i++) {
            if (allCheckedLS[i].checked == true) {
                retData[j] = allCheckedLS[i].name;
                j++;
            }
        }
        if ($.isEmptyObject(retData)){
            return false;
        }else{
            return retData;//возвращает массив выбранных элементов
        }
    }

    function clearEditWindow(idBlock)
    {
        if (!$('div').is('#'+idBlock)){
            document.getElementById(idBlock).innerHTML = ' ';
        }
    }

    function eventGreed(t,e) {
        row = $(t).attr('data-row');
        row = row-0;
        if (e.code=="ArrowUp"){
            row = row - 1;
            classInput = "."+column+"_"+row;
            $(classInput).select();
            $(classInput).focus();
        }
        if (e.code=="ArrowDown"){
            row = row + 1;
            classInput = "."+column+"_"+row;
            $(classInput).select();
            $(classInput).focus();
        }
    }

    function _G_getAllDataInputFromGrid_returnObject(idGreed)
    {
        var allInputs = $("#"+idGreed).find( $(".inpData") );
        var outArray =  {};
        for (var i = 0 ; i<allInputs.length ; i++){
            var elementArray = allInputs[i];
            var field = $(elementArray).attr('data-field');
            var id = elementArray.name;
            var val = elementArray.value;
            if (typeof outArray[field] == "undefined"){
                outArray[field] = {};
            }
            outArray[field][id] = val;
        }
        return outArray;
    }

</script>