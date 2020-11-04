<?php

	session_start();

	// mysql
    //require_once("db_config_mysql.php");
	//$dsn = "mysql:host=$host;user=$username;password=$password";

	// postgresql	
	require_once('db_config_postgresql.php');
	$dsn = "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
	
	if ($_SESSION["newsession"] == '') {
	$_SESSION["newsession"]=time() . password_hash(uniqid('',true), PASSWORD_DEFAULT) . uniqid('',true) . rand() . random_int(-2147483648, 2147483647);
	}

	if ($_SESSION["gebruiker"] == '') {
	$_SESSION["gebruiker"]='DANKUI2';
	}

    $gebruiker = $_SESSION["gebruiker"];

	$connectieid = $_SESSION["newsession"];

	try{

	 // connectie met database
	 $conn = new PDO($dsn);
	 
	 // uitvoeren van sql statement voor keuzelijst geslacht bij aanvrager 1
     $sql = "SELECT code, waarde1 FROM allezaken_data.domeinen WHERE onderdeeldomein LIKE (SELECT CONCAT('%[', code, ']%') FROM allezaken_data.domeinen WHERE waarde1='Geslacht') order by volgorde asc";
	 $geslacht1 = $conn->query($sql);
	 
	 if($geslacht1 === false){
	 die("Error executing the query: $sql");
	 } 

	 // uitvoeren van sql statement voor keuzelijst geslacht bij aanvrager 2
	 $sql = "SELECT code, waarde1 FROM allezaken_data.domeinen WHERE onderdeeldomein LIKE (SELECT CONCAT('%[', code, ']%') FROM allezaken_data.domeinen WHERE waarde1='Geslacht') order by volgorde desc";
	 $geslacht2 = $conn->query($sql);
	 
	 if($geslacht2 === false){
	 die("Error executing the query: $sql");
	 } 

	}catch (PDOException $e){
	 echo $e->getMessage();
	}

//    if(count($_POST)>0) {

//		$sql = "UPDATE allezaken.temp_kinderen set bsn='" . $_POST["bsn3"] . "', achternaam='" . $_POST["achternaam3"] . "', geboortedatum='" . $_POST["geboortedatum3"] . "', geslacht='" . $_POST["geslacht3"] . "'";

//		$sql = $sql . ", voornamen='" . $_POST["voornamen3"] . "', voorletters='" . $_POST["voorletters3"] . "', voorvoegsel='" . $_POST["voorvoegsel3"] . "'"; 

//		$sql = $sql . ", voorzwemmen='" . $_POST["voorzwemmen3"] . "', lesvolwassenen='" . $_POST["lesvolwassenen3"] . "', continurooster='" . $_POST["continurooster3"] . "'";

//		$sql = $sql . ", watervrees='" . $_POST["watervrees3"] . "', voornamendiploma='" . $_POST["voornamendiploma3"] . "', leeftijd='" . $_POST["leeftijd3"] . "'";
		
//		$sql = $sql . ", medischebijzonderheden='" . $_POST["medischebijzonderheden3"] . "', opmerkingen='" . $_POST["opmerkingen3"] . "'";

//		$sql = $sql . " WHERE kindid='" . $_POST["kindid"] . "'";
		
//		echo $sql;
		
        // header('Location: http://' . $_SERVER['HTTP_HOST'] . '/aanvraag_kinderen.php');

 //   }
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Aanmelder</title>


    <!-- Jqquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

	<!-- Jquery UI -->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="bootstrap-multiselect.css" />

    <!-- Popper -->
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>

    <!-- Bootstrap datepicker -->
	<link href='selecteerdatum.css' rel='stylesheet' type='text/css'>
	<script src='selecteerdatum.min.js' type='text/javascript'></script> 
	<script src='selecteerdatum.js' type='text/javascript'></script>  

    <!-- W3schools -->
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <!-- Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Rammetto+One' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="opmaak.css">
    <link rel="stylesheet" href="bootstrap_aanvulling.css">

	<script src='allezaken.js' type='text/javascript'></script>

<script>

		$(function(){
		  $("#headerhtml").load("header_stnd_home.html");
		});

        function opnieuwzoeken(){
		
		    var veld = '';
			var actief = $("#actief").val();
		
		    veld = veld.concat('#zoekadres', actief);

			var x = document.getElementById("btnzoekopnieuw");
			x.style.display = "none";

			var x = document.getElementById("btnadresnietinresultaat");
			x.style.display = "block";
			
			zoekextern(veld);
			
		}
		
		function aanmelden(){}
		
		function adresnietinresultaat(){


			var x = document.getElementById("btnzoekopnieuw");
			x.style.display = "block";

			var x = document.getElementById("btnadresnietinresultaat");
			x.style.display = "none";
			
			document.getElementById("straatnaam1").disabled = false;
			document.getElementById("woonplaats1").disabled = false;
			$('#Adressen').modal('hide');
			
			document.getElementById("straatnaam1").focus();
			
		}

		function sluiten() {
			$('#Adressen').modal('hide');
		}		

		function nieuweaanmelder() {
			
			kopiemaken('aanmelder', 'aanmelder_1', 'btnnieuweaanmelder', 'btnverwijderaanmelder', 'aantalaanmelders', 'maximumaanmelders', 'titel_1', 'Aanmelder');
			
		}
		
		function verwijderaanmelder() {
			
			kopieverwijderen('aanmelder', 'aanmelder_1', 'btnnieuweaanmelder', 'btnverwijderaanmelder', 'aantalaanmelders', 'maximumaanmelders', 'titel_1', 'Aanmelder');
			
		}

		function validate() {
		
   		    var output = true;
			var veldfocus = "";
			$(".registration-error").html('');
			
			if($("#aanvrager-field").css('display') != 'none') {

				if($("#wachtwoord1").val() != $("#herhaalww1").val()) {
					output = false;
					$("#herhaalww1-error").html("Wachtwoorden verschil.");
				}	
				
				if ($("#bsn2").val() == "" && $("#achternaam2").val() == "" && $("#voornamen2").val() == "" && $("#voorletters2").val() == "" 
				 && $("#mobielnummer2").val() == "" && $("#postcode2").val() == "" && $("#huisnummer2").val() == "" 
				){

					resetverplichtveld("#bsn2", "w3-border-red"); 
					resetverplichtveld("#achternaam2", "w3-border-red"); 
					resetverplichtveld("#voornamen2", "w3-border-red"); 
					resetverplichtveld("#voorletters2", "w3-border-red"); 
					resetverplichtveld("#mobielnummer2", "w3-border-red"); 
					resetverplichtveld("#postcode2", "w3-border-red"); 
					resetverplichtveld("#huisnummer2", "w3-border-red"); 
					
				}
				else
				{

					if (!isverplichtgevuld("#huisnummer2", "w3-border-red")) {veldfocus="#huisnummer2";};
					if (!isverplichtgevuld("#postcode2", "w3-border-red")) {veldfocus="#postcode2";};
					if (!isverplichtgevuld("#mobielnummer2", "w3-border-red")) {veldfocus="#mobielnummer2";};
					if (!isverplichtgevuld("#voorletters2", "w3-border-red")) {veldfocus="#voorletters2";};
					if (!isverplichtgevuld("#voornamen2", "w3-border-red")) {veldfocus="#voornamen2";};
					if (!isverplichtgevuld("#achternaam2", "w3-border-red")) {veldfocus="#achternaam2";};
					if (!isverplichtgevuld("#bsn2", "w3-border-red")) {veldfocus="#bsn2";};
					
				}
				
				if (!isverplichtgevuld("#huisnummer1")) {veldfocus="#huisnummer1";};
				if (!isverplichtgevuld("#postcode1")) {veldfocus="#postcode1";};
				if (!isverplichtgevuld("#herhaalww1")) {veldfocus="#herhaalww1";};
				if (!isverplichtgevuld("#wachtwoord1")) {veldfocus="#wachtwoord1";};
				if (!isverplichtgevuld("#mobielnummer1")) {veldfocus="#mobielnummer1";};
				if (!isverplichtgevuld("#email1")) {veldfocus="#email1";};
				if (!isverplichtgevuld("#voorletters1")) {veldfocus="#voorletters1";};
				if (!isverplichtgevuld("#voornamen1")) {veldfocus="#voornamen1";};
				if (!isverplichtgevuld("#achternaam1")) {veldfocus="#achternaam1";};
				if (!isverplichtgevuld("#bsn1")) {veldfocus="#bsn1";};
				
				if (veldfocus != ""){
					
					$(veldfocus).focus();
					output = false;
				};
			}
			
			return output;
		}

        function aanmelden(){
    		$('#Aanmelder').modal({backdrop: "static"},'show');
		}


		$(document).ready(function() {

			$("#zoekAdres1").click(function(){
				
				$("#actief").val("1");
				
				zoekAdres("#zoekadres1");

			});	

			$("#zoekAdres2").click(function(){
				
				$("#actief").val("2");
				
				zoekAdres("#zoekadres2");
				
			});	
			
			$("#volgend").click(function(){
				
				var x = $('#aanvraagform_aanvrager').serializeArray();
				
				var velden = "{";
				
				$.each(x, function(i, field){
					velden = velden.concat ('"', field.name , '":"' , field.value , '",');
				});
				
				var connectieid = "<?php echo $connectieid ?>";
				
				velden = velden.concat ('"connectieid":"', connectieid, '"');
				
				str = $("#straatnaam1").val();
				
				velden = velden.concat (',"straatnaam1":"', str, '"');

				str = $("#woonplaats1").val();
				
				velden = velden.concat (',"woonplaats1":"', str, '"');
				
				str = $("#straatnaam2").val();
				
				velden = velden.concat (',"straatnaam2":"', str, '"');

				str = $("#woonplaats2").val();
				
				velden = velden.concat (',"woonplaats2":"', str, '"');
				
				velden = velden.concat ("}");
				
				$.post('aanvragers.php', { varjson: velden }, function(result) { 
				
					resultaat = result;

				});
				
				var httphost = "<?php echo $_SERVER['HTTP_HOST'] ?>";
				
				var url = 'http://';
				
				var url = url.concat(httphost, '/aanvraag_kinderen.php?connectieid=', connectieid);

                // window.open(url, "_parent");				
				
			});
			
			$("#next").click(function(){
				var output = validate();
				if(output) {
					var current = $(".highlight");
					var next = $(".highlight").next("li");
					if(next.length>0) {
						$("#"+current.attr("id")+"-field").hide();
						$("#"+next.attr("id")+"-field").show();
						$("#back").show();
						$("#finish").hide();
						$(".highlight").removeClass("highlight");
						next.addClass("highlight");
						if($(".highlight").attr("id") == $("li").last().attr("id")) {
							$("#next").hide();
							$("#finish").show();				
						}
					}
				}
			});
			$("#back").click(function(){ 
				var current = $(".highlight");
				var prev = $(".highlight").prev("li");
				if(prev.length>0) {
					$("#"+current.attr("id")+"-field").hide();
					$("#"+prev.attr("id")+"-field").show();
					$("#next").show();
					$("#finish").hide();
					$(".highlight").removeClass("highlight");
					prev.addClass("highlight");
					if($(".highlight").attr("id") == $("li").first().attr("id")) {
						$("#back").hide();			
					}
				}
			});
		});

		// voorkomen dat dropdown menu wordt gesloten wanneer er wordt geklikt in het formulier
		$(document).on("click", ".action-buttons .dropdown-menu", function(e){
			e.stopPropagation();
		});

</script>

<style>

body {
	font-family: 'Varela Round', sans-serif;
}

@media (min-width: 1200px){
	.form-inline .input-group {
		width: 300px;
		margin-left: 30px;
	}
}
@media (max-width: 768px){
	.navbar .dropdown-menu.action-form {
		width: 100%;
		padding: 10px 15px;
		background: transparent;
		border: none;
	}
	.navbar .form-inline {
		display: block;
	}
	.navbar .input-group {
		width: 100%;
	}
}

   @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 620px;
        }
    }
    .inputbtn-veld{height: 40px;} 
	
	.modal-header{
  background-color: #F4F6F6;
}
	.modal-body {
  background-color: #F4F6F6;
}
    .modal-footer {
  background-color: #F4F6F6;
}	

.modal-dialog{
      overflow-y: initial !important;
}
.modal-body{
  overflow-x: hidden;
}

</style>

<div id="headerhtml">

</div>

</head> 
<body>

<form id="aanvraagform_aanvrager" name="aanvraagform_aanvrager">

<div id="Aanmelder" class="modal fade">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">				
				<h5 class="modal-title"><FONT COLOR="#17202A"><b>Aanmelden</b></FONT></h5>
			</div>
			<div class="modal-body">

			<div class='aanmelder'>
			<div id='aanmelder_1'>
			   
			   <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
			   <div class="w3-col" style="width:100%">
				  <h5><b><label type="text" id="titel_1" name="titel_1">Aanmelder</label></b></h5>
				</div> 
			  </div>

			   <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
			   <div class="w3-col" style="width:50%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large maakleeg" placeholder="Voornaam" title="Voornaam" type="text" id="voornamen_1" name="voornamen_1" onchange="valideer(this);"/>
				</div> 
				<div class="w3-col" style="width:50%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large maakleeg" placeholder="Achternaam" title="Achternaam" type="text" id="achternaam_1" name="achternaam_1" onchange="valideer(this);"/>
				</div>
			  </div>

                <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4></h4>			  
				<div class="w3-col" style="width:25%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Voorletters" title="Voorletters" type="text" id="voorletters1" name="voorletters1" onchange="valideer('voorletters1');"/>
				</div>
				<div class="w3-col" style="width:25%">
				  <input class="form-control inputbtn-veld w3-border w3-round-large" placeholder="Voorvoegsel" title="Voorvoegsel" type="text" id="voorvoegsel1" name="voorvoegsel1" onchange="valideer('voorvoegsel1');"/>
				  <span class="error"><?php echo $voorvoegsel1Err;?></span>
				</div>

				<div class="w3-col" style="width:22%">
					<select name="geslacht_1" id="geslacht_1" class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" title="Geslacht" onchange="valideer(this);">
    				<?php while($res = $geslacht1->fetch(PDO::FETCH_ASSOC)) {echo '<option value="' . $res['code'] . '"' . '>' . $res['waarde1'] . '</option>'; } ?>	
					</select>
				</div>

			    <div class="w3-col" style="width:28%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large datepicker" placeholder="Geboortedatum" title="Geboortedatum" type="text" name="geboortedatum1" id="geboortedatum1" data-date-format="dd-mm-yyyy"/>
					<script type='text/javascript' >
					$( function() {

					$('#geboortedatum1').datepicker( $.datepicker.regional[ "nl" ] );

					});
					</script>	
				</div> 

			  </div>
			  
			  <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4></h4>			  
				<div class="d-flex w3-col" style="width:50%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Email" title="Email" type="text" id="email1" name="email1" onchange="valideer('email1');"/>
				</div>

				<div class="d-flex w3-col" style="width:22%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="06-nummer" title="06-nummer" type="text" id="mobielnummer1" name="mobielnummer1" onchange="valideer('mobielnummer1');"/>
				</div>

				<div class="d-flex w3-col" style="width:28%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Inlognaam" title="Inlognaam" type="text" id="inlognaam1" name="inlognaam1" onchange="valideer('inlognaam1');"/>
				</div>
			  </div>

			  <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4></h4>			  
				<div class="d-flex w3-col" style="width:50%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Wachtwoord" title="Wachtwoord" type="password" id="wachtwoord1" name="wachtwoord1"  onchange="valideer('wachtwoord1');"/>
				</div>
				<div class="d-flex w3-col" style="width:50%;">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Herhaal wachtwoord" title="Herhaal uw wachtwoord" type="password" id="herhaalww1" name="herhaalww1"  onchange="valideer('herhaalww1');"/>
				</div>
			  </div>
			  
			  <div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4></h4>			  
				<div class="d-flex w3-col" style="width:25%">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Postcode" title="Postcode" type="text" id="postcode1" name="postcode1" onchange="valideer('postcode1');"/>
				</div>
				<div class="d-flex w3-col" style="width:25%;">
				  <input class="form-control inputbtn-veld w3-border-red w3-border w3-round-large" placeholder="Huisnummer" title="Huisnummer" type="text" id="huisnummer1" name="huisnummer1" onchange="valideer('huisnummer1');"/>
				</div>
				<div class="d-flex w3-col" style="width:22%">
				  <input class="form-control inputbtn-veld w3-border w3-round-large" placeholder="Huisletter" title="Huisletter" type="text" id="huisletter1" name="huisletter1" onchange="valideer('huisletter1');"/>
				</div>
				<div class="d-flex w3-col" style="width:28%">
				  <input class="form-control inputbtn-veld w3-border w3-round-large" placeholder="Toevoeging" title="Toevoeging" type="text" id="toevoeginghuisnummer1" name="toevoeginghuisnummer1" onchange="valideer('toevoeginghuisnummer1');"/>
				</div>
              </div>

				<div class="w3-row-padding" style="margin:0 -16px 8px -16px">
				<h4></h4>
					<div class="d-flex w3-col" style="width:50%">
						<input class="form-control inputbtn-veld w3-border w3-border-red w3-round-large" placeholder="Straatnaam" title="Straatnaam" type="text" id="straatnaam1" name="straatnaam1" onchange="valideer('straatnaam1');" disabled />
					</div>

					<div class="d-flex w3-col" style="width:50%">
						<input class="form-control inputbtn-veld w3-border w3-border-red mr-1 w3-round-large" placeholder="Woonplaats" title="Woonplaats" type="text" id="woonplaats1" name="woonplaats1" onchange="valideer('woonplaats1');" disabled />
						<button class="btn btn-secondary w3-round-large" id="zoekadres_1" type="button" name="zoekadres_1" title="Zoek straatnaam en woonplaats" onclick="btnzoekadres(this)">Zoek</button>
					</div>
				</div>


			  <div class="w3-row-padding" style="margin:0 -16px 8px -16px">			  
				<h4></h4>			  
				<div class="w3-col" style="width:100%">
				  <textarea class="form-control inputbtn-veld w3-border w3-round-large" title="Opmerkingen" placeholder="Opmerkingen" rows="2" cols="40" id = "opmerking1" name="opmerking1" onchange="valideer('opmerking1');"></textarea>
				</div>
			  </div>

			  <!-- Verborgen velden -->
			  <div hidden class="w3-row-padding" style="margin:0 -16px 8px -16px">
			    <div class="w3-col" style="width:100%">
				    <label class="labelaanvraag">Nummeraanduidingid</label>
                    <input class="w3-input w3-border" type="text" id="nummeraanduidingid_1" name="nummeraanduidingid_1"></input>
				</div>
			  </div>
			  
			</div>
			</div>

			  <!-- Verborgen velden -->


 <input hidden type='text' id='aantalaanmelders' value="1"/>
 <input hidden type='text' id='maximumaanmelders' value="2"/>

			  <div hidden class="w3-row-padding" style="margin:0 -16px 8px -16px">
			    <div class="w3-col" style="width:100%">
				    <label class="labelaanvraag">Gebruiker</label>
                    <input class="w3-input w3-border" type="text" id="gebruiker" name="gebruiker" value="<?php echo $_SESSION["gebruiker"]?>"></input>
				</div>
			  </div>
			  <div hidden class="w3-row-padding" style="margin:0 -16px 8px -16px">
			    <div class="w3-col" style="width:100%">
				    <label class="labelaanvraag">Adres aanvrager 1</label>
                    <input class="w3-input w3-border" type="text" id="adresaanvrager1" name="adresaanvrager1"></input>
				</div>
			  </div>
			  <div hidden class="w3-row-padding" style="margin:0 -16px 8px -16px">
			    <div class="w3-col" style="width:100%">
				    <label class="labelaanvraag">Adres aanvrager 2</label>
                    <input class="w3-input w3-border" type="text" id="adresaanvrager2" name="adresaanvrager2"></input>
				</div>
			  </div>

			  <div hidden class="w3-row-padding" style="margin:0 -16px 8px -16px">
			    <div class="w3-col" style="width:100%">
				    <label class="labelaanvraag">Actief</label>
                    <input class="w3-input w3-border" type="text" id="actief" name="actief"></input>
				</div>
			  </div>
			
			</div>
			
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm inputbtn-veld" title="Aanmelden">Aanmelden</button>
					<button type="button" class="btn btn-secondary btn-sm inputbtn-veld" id='btnnieuweaanmelder' title="Nieuwe aanmelder" onclick="nieuweaanmelder()">Nieuwe aanmelder</button>
					<button style="display: none" type="button" class="btn btn-secondary btn-sm inputbtn-veld" id='btnverwijderaanmelder' title="Verwijder aanmelder" onclick="verwijderaanmelder()">Verwijder aanmelder</button>
                    <button type="button" class="btn btn-secondary btn-sm inputbtn-veld" title="Annuleren van aanmelding" data-dismiss="modal">Annuleer</button>
            </div>
		</div>
	</div>
</div>
<div class="modal fade aria-hidden="true" id="Adressen">
    <div class="modal-dialog modal-dialog-scrollable"">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Resultaat</h4>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">

			<div id="lijst_adressen"></div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer justify-content-between">
		  <button id="btnsluiten" name="btnsluiten" type="button" class="btn btn-secondary" onclick="sluiten()">Sluiten</button>
          <button id="btnzoekopnieuw" name="btnzoekopnieuw" type="button" class="btn btn-secondary" onclick="opnieuwzoeken()">Zoek opnieuw</button>		  
		  <button id="btnadresnietinresultaat" name="btnadresnietinresultaat" style="display: none" type="button" class="btn btn-secondary" onclick="adresnietinresultaat()">Adres niet in resultaat</button>		  
		  <button id="btnselectieadres" name="btnselectieadres" type="button" class="btn btn-primary" onclick="selectie_adres()">Selecteer adres</button>
        </div>
      </div>
</div>  

</form>
</body>
</html>