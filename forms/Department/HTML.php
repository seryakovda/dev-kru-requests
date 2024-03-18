<script>
    function loadscript()
    {
        startEvent();
    }

    function addDepartment()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"addDepartment_0()",
                message:"Введите название ресурса на доступ",
                oldValue:'Фамилия инициалы',
                value:''
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function addDepartment_0()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Department",r1:'addDepartment_0',
                name:_G_varVal
            },
            dataType: 'text',
            success: function(){
                refresh_Department();
                closeBlockAPP();
            }
        });
    }
    function deleteDepartment()
    {

        var name_greed = '<?php print $this->nameGreed; ?>';
        if (insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed)){
            _G_idDepartment = insertedRow[0];
            _G_BlockAppQuestion('Подтверрдите удаление','deleteDepartment_1()')
        }else{
            _G_BlockAppMessage('Вы не выбрали заявку для входа');
        }

    }

    function deleteDepartment_1()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Department",r1:'deleteDepartment_1',
                id_Department:_G_idDepartment},
            dataType: 'text',
            success: function(){
                closeBlockAPP();

                <?php
                print "refresh_$this->objectFullName();";
                ?>
            }
        });
    }
    function  editDepartment()
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        var insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed);
        var _G_idDepartment = insertedRow[0];
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Department",r1:'editDepartment',
                id_Department:_G_idDepartment},
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data)
            }
        });
    }

    function updatePropertyDepartment(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"updatePropertyDepartment_0()",
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

    function updatePropertyDepartment_catalog(Property,catalog)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"updatePropertyDepartment_0()",
                class:catalog
             },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function updatePropertyDepartment_0()
    {
        if (typeof _G_varVal === 'object'){
            _G_varVal = _G_varVal[0];
        }
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Department",r1:'updatePropertyDepartment_0',
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
