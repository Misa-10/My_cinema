<?php include_once("index.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<title>Document</title>
</head>
<body class="bg-indigo-900">
<header class="border-b border-white border-opacity-20 text-3x flex flex-row space-x-20 h-24 items-center">
<a class="text-4xl text-white font-bold ml-8">My Cinema</a>
<ul class="text-white font-bold flex flex-row space-x-7 items-center">
<li class="hover:text-slate-300 text-3xl"><a href="html.php?page=0&submit=0&reload=off">Home</a></li>
<li class="hover:text-slate-300 text-3xl"><a href="Admin.php?page=0&submit=0&reload=off" target="_blank">Admin</a></li>
<li class="hover:text-slate-300 text-3xl"><a href="Seance.php?page=0&submit=0&reload=off" target="_blank">Seance</a></li>

</ul>

</header >
<form method ="post" action="html.php?page=1" class="mt-14">
<?php cookie_seachbar($conn);
cookie_SearchMovie_ByDate($conn);?>
<div class="flex flex-row space-x-32 place-content-around">
<div class="flex flex-row space-x-8">
<div>
<input list="distributor--movie" name="distributor--movie" placeholder="Distributeur" class="rounded-md h-10">

<datalist id="distributor--movie"  >
<?php OptionDataList($conn); ?>

</datalist>
</div>
<div>
<select name="genre--movie" id="genre--movie" class="rounded-md h-10" >
<option value="">Genre</option>
<option value="1">Action</option>
<option value="3">Adventure</option>
<option value="2">Animation</option>
<option value="7">Biography</option>
<option value="5">Comedy</option>
<option value="8">Crime</option>
<option value="4">Drama</option>
<option value="13">Family</option>
<option value="9">Fantasy</option>
<option value="10">Horror</option>
<option value="6">Mystery</option>
<option value="12">Romance</option>
<option value="11">Sci Fi</option>
<option value="14">Thriller</option>
</select>
</div>
<div>
<input type="search" name="str" id="n" placeholder="Nom de film" class="rounded-md h-10">
</div>
<div>
<input type="submit" name="submit" id="v" value="Chercher" formaction="html.php?page=1&submit=movie&reload=off" class="bg-indigo-400 rounded-md border-none  w-40 font-sans font-bold text-white hover:bg-indigo-500 h-10"> 
</div>
</div>
<div class="flex flex-row space-x-8">
<div>
<input type="date" id="date--seance" name="date--seance" class="rounded-md h-10">
</div>
<div>
<input type="submit" name="searchmovie--bydate" formaction="html.php?page=1&submit=moviedate&reload=off" id="q" value="Voir les films" class="h-10 bg-indigo-400 rounded-md border-none  w-40 font-bold text-white hover:bg-indigo-500"> 
</div>
</div>
</div>

<?php SearchMovie_ByDate($conn,$debut,$nbr_par_page,$nbr_page);
Reload_SearchMovie_ByDate($conn,$debut,$nbr_par_page,$nbr_page);?>
<?php 

searchbar($conn,$row,$debut,$nbr_par_page,$nbr_page);
Reload_SearchBar($conn,$debut,$nbr_par_page,$nbr_page,$id_genre_movie,$distributor_movie); ?>

</form>


</body>
</html>
