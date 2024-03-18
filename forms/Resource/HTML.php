<script>
    function loadscript()
    {
        startEvent();
    }
    function closeWindowResource()
    {
        document.getElementById("<?php print "WinMain__".$this->objectFullName;?>").remove();
    }

    function startEvent()
    {
        $('<?php print "#name_$this->objectFullName"; ?>').select();
        $('<?php print "#name_$this->objectFullName"; ?>').focus();
        $('<?php print "#name_$this->objectFullName"; ?>').keyup(function(event){
            if(event.keyCode == 13){
                <?php
                print "refresh_$this->objectFullName()";
                ?>
            }
        });
    }


    function addResource()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"addResource_0()",
                message:"Введите название ресурса на доступ",
                oldValue:'Новое название',
                value:''
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function addResource_0()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Resource",r1:'addResource_0',
                name:_G_varVal
            },
            dataType: 'text',
            success: function(){
                refresh_Resource();
                closeBlockAPP();
            }
        });
    }

    function  editResource()
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        var insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed);
        var id_Resource = insertedRow[0];
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Resource",r1:'editResource',
                id_Resource:id_Resource},
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data)
            }
        });
    }

    function deleteResource()
    {

        var name_greed = '<?php print $this->nameGreed; ?>';
        if (insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed)){
            _G_idResource = insertedRow[0];
            _G_BlockAppQuestion('Подтверрдите удаление','deleteResource_1()')
        }else{
            _G_BlockAppMessage('Вы не выбрали заявку для входа');
        }

    }

    function deleteResource_1()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Resource",r1:'deleteResource_1',
                id_Resource:_G_idResource},
            dataType: 'text',
            success: function(){
                closeBlockAPP();

                <?php
                print "refresh_$this->objectFullName();";
                ?>
            }
        });
    }

    function updatePropertyResource(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"updatePropertyResource_0()",
                message:"Введите",
                oldValue:olgValue,
                value:olgValue
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function updatePropertyResource_FIO(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"updatePropertyResource_0()",
                class:"Worker"
             },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function updatePropertyResource_0()
    {
        //clearEditWindow();
        //alert(_G_varVal)
        if (_G_varName == 'id_worker') {
            _G_varVal = _G_varVal[0];
        }
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Resource",r1:'updatePropertyResource_0',
                property:_G_varName,
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV?>",data);
                closeBlockAPP();
            }
        });
    }
</script>
