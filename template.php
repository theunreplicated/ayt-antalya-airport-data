<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />

<script src="http://code.jquery.com/jquery.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
var showallflights_enabled=false;
var xhr=function (url,did,callbfunc){
	$.get(url,function (data){
		document.getElementById(did).innerHTML=data;//unnötig,jquery wäre besser ,ich weiß $("#blub")[0].innerHTML oder .html()
		callbfunc();
		}).fail(function (){
			alert("Netzwerk-Fehler");
			});;
	};
var search=function (val){
	if(!val==''){
xhr('index.php?mode=search&searchstr='+val,'blub',function (){});
	}
	
	}
var showallflights=function (){
	if(!showallflights_enabled){
	xhr('index.php?mode=getall','blub',function (){showallflights_enabled=true;});
	}else{
		document.getElementById('blub').innerHTML='';
		showallflights_enabled=false;
		};
	};
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Unbenanntes Dokument</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
	<div class="row">
    <div class="page-header">
  <h3>Flughafeninfo Antalya <small>Regs,Flugzeug-Typen,Airlines,usw.   hört sich also für jedermann ziemlich interessant an :-D</small></h3>
</div>
		<div class="span10">
            <table class="table table-hover">
	<thead><tr><th><h1>Flughafen Saarbrücken</h1></th></tr></thead><tbody>
    <?php
	print $scn_tables;
	?>
    </tbody>
</table></div>
		<div class="span8"> 
        <table class="table table-hover">
	<thead><tr><th><h5>Flughafen Zweibrücken</h5></th></tr></thead><tbody>
    <?php
	print $zqw_tables;
	?>
    </tbody>
</table>
	</div>
    		<div class="span8"> 
        <table class="table table-hover">
	<thead bgcolor="#009999" role="button" class="btn btn-primary span4 offset2" onclick="showallflights()"><tr><th><h5>Alle Flüge anzeigen</h5></th></tr></thead><tbody id="blub">
 
    </tbody>
</table>
	</div>

	
</div>
</div>


</body>
</html>