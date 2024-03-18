<style>
    .buttonCopyClipboard {
        /*.background-Menu*/
        /*.textColorBlack*/
        /*.BorderAll*/
        background-image: url(/frontPage/img/copyClipboard.png);
        background-size: contain;
        background-repeat: no-repeat;

        text-align:center;
        cursor:pointer;
    }
    .buttonCopyClipboard:hover{
        /*.background-Insert-Menu*/
        /*.textColorWhite*/
        background-image: url(/frontPage/img/copyClipboard.png);
        background-size: contain;
        background-repeat: no-repeat;
    }
</style>
<script>
    function loadscript()
    {
        $("#head__<?php print $this->objectFullName;?>").mousedown(function(event) {
            _G_moveIdBlock = "<?php print $this->objectFullName;?>";
            _G_mouseMemX = event.pageX;
            _G_mouseMemY = event.pageY;
            var topLeft = $("#WinMain__<?php print $this->objectFullName;?>").offset();
            _G_windowMemX = topLeft['left'];
            _G_windowMemY = topLeft['top'];
        });
    }

    function CopyToClipboard(containerid)
    {
        const str = document.getElementById(containerid).innerText;
        const el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    }

    function deleteRequest()
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        if (insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed)){
            _G_idRequest = insertedRow[0];
            _G_BlockAppQuestion('Подтвердите удаление','deleteRequestStart();');
        }else{
            _G_BlockAppMessage('Вы не выбрали заявку для входа');
        }
    }

    function deleteRequestStart()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'deleteRequest',
                id_Request:_G_idRequest},
            dataType: 'text',
            success: function(){

                $('#upload_button').on('change', function(){
                    _G_filesInserted=this.files;
                    this.files = null;
                    uploadFile_1(event);
                });

                <?php
                print "refresh_$this->objectFullName()";
                ?> ;

                closeBlockAPP();
            }
        });
    }

    function createNewRequest()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'createNewRequest'},
            dataType: 'text',
            success: function(data){
                _G_idRequest = data;
                editRequestStart();
                //integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data)
            }
        });
    }


    function  editRequest()
    {
        var name_greed = '<?php print $this->nameGreed; ?>';
        if (insertedRow = _G_foundCheckedRowInGrid_returnObject(name_greed)){
            _G_idRequest = insertedRow[0];
            editRequestStart();
        }else{
            _G_BlockAppMessage('Вы не выбрали заявку для входа');
        }
    }


    function editRequestStart()
    {
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'editRequest',
                id_Request:_G_idRequest},
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data);
                editEntryRequest();

                $('#upload_button').on('change', function(){
                    _G_filesInserted=this.files;
                    this.files = null;
                    uploadFile_1(event);
                });
            }
        });
    }


    function editEntryRequest()
    {
        $("#allManageElement_Request").slideUp(200);
        $("#Request_edit_div").slideDown(200);
    }


    function editExitRequest()
    {
        <?php
        print "refresh_$this->objectFullName()";
        ?> ;

        $("#allManageElement_Request").slideDown(200);
        $("#Request_edit_div").slideUp(200);
        setTimeout(document.getElementById("Request_edit_div").innerHTML = '', 200);
    }


    function internetClick()
    {
        _G_varName = 'internet_f';
        _G_varVal = $('#internet_f').prop('checked')?1:0;
        updatePropertyRequest_0()
    }

    function netDiskClick()
    {
        _G_varName = 'netDisk_f';
        _G_varVal = $('#netDisk_f').prop('checked')?1:0;
        updatePropertyRequest_0()
    }
    function updatePropertyRequest(Property,olgValue,pattern)
    {
        _G_varName = Property;
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction:"updatePropertyRequest_0()",
                message:"Введите",
                oldValue:olgValue,
                value:olgValue,
                pattern:pattern,
                placeholder:"reverse: true,"
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function updateClosed(catalog,Property,olgValue)
    {
        BlockAPP();
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'updateClosed'},
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }


    function updatePropertyRequest_Catalog(catalog,Property,olgValue)
    {
        _G_varName = Property;

        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"updatePropertyRequest_0()",
                class:catalog
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }


    function updatePropertyRequest_0()
    {
        //alert(_G_varVal)

        if (typeof _G_varVal === 'object'){
            _G_varVal = _G_varVal[0];
        }
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'updatePropertyRequest_0',
                property:_G_varName,
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data);
                closeBlockAPP();
            }
        });
    }


    function addRequestInToTable()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"addRequestInToTable_0()",
                AllInsertOff:1,
                class:"Resource"
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function addRequestInToTable_0()
    {
        _G_varVal = JSON.stringify(_G_varVal);

        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'addRequestInToTable_0',
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("id_editTableRequest",data);
                closeBlockAPP();
            }
        });
    }

    function addNetDiskInToTable()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"addNetDiskInToTable_0()",
                AllInsertOff:1,
                class:"NetDisk"
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function addNetDiskInToTable_0()
    {
        _G_varVal = JSON.stringify(_G_varVal);

        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'addNetDiskInToTable_0',
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("id_editTableNetDisk",data);
                closeBlockAPP();
            }
        });
    }


    function deleteResourceInToTable()
    {
        _G_varVal = _G_foundCheckedRowInGrid_returnObject('Request_greedResource');
        _G_varVal = JSON.stringify(_G_varVal);
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'deleteResourceInToTable',
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("id_editTableRequest",data);
                closeBlockAPP();
            }
        });
    }

    function closeWindowRequest()
    {
        document.getElementById("<?php print "WinMain__".$this->objectFullName;?>").remove();
    }

    function deleteNetDiskInToTable()
    {
        _G_varVal = _G_foundCheckedRowInGrid_returnObject('Request_greedNetDisk');
        _G_varVal = JSON.stringify(_G_varVal);
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'deleteNetDiskInToTable',
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("id_editTableNetDisk",data);
                closeBlockAPP();
            }
        });
    }


    function printRequest()
    {
        BlockAPP();
        BlockAPPWait();
        var path_download="/download/"+_G_idRequest+".xlsx";
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'printRequest',
                id_Request:_G_idRequest
            },
            dataType: 'text',
            success: function(data){
                newFileName = "Заявка.xlsx";
                var link = document.createElement('a');
                link.setAttribute('href', path_download);
                link.setAttribute('download', newFileName);
                link.setAttribute('id', 'clickDownload');
                document.body.appendChild(link);
                document.getElementById("clickDownload").click();
                //link.onclick();
                link.remove();
                closeBlockAPP();
            }
        });
    }


    function replaceStatus()
    {
        BlockAPP();
        $.ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "executeCatalog",
                callFunction:"replaceStatus_0()",
                class:'Status'
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }


    function replaceStatus_0()
    {
        if (typeof _G_varVal === 'object'){
            _G_varVal = _G_varVal[0];
        }
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'replaceStatus_0',
                property:'status',
                value:_G_varVal
            },
            dataType: 'text',
            success: function(data){
                console.log(_G_varVal)
                integrationsScriptCSS("<?php print $this->nameEditDIV;?>",data);
                <?php print "refresh_$this->objectFullName();"; ?>
                closeBlockAPP();
            }
        });
    }


    function uploadFile_0()
    {
        $('#upload_button').click();
    }



    function uploadFile_1(event)
    {

        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        var data = new FormData();

        $.each( _G_filesInserted, function( key, value ){
            data.append( key, value );
        });
        data.append( "idRequest",_G_idRequest);
        data.append( "r0", 'Request' );
        data.append( "r1", 'upload' );
        BlockAPP();
        BlockAPPWait();
        $.ajax({
            type: "POST",
            url: "index_ajax.php",
            data: data,
            cache       : false,
            dataType    : 'text',
            // отключаем обработку передаваемых данных, пусть передаются как есть
            processData : false,
            // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
            contentType : false,
            success: function () {
                closeBlockAPP();
                viewScan();
            }
        });
    }


    function viewScan()
    {
        BlockAPP();
        $.ajax({
            type:"GET",
            url:"index_ajax.php",
            data:{r0:"Request",r1:'viewScan',
                idRequest:_G_idRequest
            },
            dataType: 'text',
            success: function(data){
                integrationsScriptCSS("BlockAPP",data);
            }
        });
    }

</script>
