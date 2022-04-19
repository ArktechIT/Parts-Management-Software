<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);    
include('PHP Modules/mysqliConnection.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/gerald_functions.php');
include("PHP Modules/rose_prodfunctions.php");
ini_set("display_errors", "on");
$ctrl = new PMSDatabase;
$tpl = new PMSTemplates;
$pms = new PMSDBController;
$rdr = new Render\PMSTemplates;

$title = "PARTS COMMENT LIST";
PMSTemplates::includeHeader($title);

$tpl->setDataValue("L436");
$tpl->setAttribute("type","button");
$tpl->setAttribute("onclick","location.href=''");
$tpl->addClass("w3-right");
$refreshBtn = $tpl->createButton();

    
$tpl->setDataValue("L437");
$tpl->setAttribute([
     "name"  => "btnFilter",
     "id"    => "btnFilter",
     "type"  => "submit",
     "onclick"  => "modalFilterForm()"
]);
$tpl->addClass("w3-right");
$btnFilter = $tpl->createButton();

$tpl->setDataValue("L482");
$tpl->setAttribute([
     "name"  => "btnAdd",
     "id"    => "btnAdd",
     "type"  => "submit",
     "onclick"  => "modalAddForm()"
]);
$tpl->addClass("w3-right");
$btnAdd = $tpl->createButton();

$tpl->setDataValue("L243");
$tpl->setAttribute([
     "name"  => "btnEdit",
     "id"    => "btnEdit",
     "type"  => "submit",
     "onclick"  => "modalEditForm()"
]);
$tpl->addClass("w3-right");
$btnEdit = $tpl->createButton();

$tpl->setDisplayId("") # OPTIONAL
    ->setVersion("PARTS COMMENT LIST") # OPTIONAL
    ->setPrevLink($_SERVER['HTTP_REFERER']) # OPTIONAL
    ->setHomeIcon() # OPTIONAL 0 - Default; 1 - w/o home icon
    ->createHeader();

    
   $partComment = isset($_POST['partComments'])? $_POST['partComments']:'';

    $sqlFilterArray = array();
    
    if($partComment!='')	$sqlFilterArray[] = "partsComment LIKE '%".$partComment."%'";
    
    
    
    $orderBy = "ORDER BY commentId DESC";
    $sqlFilter = "WHERE commentId > 0";
    if(count($sqlFilterArray) > 0)
    {
        $sqlFilter .= " AND ".implode(" AND ",$sqlFilterArray );
    }
        
    
    $sql = "SELECT * FROM cadcam_partsComment ".$sqlFilter."";
    $queryComment = $db->query($sql);
    $resultComment = $queryComment->fetch_assoc();
    $sqlData=$sql;
    $totalRecords=$queryComment->num_rows;
?>
<div class='container-fluid'>
  
    <div class='row w3-padding-top w3-right'> <!-- row 1 -->
        
        <div class="col-md-4">
            <?php echo $btnAdd;?>
        </div>
        <!-- <div class="col-md-4">
            <?php echo $btnEdit;?>
        </div> -->
        <div class='col-md-4'>
            <!-- Code Here.. -->
           
            <?php echo $btnFilter;?>
            
        </div>
        <div class='col-md-4'>
            <?php echo $refreshBtn;?>
        </div>
    </div>
    <div class='row w3-padding-top'>  <!-- row 2 -->
        <div class='col-md-12'>
            <!-- TABLE TEMPLATE -->
            <label><?php echo displayText("L41", "utf8", 0, 0, 1)." : ". $totalRecords; ?></label>
            <table id='listTableAjax' class="table table-bordered table-striped table-condensed" style="width:100%;">
				<thead class='w3-indigo' style='text-transform:uppercase;'>
                    <th class='w3-center' style='vertical-align:middle;'>#</th>
                    <th class='w3-center' style='vertical-align:middle;'>PARTS COMMENT</th>
                    <th class='w3-center' style='vertical-align:middle;width:50px;'>ACTION</th>
                 
				</thead>
				<tbody class='w3-center'>
					
				</tbody>
				<tfoot class='w3-indigo' >
                    <tr>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                        <th class='w3-center' style='vertical-align:middle;'></th>
                        <th class='w3-center' style='vertical-align:middle;width:50px;'></th>
                    </tr>
				</tfoot>
			</table>
                    </div>
    </div>
</div>
<div id='modal-izi-add'><span class='izimodal-content-add'></span></div>  
<div id='modal-izi-edit'><span class='izimodal-content-edit'></span></div>  
<div id='modal-izi-delete'><span class='izimodal-content-delete'></span></div> 
<div id='modal-izi-filter'><span class='izimodal-content-filter'></span></div>  
<?php
PMSTemplates::includeFooter();
?>
<script>
        // AJAX TABLE START HERE

        $(document).ready(function(){ 
            var sqlData = "<?php echo $sqlData; ?>";
            console.log(sqlData);
            var totalRecords = "<?php echo $totalRecords; ?>";
            var dataTable = $('#listTableAjax').DataTable({
                "searching"    : false,   
                "processing"    : true,
                "ordering"      : false,
                "serverSide"    : true,
                "bInfo" 		: false,
                "ajax":{
                    url     :"jhon_partsCommentListAJAX.php", // json datasource
                    type    : "post",  // method  , by default get
                    data    : {
                                "totalRecords"   	: totalRecords,
                                "sqlData"     	    : sqlData
                                },
                    error: function(data){  // error handling
                        console.log(data);
                        
                        // $("#idNumber").append('<tbody class="listTableAjax-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        // $("#idNumber").css("display","none");
                        
                    }
                },
                
                language	: {
                            processing	: "<span class='loader'></span>"
                },
                fixedColumns:   {
                        leftColumns: 0
                },
                // responsive		: true,
                scrollY     	: 530,
                scrollX     	: true,
                scrollCollapse	: false,
                scroller    	: {
                    loadingIndicator    : true
                },
                stateSave   	: false
            });

            $("#btnFilter").click(function(){
                //alert('asd');
            });

        });
    
        // AJAX TABLE END HERE
        //MODAL ADD FORM START HERE------------------------------------------------------------------
        function modalAddForm()
        {
                $("#modal-izi-add").iziModal({
                    title                   : '<i class="fa fa-plus"></i> <?php echo displayText("L482","utf8",0,0,1);?>',
                    headerColor             : '#1F4788',
                    subtitle                : '<b><?php //echo strtoupper(date('F d, Y'));?></b>',
                    width                   : 400,
                    fullscreen              : false,
                    transitionIn            : 'comingIn',
                    transitionOut           : 'comingOut',
                    padding                 : 20,
                    radius                  : 0,
                    top                     : 100,
                    restoreDefaultContent   : true,
                    closeOnEscape           : true,
                    closeButton             : true,
                    overlayClose            : false,
                    onOpening               : function(modal){
                                                modal.startLoading();
                                                // alert(assignedTo);
                                                $.ajax({
                                                    url         : 'jhon_partCommentAdd.php',
                                                    type        : 'POST',
                                                    data        : {
                                                      
                                                        partComments           :'<?php echo $partComment;?>',
                                                        

                                                                
                                                    },
                                                    success     : function(data){
                                                                    $( ".izimodal-content-add" ).html(data);
                                                                    modal.stopLoading();
                                                    }
                                                });
                                            },
                    onClosed                : function(modal){
                                                $("#modal-izi-add").iziModal("destroy");
                                } 
                });

                $("#modal-izi-add").iziModal("open");
        }
        //MODAL ADD FORM END HERE------------------------------------------------------------------
        //MODAL EDIT FORM START HERE------------------------------------------------------------------
        function modalEditForm(commentId)
        {
                $("#modal-izi-edit").iziModal({
                    title                   : '<i class="fa fa-edit"></i> <?php echo displayText("L243","utf8",0,0,1);?>',
                    headerColor             : '#1F4788',
                    subtitle                : '<b><?php //echo strtoupper(date('F d, Y'));?></b>',
                    width                   : 400,
                    fullscreen              : false,
                    transitionIn            : 'comingIn',
                    transitionOut           : 'comingOut',
                    padding                 : 20,
                    radius                  : 0,
                    top                     : 100,
                    restoreDefaultContent   : true,
                    closeOnEscape           : true,
                    closeButton             : true,
                    overlayClose            : false,
                    onOpening               : function(modal){
                                                modal.startLoading();
                                                // alert(assignedTo);
                                                $.ajax({
                                                    url         : 'jhon_partCommentEdit.php',
                                                    type        : 'POST',
                                                    data        : {
                                                      
                                                       commentId : commentId

                                                                
                                                    },
                                                    success     : function(data){
                                                                    $( ".izimodal-content-edit" ).html(data);
                                                                    modal.stopLoading();
                                                    }
                                                });
                                            },
                    onClosed                : function(modal){
                                                $("#modal-izi-edit").iziModal("destroy");
                                } 
                });

                $("#modal-izi-edit").iziModal("open");
        }
        //MODAL EDIT FORM END HERE------------------------------------------------------------------
        //MODAL FILTER FORM START HERE------------------------------------------------------------------
        function modalFilterForm()
        {
                //alert();
                $("#modal-izi-filter").iziModal({
                    title                   : '<i class="fa fa-filter"></i> <?php echo displayText("L437","utf8",0,0,1);?>',
                    headerColor             : '#1F4788',
                    subtitle                : '<b><?php //echo strtoupper(date('F d, Y'));?></b>',
                    width                   : 400,
                    fullscreen              : false,
                    transitionIn            : 'comingIn',
                    transitionOut           : 'comingOut',
                    padding                 : 20,
                    radius                  : 0,
                    top                     : 100,
                    restoreDefaultContent   : true,
                    closeOnEscape           : true,
                    closeButton             : true,
                    overlayClose            : false,
                    onOpening               : function(modal){
                                                modal.startLoading();
                                                // alert(assignedTo);
                                                $.ajax({
                                                    url         : 'jhon_partCommentFilter.php',
                                                    type        : 'POST',
                                                    data        : {
                                                      
                                                       partComments : '<?php echo $partComment; ?>'

                                                                
                                                    },
                                                    success     : function(data){
                                                                    $( ".izimodal-content-filter" ).html(data);
                                                                    modal.stopLoading();
                                                    }
                                                });
                                            },
                    onClosed                : function(modal){
                                                $("#modal-izi-filter").iziModal("destroy");
                                } 
                });

                $("#modal-izi-filter").iziModal("open");
        }
        //MODAL FILTER FORM END HERE------------------------------------------------------------------
        //MODAL DELETE FORM START HERE------------------------------------------------------------------
        function modalDeleteForm(commentId)
        {
                //alert();
                $("#modal-izi-delete").iziModal({
                    title                   : '<i class="fa fa-trash"></i> <?php echo displayText("L609","utf8",0,0,1);?>',
                    headerColor             : '#1F4788',
                    subtitle                : '<b><?php //echo strtoupper(date('F d, Y'));?></b>',
                    width                   : 400,
                    fullscreen              : false,
                    transitionIn            : 'comingIn',
                    transitionOut           : 'comingOut',
                    padding                 : 20,
                    radius                  : 0,
                    top                     : 100,
                    restoreDefaultContent   : true,
                    closeOnEscape           : true,
                    closeButton             : true,
                    overlayClose            : false,
                    onOpening               : function(modal){
                                                modal.startLoading();
                                                // alert(assignedTo);
                                                $.ajax({
                                                    url         : 'jhon_partCommentDelete.php',
                                                    type        : 'POST',
                                                    data        : {
                                                      
                                                        commentId :         commentId

                                                                
                                                    },
                                                    success     : function(data){
                                                                    $( ".izimodal-content-delete" ).html(data);
                                                                    modal.stopLoading();
                                                    }
                                                });
                                            },
                    onClosed                : function(modal){
                                                $("#modal-izi-delete").iziModal("destroy");
                                } 
                });

                $("#modal-izi-delete").iziModal("open");
        }
        //MODAL DELETE FORM END HERE------------------------------------------------------------------


</script>
