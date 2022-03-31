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
<li class="hover:text-slate-300 text-3xl"><a href="Seance.php?page=0&submit=0&reload=off">Seance</a></li>

</ul>

</header >
<form method="post" >
<input type="datetime-local" id="datetime--seance" name="datetime--seance" step="1" class="rounded-md h-10">
<select name="room" id="room" class="rounded-md h-10">
<option value="0">Salle</option>
<?php OptionSelectRoom($conn);?>
</select>

<input list="search--movie" name="search--movie" placeholder="Recherche film" class="rounded-md h-10">
<datalist id="search--movie"  >
<?php OptionsDataList_SearchMovie($conn);?>
</datalist>

<input type="submit" name="add--seance" id="v" value="ajouter une seance" class="bg-indigo-400 rounded-md border-none  w-40 font-sans font-bold text-white hover:bg-indigo-500 h-10"> 
<?php AddSeance($conn);?>

</form>
</body>
</html>

