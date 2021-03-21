<header>
	<nav>
		<ul class="nav-links">
			<li style="margin-left:3em"><img style="margin-bottom:15px" src="./img/book_logo.png" width="99" height="82" alt="logo-site"></td>
			<li style="margin-top:20px"><a class="title">Books & cie</a></li>
		</ul>
	</nav>
	
	<div class="catalogue-and-research-block">
		<nav>
			<ul class="nav-links" style="margin-top:3em">
				<li class="dropdown" style="top:-10px">
					<a href="#" style="font-size:20px;text-decoration:none;margin-left:2em" class="dropdown-toggle" data-toggle="dropdown">CATALOGUE</a>
					<ul style="margin-top:1em" class="dropdown-menu multi-column columns-3">
						<div class="row">
							<div class="col-sm-4">
								<ul class="multi-column-dropdown">
									<li><a href="./research/books.php?category=Art">Art</a></li>
									<li><a href="./research/books.php?category=Biography">Biography</a></li>
									<li><a href="./research/books.php?category=Business">Business</a></li>
									<li><a href="./research/books.php?category=Computers">Computers</a></li>
									<li><a href="./research/books.php?category=Cooking">Cooking</a></li>
									<li><a href="./research/books.php?category=Fiction">Fiction</a></li>
								</ul>
							</div>
							<div class="col-sm-4">
								<ul class="multi-column-dropdown">
									<li><a href="./research/books.php?category=Health">Health</a></li>
									<li><a href="./research/books.php?category=History">History</a></li>
									<li><a href="./research/books.php?category=Humor">Humor</a></li>
									<li><a href="./research/books.php?category=Mathematics">Mathematics</a></li>
									<li><a href="./research/books.php?category=Medical">Medical</a></li>	
									<li><a href="./research/books.php?category=Music">Music</a></li>										
								</ul>
							</div>
							<div class="col-sm-4">
								<ul class="multi-column-dropdown">
									<li><a href="./research/books.php?category=Nature">Nature</a></li>
									<li><a href="./research/books.php?category=Philosophy">Philosophy</a></li>
									<li><a href="./research/books.php?category=Poetry">Poetry</a></li>
									<li><a href="./research/books.php?category=Psychology">Psychology</a></li>
									<li><a href="./research/books.php?category=Religion">Religion</a></li>
									<li><a href="./research/books.php?category=Science">Science</a></li>
								</ul>
							</div>
						</div>
					</ul>
				</li>
				<li>
					<div class="input-group">
						<form action="./research/books.php" method="POST">
							<input type="text" name="search" class="form-control" placeholder="I'm looking for a book...">
							<div class="input-group-append">
								<button class="btn btn-secondary" type="submit">
									<i class="fa fa-search"></i>
								</button>	
							</div>
						</form>
					</div>
				</li>

				<li style="float:right">
					<table style="margin-right:2em;margin-top:6px">
						<tr>
							<?php
								if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email'])))){
									echo "<td style='float:right'><a href='./account/deconnexion.php' style='font-size:16px'>LOG OUT</a></td>";
								}
								else{
									echo "<td style='float:right'><a id='connexion' style='font-size:16px' href='#'>LOG IN</a></td>";
								}
							?>
							<td style="padding-left:7px"><img src="./img/logo_account.png" width="20" height="20" alt="logo-account"></td>
						</tr>
					</table>
				</li>
				
				<?php
					if(((isset($_SESSION['email'])) AND (!empty($_SESSION['email'])))){
						echo "<li style='float:right'><div class='dropdown show'>
								<a class='btn btn-secondary dropdown-toggle' style='background-color:#023f76;font-size:16px' href='#' role='button' id='dropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
									My account
								</a>
								<div class='dropdown-menu' aria-labelledby='dropdownMenuLink'>
									<a class='dropdown-item' style='color:black' href='./account/profil.php'>My Profil</a>
									<a class='dropdown-item' style='color:black' href='./account/favoriteBooks.php'>My favorites history</a>
								</div>
							</div></li>";
					}
				?>
			</ul>
		</nav>
	</div>
</header>		