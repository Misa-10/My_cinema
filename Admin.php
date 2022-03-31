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
<li class="hover:text-slate-300 text-3xl"><a href="html.php?page=0&submit=0&reload=off" target="_blank">Home</a></li>
<li class="hover:text-slate-300 text-3xl"><a href="Admin.php?page=0&submit=0&reload=off" >Admin</a></li>
<li class="hover:text-slate-300 text-3xl"><a href="Seance.php?page=0&submit=0&reload=off" target="_blank">Seance</a></li>

</ul>

</header >
<form method ="post" Action="Admin.php?page=1">
<?php cookie_showhistory($conn,$id_user); ?>
<input type="search" name="str" id="n" placeholder="Recherche utilisateur" class="rounded-md h-10">
<input type="submit" name="button--searchbar--user" id="v" value="Chercher" formaction="Admin.php?page=1&submit=searchuser&reload=off" class="bg-indigo-400 rounded-md border-none  w-40 font-sans font-bold text-white hover:bg-indigo-500 h-10"> 


<select name="id--subscribtion" id="id--subscribtion" class="rounded-md h-10">
<option value="0">Aucun abonnement</option>
<option value="1">VIP</option>
<option value="2">Gold</option>
<option value="3">classic</option>
<option value="4">Pass Day</option>
</select>
<input type="submit" name="change--subscription" id="x" value="Modifier abonnement" formaction="Admin.php?page=1&submit=modifsub&reload=on" class="bg-indigo-400 rounded-md border-none  w-80 font-sans font-bold text-white hover:bg-indigo-500 h-10"> 
<input type="submit" name="show--history" id="w" value="Voir l'historique des films" formaction="Admin.php?page=1&submit=showhistory&reload=off" class="bg-indigo-400 rounded-md border-none  w-80 font-sans font-bold text-white hover:bg-indigo-500 h-10"> 


<?php 

modif_sub($conn,$id_user,$id_membership);
Searchbar_user($conn,$debut,$nbr_par_page,$nbr_page);
Reload_SearchBar_user($conn,$debut,$nbr_par_page,$nbr_page); 

Show_history($conn,$id_user,$id_membership,$debut,$nbr_par_page,$nbr_page);
Reload_showhistory($conn,$id_user,$id_membership,$debut,$nbr_par_page,$nbr_page);
?>
<input list="seance--movie" name="seance--movie" placeholder="Seance" class="rounded-md h-10">
<datalist id="seance--movie" >
<?php OptionDataList2($conn);?>

</datalist>
<input type="submit" name="add--movie" id="m" value="Ajouter un film a l'historique" formaction="Admin.php?page=1&submit=addmovie&reload=off" class="bg-indigo-400 rounded-md border-none  w-80 font-sans font-bold text-white hover:bg-indigo-500 h-10"> 



<?php Add_movie($conn,$id_user,$id_membership);?>

</form>


</body>
</html>

