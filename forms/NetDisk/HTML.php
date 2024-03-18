<script>
    function loadscript()
    {
        startEvent();
    }
    function closeWindowNetDisk()
    {
        document.getElementById("<?php print "WinMain__".$this->objectFullName;?>").remove();
    }
    function addNetDisk()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"addNetDisk_0()",
                message:"Введите Сетевой путь диска",
                oldValue:'\\\\SHD\\',
                value:''
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function addNetDisk_0()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"NetDisk",r1:'addNetDisk_0',
                name:_G_varVal
            },
            dataType: 'text',
            success: function(){
                refresh_NetDisk();
                closeBlockAPP();
            }
        });
    }

    function  editNetDisk()
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        var insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed);
        var id_NetDisk = insertedRow[0];
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"NetDisk",r1:'editNetDisk',
                id_NetDisk:id_NetDisk
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data)
            }
        });
    }

    function updatePropertyNetDisk(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"updatePropertyNetDisk_0()",
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

    function updatePropertyNetDisk_FIO(Property,olgValue)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"updatePropertyNetDisk_0()",
                class:"Worker"
             },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function updatePropertyNetDisk_0()
    {
        if (typeof _G_varVal === 'object'){
            _G_varVal = _G_varVal[0];
        }
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"NetDisk",r1:'updatePropertyNetDisk_0',
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
