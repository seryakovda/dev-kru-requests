
<style>
    /*Стили Скелета приложения*/
    .fixHeadBlock {
        /*.shadowNormal*/
        /*.backgroundNormal*/
        margin: 0 auto;
        width: 100%;
        position:fixed;
        top:0px;

    }
    .fixLeftBlock{
        /*.shadowNormal*/
        /*.backgroundNormal*/
        position:absolute;
        top:90px;
        left:10px;
        width: 100%;
        bottom:75px;

    }
    .mainContent{
        position: absolute;
        top:90px;
        left:0px;
        width: 100%;
    }

    .calendar {
        position: fixed;
        top: 10px;
        left: 460px;
    }
</style>

<code>
    <?php
    print "$printHTTP";
    ?>

</code>
<script>



    function run(className)
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:className},
            dataType: 'text',
            success: function(data){
                    integrationsScriptCSS("mainContent",data)
            }
        });
    }

     function loadscript() {
         document.title = 'Формирование заявок на доступ';
     }

</script>