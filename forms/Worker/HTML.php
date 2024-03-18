<script>
    function loadscript()
    {
        startEvent();
    }
    function closeWindowRequest()
    {
        document.getElementById("<?php print "WinMain__".$this->objectFullName;?>").remove();
    }
    function addWorker()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"addWorker_0()",
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

    function addWorker_0()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Worker",r1:'addWorker_0',
                name:_G_varVal
            },
            dataType: 'text',
            success: function(){
                refresh_Worker();
                closeBlockAPP();
            }
        });
    }

    function  editWorker()
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        var insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed);
        var id_Worker = insertedRow[0];
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Worker",r1:'editWorker',
                id_Worker:id_Worker},
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data)
            }
        });
    }

    function updatePropertyWorker(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"updatePropertyWorker_0()",
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

    function updatePropertyWorker_FIO(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"addWorkerInToTable_0()",
                class:"Worker"
             },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }
    function updatePropertyWorker_0()
    {
        if (typeof _G_varVal === 'object'){
            _G_varVal = _G_varVal[0];
        }
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Worker",r1:'updatePropertyWorker_0',
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
