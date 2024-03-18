<style>
    .backgroundCalendar{
        position:relative;
        height: 170px;
        width: 265px;
        display:none;
            /*.shadowNormal*/
        /*.backgroundNormal*/
    }
    .inputYear{
        /*.*/
        /*.textColorBlack*/
        position: relative;
        width: 50px;
        border: none;
        background: none;
    }

</style>

    <?php
    print $HTML;
    ?>

<script>
    function clickMonth(mainID,inpMonth,textMonth,func,caption){
        inpMonth=inpMonth+1;
        var ob=$("#"+mainID);
        var ob0 = ob.find("#ButtonCalendar");

        var ob2=ob0.find("td");
        var ob3=ob.find("#secondaryDateYear");
        newSysYear=((ob3.val()-2006)*12)+inpMonth;
        _G_varVal = newSysYear;

        ob2.html(caption+" "+textMonth+" "+ob3.val());
        /*
        $("#"+mainID).children("#mainDateYear").val();
        $("#"+mainID).children("#mainDateMonth").val(textMonth);
*/
        $("#"+mainID).children("#BodyCalendar").slideUp(200);

        eval(func);
    }
    function clickYear(mainID) {
        $("#"+mainID).children("#BodyCalendar").slideDown(200);
    }

    function plusYear(mainID) {
        var ob=$("#"+mainID);
        var ob0 = ob.find("#secondaryDateYear");
        var secYear = ob0.val()-0;
        secYear=0+secYear+1;
        ob0.val(secYear);
    }
    function minusYear(mainID) {
        var ob=$("#"+mainID);
        var ob0 = ob.find("#secondaryDateYear");
        var secYear = ob0.val()-0;
        secYear=0+secYear-1;
        ob0.val(secYear);
    }
</script>