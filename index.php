
<?php

require_once('helper_funcs.php');
include("phpqrcode/qrlib.php");

$ip = $_SERVER['SERVER_ADDR'];

?>

<html>


<link rel="stylesheet" href="css/main.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" >
	

	$(function () {


		init();

		$('.menu-item').click(function (e) {


			togglePage($(this).attr('pg'));
			

		})

	})

	function init(){
		$('.main-cont > .cont ').hide();
		$('.cont.server').show();
	}

	function togglePage(pg){


			$('.main-cont > .cont ').hide();


			$('.cont.' + pg).show();
	}

	function log(d){
		console.log(d);
	}

</script>


<div class="main">
	<div class="menu-cont">
		<ul class="main-menu">

			<li class="menu-item" pg="server">Server</li>
			<li class="menu-item" pg="stock">Mon stock</li>
			<li class="menu-item" pg="sells">Ventes</li>
			<li class="menu-item" pg="settings">Parametres</li>

		</ul>
	</div>
	<div class="main-cont">

		<div class="cont server" >

			
				<div>
				<?php

					echo 'ip -> <b>' . $ip . '</b>';

					QRcode::png("ip_" . $ip, "server_qr.png", QR_ECLEVEL_L, 10);

				?>
				</div>

				

				<div>
					<img src="server_qr.png" style="display: block;" >
				</div>
			

		</div>

		<div class="cont stock">


			<table>

				<tr>
					<td>Nom</td>
					<td>Prix</td>
					<td>Stock</td>
				</tr>

			<?php

				$data = file_get_contents('http://' . $ip . '/shopm/api.php?act=loadAllItems');
				$data = json_decode($data);
				$data = $data->{'data'};



				foreach ($data as $k => $v) {
					
					echo '<tr>';
					$name = $v->{'item_name'};
					$price = $v->{'item_price'};
					$stock = $v->{'item_stock_count'};


					echo '<td>' . $name . '</td>';
					echo '<td>' . $price . '</td>';
					echo '<td>' . $stock . '</td>';

					echo '</tr>';
				}

			?>

			</table>
		</div>

		<div class="cont sells" >
			ventes
		</div>

		<div class="cont settings" >
			settings
		</div>

		

	</div>
</div>


</html>


