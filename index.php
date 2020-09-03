<?php

	include 'inc/config.inc';

	session_start();

	$user_id = $_SESSION['user'];

	$sql_id = "SELECT name, vgc_elo
				FROM users
				WHERE id = $user_id";
	$result_id = mysqli_query($conn, $sql_id);
	$row_id = mysqli_fetch_array($result_id);
	$name = $row_id['name'];
	$vgc_elo = $row_id['vgc_elo'];

	$sql_ladder = "SELECT u.id,
						u.name,
						u.vgc_elo,
						(SELECT COUNT(l.result) FROM elo_log l WHERE l.result = 'W' AND l.id_user = u.id) AS wins,
						(SELECT COUNT(l.result) FROM elo_log l WHERE l.result = 'L' AND l.id_user = u.id) AS losses
					FROM users u
					ORDER BY u.vgc_elo DESC";
	$result_ladder = mysqli_query($conn, $sql_ladder);

	$ladder_pos = 1;


	$sql_find = "SELECT id, name, vgc_elo
				FROM users
				WHERE id != $user_id";
	$result_find = mysqli_query($conn, $sql_find);


	$sql_phrase = "SELECT text
					FROM bad_takes
					ORDER BY rand()
					LIMIT 1";
	$result_phrase = mysqli_query($conn, $sql_phrase);
	$row_phrase = mysqli_fetch_array($result_phrase);
	$phrase = $row_phrase['text'];

?>


<!DOCTYPE HTML>
<!--
	Dimension by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Escadinha dos Refugiados</title>
		<meta charset="ISO-8859-1"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="logo">
							<img src="images/pokeball.png" width="90%" style="margin-top:0.3em;"></img>
						</div>
						<div class="content">
							<div class="inner">
								<h1>Escadinha dos Refugiados</h1>
						<?php
							if(isset($_SESSION['user'])){
						?>
								<h3>Bem-vindo, <?php echo $name;?></h3>
						<?php
							}
						?>
								
								<p><?php echo $phrase;?></p>
							</div>
						</div>
						<nav>
							<ul>
						<?php
							if(isset($_SESSION['user'])){
						?>
								<li><a href="#table">Escada</a></li>
								<li><a href="#partida">Jogo</a></li>
								<li><a href="logout.php">Logout</a></li>
								<!--<li><a href="#elements">Elements</a></li>-->
						<?php
							}else{
						?>
								<li><a href="#login">Login</a></li>
								<li><a href="#create">Registar</a></li>
						<?php
							}
						?>
							</ul>
						</nav>
					</header>

				<!-- Main -->
					<div id="main">

					<!-- Login -->
					<article id="login">
					<h3 class="major">Login</h3>
						<form method="post" action="checklogin.php">
							<div class="fields">
								<div class="field half">
									<label for="name">Nome</label>
									<input type="text" name="name" id="name" value=""/>
								</div>
								<div class="field half">
									<label for="password">Password</label>
									<input type="password" name="password" id="password" value="" />
								</div>
							</div>
							<ul class="actions">
								<li><input type="submit" value="Login" class="primary" /></li>
							</ul>
						</form>
					</article>

					<!-- Registar -->
					<article id="create">
						<h3 class="major">Registar</h3>
						<form method="post" action="save.php?i=1">
							<div class="fields">
								<div class="field half">
									<label for="name">Nome</label>
									<input type="text" name="name" id="name"/>
								</div>
								<div class="field half">
									<label for="password">Password</label>
									<input type="password" name="password" id="password"/>
								</div>
							</div>
							<ul class="actions">
								<li><input type="submit" value="Submeter" class="primary" /></li>
							</ul>
						</form>
					</article>

					
						<!-- Work -->
							<article id="table">
								<h2 class="major" style="text-align:center;">Escadinha</h2>
								<!--<span class="image main"><img src="images/pic02.jpg" alt="" /></span>-->
								
								<section>
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>Posição</th>
													<th>Nome</th>
													<th>ELO</th>
													<th>Vitórias</th>
													<th>Derrotas</th>
												</tr>
											</thead>
											<tbody>
							<?php
								if(mysqli_num_rows($result_ladder) > 0){
									while($row_l = mysqli_fetch_array($result_ladder)){
							?>
												<tr>
													<td><?php echo $ladder_pos;?></td>
													<td><?php echo $row_l['name'];?></td>
													<td><?php echo $row_l['vgc_elo'];?></td>
													<td><?php echo $row_l['wins'];?></td>
													<td><?php echo $row_l['losses'];?></td>
												</tr>
							<?php
										$ladder_pos++;
									}
								}
							?>
											</tbody>
										</table>
									</div>
								</section>
							
							</article>

							

						<!-- Submit -->
							<article id="partida">
								<h2 class="major">Jogo</h2>
								<!--<span class="image main"><img src="images/pic03.jpg" alt="" /></span>-->
								<div style="display: flex;">
									<div style="flex: 0 0 45%; text-align:center"><h2><?php echo $name;?></h2><h4>1000</h4></div>
									<div style="flex: 0 0 10%; text-align:center;margin-top:1.55em;"><h3>vs</h3></div>
									<div id="opponent" style="flex: 1; text-align:center"><h2>???</h2><h4>???</h4></div>
								</div>

								<form method="post" action="calc.php">
									<input type="hidden" id="myelo" name="myelo" value="<?php echo $vgc_elo;?>">
									<div class="fields">
										<div class="field">
											<label for="sel_opponent">Seleciona o teu adversário:</label>
											<select name="sel_opponent" id="sel_opponent">
												<option selected value="" disabled>Prima do Caxinas</option>
									<?php
										if(mysqli_num_rows($result_find) > 0){
											while($row_f = mysqli_fetch_array($result_find)){
									?>
												<option value="<?php echo $row_f['id'];?>"><?php echo $row_f['name'];?></option>
									<?php
											}
										}
									?>
											</select>
										</div>
									
										<div class="field half" style="text-align:center;">
											<input type="radio" id="win" name="radio" value="W">
											<label for="win">Vitória</label>
										</div>
										<div class="field half" style="text-align:center;">
											<input type="radio" id="loss" name="radio" value="L">
											<label for="loss">Derrota</label>
										</div>
									</div>
									<ul class="actions" style="margin-left:35%;">
										<li><input type="submit" value="Submeter" class="primary" /></li>
									</ul>
								</form>
							</article>


						<!-- Elements -->
							<article id="elements">
								<h2 class="major">Elements</h2>

								<section>
									<h3 class="major">Text</h3>
									<p>This is <b>bold</b> and this is <strong>strong</strong>. This is <i>italic</i> and this is <em>emphasized</em>.
									This is <sup>superscript</sup> text and this is <sub>subscript</sub> text.
									This is <u>underlined</u> and this is code: <code>for (;;) { ... }</code>. Finally, <a href="#">this is a link</a>.</p>
									<hr />
									<h2>Heading Level 2</h2>
									<h3>Heading Level 3</h3>
									<h4>Heading Level 4</h4>
									<h5>Heading Level 5</h5>
									<h6>Heading Level 6</h6>
									<hr />
									<h4>Blockquote</h4>
									<blockquote>Fringilla nisl. Donec accumsan interdum nisi, quis tincidunt felis sagittis eget tempus euismod. Vestibulum ante ipsum primis in faucibus vestibulum. Blandit adipiscing eu felis iaculis volutpat ac adipiscing accumsan faucibus. Vestibulum ante ipsum primis in faucibus lorem ipsum dolor sit amet nullam adipiscing eu felis.</blockquote>
									<h4>Preformatted</h4>
									<pre><code>i = 0;

while (!deck.isInOrder()) {
    print 'Iteration ' + i;
    deck.shuffle();
    i++;
}

print 'It took ' + i + ' iterations to sort the deck.';</code></pre>
								</section>

								<section>
									<h3 class="major">Lists</h3>

									<h4>Unordered</h4>
									<ul>
										<li>Dolor pulvinar etiam.</li>
										<li>Sagittis adipiscing.</li>
										<li>Felis enim feugiat.</li>
									</ul>

									<h4>Alternate</h4>
									<ul class="alt">
										<li>Dolor pulvinar etiam.</li>
										<li>Sagittis adipiscing.</li>
										<li>Felis enim feugiat.</li>
									</ul>

									<h4>Ordered</h4>
									<ol>
										<li>Dolor pulvinar etiam.</li>
										<li>Etiam vel felis viverra.</li>
										<li>Felis enim feugiat.</li>
										<li>Dolor pulvinar etiam.</li>
										<li>Etiam vel felis lorem.</li>
										<li>Felis enim et feugiat.</li>
									</ol>
									<h4>Icons</h4>
									<ul class="icons">
										<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
										<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
										<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
										<li><a href="#" class="icon brands fa-github"><span class="label">Github</span></a></li>
									</ul>

									<h4>Actions</h4>
									<ul class="actions">
										<li><a href="#" class="button primary">Default</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
									<ul class="actions stacked">
										<li><a href="#" class="button primary">Default</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
								</section>

								<section>
									<h3 class="major">Table</h3>
									<h4>Default</h4>
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Item One</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Two</td>
													<td>Vis ac commodo adipiscing arcu aliquet.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Three</td>
													<td> Morbi faucibus arcu accumsan lorem.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Four</td>
													<td>Vitae integer tempus condimentum.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Five</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td>100.00</td>
												</tr>
											</tfoot>
										</table>
									</div>

									<h4>Alternate</h4>
									<div class="table-wrapper">
										<table class="alt">
											<thead>
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Item One</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Two</td>
													<td>Vis ac commodo adipiscing arcu aliquet.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Three</td>
													<td> Morbi faucibus arcu accumsan lorem.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Four</td>
													<td>Vitae integer tempus condimentum.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Five</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td>100.00</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</section>

								<section>
									<h3 class="major">Buttons</h3>
									<ul class="actions">
										<li><a href="#" class="button primary">Primary</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
									<ul class="actions">
										<li><a href="#" class="button">Default</a></li>
										<li><a href="#" class="button small">Small</a></li>
									</ul>
									<ul class="actions">
										<li><a href="#" class="button primary icon solid fa-download">Icon</a></li>
										<li><a href="#" class="button icon solid fa-download">Icon</a></li>
									</ul>
									<ul class="actions">
										<li><span class="button primary disabled">Disabled</span></li>
										<li><span class="button disabled">Disabled</span></li>
									</ul>
								</section>

								<section>
									<h3 class="major">Form</h3>
									<form method="post" action="#">
										<div class="fields">
											<div class="field half">
												<label for="demo-name">Name</label>
												<input type="text" name="demo-name" id="demo-name" value="" placeholder="Jane Doe" />
											</div>
											<div class="field half">
												<label for="demo-email">Email</label>
												<input type="email" name="demo-email" id="demo-email" value="" placeholder="jane@untitled.tld" />
											</div>
											<div class="field">
												<label for="demo-category">Category</label>
												<select name="demo-category" id="demo-category">
													<option value="">-</option>
													<option value="1">Manufacturing</option>
													<option value="1">Shipping</option>
													<option value="1">Administration</option>
													<option value="1">Human Resources</option>
												</select>
											</div>
											<div class="field half">
												<input type="radio" id="demo-priority-low" name="demo-priority" checked>
												<label for="demo-priority-low">Low</label>
											</div>
											<div class="field half">
												<input type="radio" id="demo-priority-high" name="demo-priority">
												<label for="demo-priority-high">High</label>
											</div>
											<div class="field half">
												<input type="checkbox" id="demo-copy" name="demo-copy">
												<label for="demo-copy">Email me a copy</label>
											</div>
											<div class="field half">
												<input type="checkbox" id="demo-human" name="demo-human" checked>
												<label for="demo-human">Not a robot</label>
											</div>
											<div class="field">
												<label for="demo-message">Message</label>
												<textarea name="demo-message" id="demo-message" placeholder="Enter your message" rows="6"></textarea>
											</div>
										</div>
										<ul class="actions">
											<li><input type="submit" value="Send Message" class="primary" /></li>
											<li><input type="reset" value="Reset" /></li>
										</ul>
									</form>
								</section>

							</article>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Pussies of Soap Productions</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<script>

			$('#sel_opponent').on('change', function(){

				var opponent = document.getElementById('sel_opponent').value;
				
				var name = '';
				var elo = '';

				$.ajax({
					type: "POST",
					url: "save.php?i=2",
					data: {opp: opponent},
					async: false,
					success: function(data){

						
					document.getElementById('opponent').innerHTML = data;
					},
				});

				

			});

		</script>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"></script>

	</body>
</html>
