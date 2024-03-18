<script>
    function loadscript()
    {
    }

    // назание функции вызываается по умолчанию в елементе обновить
    <?php
    print "function  refresh_$this->objectFullName()";
    ?>
    {
        var name_div_greed = '<?php print $this->nameGreedDIV; ?>';
        var objectParentName = '<?php print $this->objectParentName; ?>';
        var objectName = '<?php print $this->objectName; ?>';
        var filter={};
        filter['name'] = $('<?php print "#name_$this->objectFullName"; ?>').val();
        var filterTXT = JSON.stringify(filter);
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:objectName,r1:"getListFilter",
                filter:filterTXT
            },
            dataType: 'text',
            success: function(data){ integrationsScriptCSS(name_div_greed,data)}
        });
    }

</script>
