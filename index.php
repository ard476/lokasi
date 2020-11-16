<?php
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporkan Posisimu</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Laporkan Posisimu</a>
            </div>
        </div>
    </nav>
    <div class="container" style="margin-top: 50px">
		<?php
        //definisikan variabel untuk tiap-tiap inputan
		$foto = pg_escape_string($kon,$_POST['foto']);
        $lat = pg_escape_string($kon,$_POST['lat']);
        $lokasi = pg_escape_string($kon,"https://www.google.com/maps/search/".$_POST['lat']."+".$_POST['long']);
		$long = pg_escape_string($kon,$_POST['long']);
        $ket = pg_escape_string($kon,$_POST['ket']);
		
        
		//jika di klik tombol kirim pesan menjalankan script di bawah ini
		if($_POST['submit']){	
			$input = pg_query($kon,"INSERT INTO lokasimu(foto,lokasi,lat,long,ket) 
					VALUES('$foto','$lokasi',$lat,$long,'$ket')");
			if($input){
				echo '<div class="alert alert-success">Berhasil menyimpan data!</div>';
			}else{
				echo '<div class="alert alert-warning">Gagal menyimpan data!</div>';
			}
		}
        ?>		
        <form id="formPost" name="formPost" class="form-horizontal" method="post">
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-7">
                    <div id="cam"></div>
                    <input id="foto" type="hidden" name="foto">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Keterangan</label>
                <div class="col-sm-4">
                    <input type="text" name="ket" class="form-control" required maxlength="255">
                </div>
            </div>
			<div class="form-group">
                <label class="col-sm-3 control-label">Posisi saat ini</label>
                <div class="col-sm-4">
				    <input type="hidden" name="lat" id="lat" value="0" >
					<input type="hidden" name="long" id="lon" value="0" > 
					<p id="posisi"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-4">
                    <input type="submit" name="submit" class="btn btn-primary" value="Kirim">
                </div>
            </div>
        </form>
    </div>     
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/webcam.min.js"></script>
    <script >
	(function($) {
	  $.fn.inputFilter = function(inputFilter) {
		return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
		  if (inputFilter(this.value)) {
			this.oldValue = this.value;
			this.oldSelectionStart = this.selectionStart;
			this.oldSelectionEnd = this.selectionEnd;
		  } else if (this.hasOwnProperty("oldValue")) {
			this.value = this.oldValue;
			this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
		  }
		});
	  };
	}(jQuery));	
	
	$(".number").inputFilter(function(value) {
		return /^\d*$/.test(value);
	});

	Webcam.set({
		width: 320,
		height: 240,
		image_format: 'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach('#cam');
	$("#formPost").submit(function( event ) {
		Webcam.snap( function(data_uri) {
			var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
			$('#foto').val(raw_image_data);
		});
	});
    </script>
	<script>
	var x = document.getElementById("posisi");
	var flat = document.getElementById("lat");
	var flon = document.getElementById("lon");

	function getLocation() {
	  if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	  } else { 
		x.innerHTML = "Geolocation is not supported by this browser.";
	  }
	}

	function showPosition(position) {
	  // kantor 
	  xlat = -7.8129605;
	  xlong = 110.3770045;
	  
	  flat.value = position.coords.latitude;
	  flon.value = position.coords.longitude;
	  
	  x.innerHTML = "lat: " + position.coords.latitude + 
	  "<br>long: " + position.coords.longitude+
	  "<br><a target='_blank' href='https://www.google.com/maps/search/"+position.coords.latitude+"+"+position.coords.longitude+"'>Cek di map</a>"+
	  "<br><br>Anda "+distance(xlat,xlong,position.coords.latitude,position.coords.longitude)+" meter dari Kantor";
	}

	function distance(lat1,lon1,lat2,lon2) {
		var R = 6371; // km (change this constant to get miles)
		var dLat = (lat2-lat1) * Math.PI / 180;
		var dLon = (lon2-lon1) * Math.PI / 180;
		var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
			Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
			Math.sin(dLon/2) * Math.sin(dLon/2);
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
		var d = R * c;
		return Math.round(d*1000);
		//if (d>1) return Math.round(d)+" km";
		//else if (d<=1) return Math.round(d*1000)+" m";
		return d;
	}

	getLocation();
	</script>
</body>
</html>