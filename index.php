
<?php
require_once "db.php";

$debut=null; $nbr_par_page=null; $nbr_page=null; $row = null; $id_user = null; $id_membership= null;
$id_genre_movie=null ; $distributor_movie=null ;

function cookie_seachbar($conn)
{
    if (isset($_POST["submit"])) {
        $str =mysqli_real_escape_string($conn, $_POST["str"]); 
        $cookie_name = "movie";
        $cookie_value = $str;
        setcookie("$cookie_name", "$cookie_value", time()+1500);
        $id_genre_movie = $_POST["genre--movie"]; 
        $cookie_name = "movie_genre";
        $cookie_value = $id_genre_movie;
        setcookie("$cookie_name", "$cookie_value", time()+1500);
        $distributor_movie = $_POST["distributor--movie"];
        $cookie_name = "movie_distributor";
        $cookie_value = $distributor_movie;
        setcookie("$cookie_name", "$cookie_value", time()+1500);
    }
    else {
      
    }
}

function searchbar($conn,$row,$debut,$nbr_par_page,$nbr_page)
{
    if (isset($_POST["submit"])) {
      
        $id_genre_movie = $_POST["genre--movie"]; 
        $distributor_movie = $_POST["distributor--movie"];
        $str =mysqli_real_escape_string($conn, $_POST["str"]); 
        echo "<div class='flex flex-row place-content-evenly  mt-32'>";
        pagination_searchbar($conn, $debut, $nbr_par_page, $nbr_page, $str, $id_genre_movie, $distributor_movie);
      
        $sql ="SELECT movie.title , movie_genre.id_genre,distributor.name from movie join movie_genre on movie.id = movie_genre.id_movie 
      join distributor on movie.id_distributor = distributor.id
      where title like '%$str%' and id_genre like '%$id_genre_movie%' and name like '%$distributor_movie%' limit $debut,$nbr_par_page"; 
        $result=mysqli_query($conn, $sql); 
     
        if (mysqli_num_rows($result)>0) { 
         
            while ($row=mysqli_fetch_assoc($result)){ 
                echo "<div>";
                echo "<div>";
                affiche($row);
                echo "</div>";
                echo "<div class='mt-11 text-white text-lg'>";
                echo "<a>".$row["title"]."</a>";
                echo "</div>";
                echo "</div>";
            }
         
            echo "<div>";
          
        }
        else {
            echo "Aucun film trouver";
        }
      
    }

   

}

function OptionDataList($conn)
{
    $sql = "select name from distributor";
    $result = mysqli_query($conn, $sql);
   
    if (mysqli_num_rows($result)>0) { 
        while ($row=mysqli_fetch_assoc($result)){ 
            echo '<option value="'.$row["name"].'">';
         
        }
    }
    else {
      
    }
}

function Searchbar_user($conn,$debut,$nbr_par_page,$nbr_page)
{
    if (isset($_POST["button--searchbar--user"])) {
      
        $str =mysqli_real_escape_string($conn, $_POST["str"]); 
      
        $cookie_name = "string";
        $cookie_value = $str;
        setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60));
      
        pagination_searchbar_user($conn, $debut, $nbr_par_page, $nbr_page, $str);
      
        $sql ="SELECT subscription.name , user.firstname , user.lastname , user.id as user_id ,membership.id as membership_id  from user left join membership on user.id = membership.id_user 
      left join subscription on membership.id_subscription =subscription.id WHERE CONCAT(firstname, ' ', lastname) LIKE '%$str%' limit $debut,$nbr_par_page"; 
        $result=mysqli_query($conn, $sql); 
      
        if (mysqli_num_rows($result)>0) { 
            while ($row=mysqli_fetch_assoc($result)){ 
            
                echo'<br><input type="radio" name="a" id="'.$row["user_id"].'" value="'.$row["user_id"].'|'.$row["membership_id"].'">
            <label for="'.$row["user_id"].'">'.$row["firstname"]." ".$row["lastname"]." ".$row["name"].'</label><br>"';
            
            }
         
        }
        else {
            echo "Aucun utilisateur trouver";
        }   
    }
   
   
}


function modif_sub($conn,$id_user,$id_membership)
{
   
   
    if (isset($_POST["change--subscription"])) {
      
      
      
        $Subscription = $_POST["id--subscribtion"];
      
      
      
        explode_data_radio($id_user, $id_membership);
      
      
      
      
      
         $sql="SELECT * FROM membership WHERE id_user like $id_user";
         $result =mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result) == null) {
            $sql ="INSERT INTO membership (id_user, id_subscription) VALUES ($id_user, $Subscription);";
            mysqli_query($conn, $sql); 
        }
        else if($Subscription == 0) { 
            $sql ="SET FOREIGN_KEY_CHECKS=0";
            mysqli_query($conn, $sql); 
            $sql =  "DELETE FROM membership WHERE id_user = $id_user";
            mysqli_query($conn, $sql);
        }
        else {
            $sql ="UPDATE membership SET id_subscription = $Subscription WHERE id_user = $id_user";
            mysqli_query($conn, $sql); 
        }
         
      
      
      
    }   
}





function Reload_SearchBar_user($conn,$debut,$nbr_par_page,$nbr_page)
{
    @$page =$_GET["page"];
    $submittype =$_GET["submit"];
    $reload =$_GET["reload"];

    if ($submittype == "searchuser" and $reload == "on" or $submittype == "modifsub" and $reload == "on" ) {
   

           $str =$_COOKIE['string'];
      
          pagination_searchbar_user($conn, $debut, $nbr_par_page, $nbr_page, $str);

     
      
          $sql ="SELECT subscription.name , user.firstname , user.lastname , user.id as user_id ,membership.id as membership_id  from user left join membership on user.id = membership.id_user 
      left join subscription on membership.id_subscription =subscription.id WHERE CONCAT(firstname, ' ', lastname) LIKE '%$str%' limit $debut,$nbr_par_page";
          $result=mysqli_query($conn, $sql); 
      
        if (mysqli_num_rows($result)>0) { 
            while ($row=mysqli_fetch_assoc($result)){ 
            
                echo'<br><input type="radio" name="a" id="'.$row["user_id"].'" value="'.$row["user_id"].'|'.$row["membership_id"].'">
            <label for="'.$row["user_id"].'">'.$row["firstname"]." ".$row["lastname"]." ".$row["name"].'</label><br>"';            
            }
         
        }
    }
    else {
      
    }
   
}

function Show_history($conn,$id_user,$id_membership,$debut,$nbr_par_page,$nbr_page)
{
   
    if (isset($_POST["show--history"])) {
      
      
      
        explode_data_radio($id_user, $id_membership);
      
      
  
      
        pagination_showhistory($conn, $debut, $nbr_par_page, $nbr_page, $id_user);
      
     
         $table_situation =0;
         $sql ="SELECT membership.id , user.firstname , user.lastname ,movie_schedule.date_begin,movie.title,room.name from user join 
         membership on user.id = membership.id_user join membership_log on membership.id = membership_log.id_membership join movie_schedule 
         on movie_schedule.id = membership_log.id_session join movie on movie.id = movie_schedule.id_movie join room on 
         room.id = movie_schedule.id_room where membership.id_user like '$id_user' limit $debut,$nbr_par_page"; 
         $result= mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
            while ($row=mysqli_fetch_assoc($result)){ 
               
                if ($table_situation==0) {
                    echo "<table> <tr> <th>".$row["firstname"]." ".$row["lastname"]."</th></tr><tr>
                  <td>Nom de films</td>
                  <td>Date diffusion</td>
                  <td>Nom de la salle</td>
                  </tr>
                  ";
                    $table_situation=1;
                }
                else{
                    echo "
                  <tr>
                  <td>".$row["title"]."</td>
                  <td>".$row["date_begin"]."</td>
                  <td>".$row["name"]."</td>
                  </tr>";
                  
                }
               
               
            }
            
            echo " </table>";
            
            
        }
        else {
            echo "Aucun Historique de film";
        }   
      
      
    }
   
}

function pagination_showhistory($conn,&$debut,&$nbr_par_page,$nbr_page,$id_user)
{
    $sql_page="SELECT membership.id , user.firstname , user.lastname ,count(movie_schedule.date_begin) as number_row,
   movie.title,room.name from user join membership on user.id = membership.id_user join membership_log on membership.id = 
   membership_log.id_membership join movie_schedule on movie_schedule.id = membership_log.id_session join movie on movie.id = 
   movie_schedule.id_movie join room on room.id = movie_schedule.id_room where membership.id_user like '$id_user'";
   
    $result= mysqli_query($conn, $sql_page); 
    if (mysqli_num_rows($result)>0) { 
        $row=mysqli_fetch_assoc($result);
      
        @$page =$_GET["page"];
      
        $nbr_par_page = 25;
      
        $nbr_page = ceil($row['number_row']/$nbr_par_page);
      
        $debut =($page-1)*$nbr_par_page;
      
      
        $page_limit = $page+9;
        if ($nbr_page<$page_limit) {
            $page_limit = $nbr_page;
        }
        else {
         
        }
        echo "<div class='flex flex-col place-content-evenly text-center text-white font-bold'>";
        for ($page; $page <=$page_limit ; $page++) { 
            echo "<div class='bg-indigo-400 hover:bg-indigo-500 h-10 w-10 rounded-md'>";
            echo "<a href='?page=$page&submit=showhistory&reload=on' class=''>$page</a>";
            echo "</div>";
         
        }
        echo "</div>";
      
      
      
      
    }
   
}

function Reload_showhistory($conn,$id_user,$id_membership,$debut,$nbr_par_page,$nbr_page)
{
    @$page =$_GET["page"];
    $submittype =$_GET["submit"];
    $reload =$_GET["reload"];
   
   
   
   
    if($submittype == "showhistory" and $reload == "on") {

      
        if (!isset($_COOKIE['id_user'])) {
            $id_user= null;
        }
        else {
         
            $id_user =$_COOKIE['id_user'];
         
        }  
      
      
      
        pagination_showhistory($conn, $debut, $nbr_par_page, $nbr_page, $id_user);
   
      
         $table_situation =0;
         $sql ="SELECT membership.id , user.firstname , user.lastname ,movie_schedule.date_begin,movie.title,room.name from user join 
         membership on user.id = membership.id_user join membership_log on membership.id = membership_log.id_membership join movie_schedule 
         on movie_schedule.id = membership_log.id_session join movie on movie.id = movie_schedule.id_movie join room on 
         room.id = movie_schedule.id_room where membership.id_user like '$id_user' limit $debut,$nbr_par_page"; 
         $result= mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
            while ($row=mysqli_fetch_assoc($result)){ 
               
                if ($table_situation==0) {
                    echo "<table> <tr> <th>".$row["firstname"]." ".$row["lastname"]."</th></tr><tr>
                  <td>Nom de films</td>
                  <td>Date diffusion</td>
                  <td>Nom de la salle</td>
                  </tr>
                  ";
                    $table_situation=1;
                }
                else{
                    echo "
                  <tr>
                  <td>".$row["title"]."</td>
                  <td>".$row["date_begin"]."</td>
                  <td>".$row["name"]."</td>
                  </tr>";
                  
                }
               
               
            }
            
             echo " </table>";
            
            
        }
        else {
            echo "Aucun Historique de film";
        }   
      
      
    }
    else {
      
    }
}

function cookie_showhistory($conn,$id_user)
{
    if (isset($_POST["show--history"])) {
        explode_data_radio($id_user, $id_membership);
   
        $cookie_name = "id_user";
        $cookie_value = $id_user;
        setcookie("$cookie_name", "$cookie_value", time()+1500);
    }
    else {
      
    }
}

function explode_data_radio(&$id_user,&$id_membership)
{
    if (!isset($_POST["a"])) {
        echo "Vous avez selectione aucun client";
    }
    else {
        $radio = $_POST["a"]; 
        $explode_radio =explode("|", $radio);
        $id_user = $explode_radio[0];
        $id_membership = $explode_radio[1];
    }
   
   
   
}

function Add_movie($conn,$id_user,$id_membership)
{
    if (isset($_POST["add--movie"])) {
      
      
        explode_data_radio($id_user, $id_membership);
      
     
        $seance_movie = $_POST["seance--movie"];
        $seance_movie_explode = explode(" le ", $seance_movie);
      
      
       
         $sql="SELECT movie.title,  membership_log.id_session , movie_schedule.date_begin from movie join movie_schedule on 
         movie_schedule.id_movie = movie.id join membership_log on movie_schedule.id = membership_log.id_session where movie.title like '%$seance_movie_explode[0]%' and movie_schedule.date_begin like'%$seance_movie_explode[1]%'";
         $result= mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
            
            $row=mysqli_fetch_assoc($result);
            $row_id_session =$row["id_session"];
            $sql ="INSERT INTO membership_log (id_membership, id_session) VALUES ($id_membership,$row_id_session );";
            mysqli_query($conn, $sql); 
            
            
        }
      
      
      
    }   
}

function OptionDataList2($conn)
{
    $sql = "SELECT movie_schedule.id,movie.title,movie_schedule.date_begin from movie_schedule join movie on movie.id=movie_schedule.id_movie";
    $result = mysqli_query($conn, $sql);
   
    if (mysqli_num_rows($result)>0) { 
        while ($row=mysqli_fetch_assoc($result)){ 
            echo '<option value="'.$row["title"]." le ".$row["date_begin"].'">';
         
        }
    }
    else {
      
    }
}

function OptionSelectRoom($conn)
{
    $sql = "SELECT room.id, room.name from room";
    $result = mysqli_query($conn, $sql);
   
    if (mysqli_num_rows($result)>0) {
        while ($row=mysqli_fetch_assoc($result)){ 
            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
         
        }
    }
    else {
      
    }
}

function OptionsDataList_SearchMovie($conn)   
{
    $sql = "SELECT movie.id, movie.title from movie";
    $result = mysqli_query($conn, $sql);
   
    if (mysqli_num_rows($result)>0) { 
        while ($row=mysqli_fetch_assoc($result)){ 
            echo '<option value="'.$row["title"].'">';
         
        }
    }
    else {
      
    }
}

function AddSeance($conn)
{
    if (isset($_POST["add--seance"])) {
      
        $datetime_seance = $_POST["datetime--seance"];
        $id_room = $_POST["room"];
        $title_movie = $_POST["search--movie"];
        $title_movie=mysqli_real_escape_string($conn, $title_movie);
        $string_tr = array("T"=>" ","-"=>"/");
        $datetime_seance= strtr($datetime_seance, $string_tr);
     
     
      
        $sql = "SELECT movie.id, movie.title from movie where title like '%$title_movie%'";      
        $result= mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
         
            $row=mysqli_fetch_assoc($result);
            $id_movie =$row["id"];
         
            $sql ="INSERT INTO movie_schedule (id_movie, id_room,date_begin) VALUES ($id_movie,$id_room,'$datetime_seance' );";
            mysqli_query($conn, $sql);

         
         
         
        }
    }
   
   
}   

function cookie_SearchMovie_ByDate($conn)
{
    if (isset($_POST["searchmovie--bydate"])) {
        $date_seance = $_POST["date--seance"];
        $cookie_name = "date-seance";
        $cookie_value = $date_seance;
        setcookie("$cookie_name", "$cookie_value", time()+1500);
      
    }
    else {
      
    }
}

function SearchMovie_ByDate($conn,$debut,$nbr_par_page,$nbr_page)
{
    if (isset($_POST["searchmovie--bydate"])) {
        $date_seance = $_POST["date--seance"];
      
        echo "<div class='flex flex-row place-content-evenly mt-32'>";


        pagination_SearchMovie_ByDate($conn, $debut, $nbr_par_page, $nbr_page, $date_seance);
      
        $sql = "SELECT movie_schedule.id_movie,movie.title,movie_schedule.date_begin from movie_schedule join movie on movie.id = movie_schedule.id_movie 
      where date_begin like '%$date_seance%' limit $debut,$nbr_par_page";      
        $result= mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
            while ($row=mysqli_fetch_assoc($result)){ 
                $explode_date_begin=explode(" ", $row["date_begin"]);
            
                echo "<div>";
                echo "<div>";
                affiche($row);
                echo "</div>";
                echo "<div class='mt-11 text-white text-lg'>";
                echo "<a>".$row["title"].$explode_date_begin[1]."</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "<div>";
        }
        else {
            echo "Aucun film trouver";
        }   
    }
}

function Reload_SearchMovie_ByDate($conn,$debut,$nbr_par_page,$nbr_page)
{
    @$page =$_GET["page"];
    $submittype =$_GET["submit"];
    $reload = $_GET["reload"];
   
    if ($submittype == "moviedate" and $reload == "on") { 
      
        if (!isset($_COOKIE['date-seance'])) {
            $date_seance= null;
        }
        else {
         
            $date_seance =$_COOKIE['date-seance'];
         
        }  
      
        echo "<div class='flex flex-row place-content-evenly mt-32'>";
      
        pagination_SearchMovie_ByDate($conn, $debut, $nbr_par_page, $nbr_page, $date_seance);
      
      
        $sql = "SELECT movie_schedule.id_movie,movie.title,movie_schedule.date_begin from movie_schedule join movie on movie.id = movie_schedule.id_movie 
      where date_begin like '%$date_seance%' limit $debut,$nbr_par_page";      
        $result= mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
        
            while ($row=mysqli_fetch_assoc($result)){ 
                $explode_date_begin=explode(" ", $row["date_begin"]);
            
                echo "<div>";
                echo "<div>";
                affiche($row);
                echo "</div>";
                echo "<div class='mt-11 text-white text-lg'>";
                echo "<a>".$row["title"].$explode_date_begin[1]."</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "<div>";
        }
        else {
            echo "Aucun film trouver";
        }   
      
    }
    else {
      
    }
   
}

function pagination_SearchMovie_ByDate($conn,&$debut,&$nbr_par_page,$nbr_page,$date_seance)
{
    $sql_page="SELECT movie_schedule.id_movie,movie.title,movie_schedule.date_begin, count(movie.title) as number_row from movie_schedule join movie on movie.id = movie_schedule.id_movie 
   where date_begin like '%$date_seance%'";
   
    $result= mysqli_query($conn, $sql_page); 
    if (mysqli_num_rows($result)>0) { 
        $row=mysqli_fetch_assoc($result);
      
        @$page =$_GET["page"];
      
        $nbr_par_page = 3;
      
        $nbr_page = ceil($row['number_row']/$nbr_par_page);
      
        $debut =($page-1)*$nbr_par_page;
      
      
        $page_limit = $page+9;
        if ($nbr_page<$page_limit) {
            $page_limit = $nbr_page;
        }
        else {
         
        }
        echo "<div class='flex flex-col place-content-evenly text-center text-white font-bold'>";
        for ($page; $page <=$page_limit ; $page++) { 
            echo "<div class='bg-indigo-400 hover:bg-indigo-500 h-10 w-10 rounded-md'>";
            echo "<a href='?page=$page&submit=moviedate&reload=on' class=''>$page</a>";
            echo "</div>";
         
        }
        echo "</div>";
      
      
    }
   
}


function pagination_searchbar_user($conn,&$debut,&$nbr_par_page,$nbr_page,$str)
{
   
    $sql_page="SELECT subscription.name , user.firstname , user.lastname , user.id as user_id ,membership.id as membership_id ,count(user.id) 
   as number_row  from user left join membership on user.id = membership.id_user left join subscription on 
   membership.id_subscription =subscription.id WHERE CONCAT(firstname, ' ', lastname) LIKE '%$str%'";
   
    $result= mysqli_query($conn, $sql_page);
    if (mysqli_num_rows($result)>0) { 
        $row=mysqli_fetch_assoc($result);
      
        @$page =$_GET["page"];
      
        $nbr_par_page = 10;
      
        $nbr_page = ceil($row['number_row']/$nbr_par_page);
      
        $debut =($page-1)*$nbr_par_page;
      
      
      
        $page_limit = $page+9;
        if ($nbr_page<$page_limit) {
            $page_limit = $nbr_page;
        }
        else {
         
        }
        echo "<div class='flex flex-col place-content-evenly text-center text-white font-bold'>";
        for ($page; $page <=$page_limit ; $page++) { 
            echo "<div class='bg-indigo-400 hover:bg-indigo-500 h-10 w-10 rounded-md'>";
            echo "<a href='?page=$page&submit=searchuser&reload=on' class=''>$page</a>";
            echo "</div>";
         
        }
        echo "</div>";
      
      
    }
   
}

function pagination_searchbar($conn,&$debut,&$nbr_par_page,$nbr_page,$str,$id_genre_movie,$distributor_movie)
{
    $sql_page="SELECT movie.title , movie_genre.id_genre,distributor.name,count(movie.title) 
   as number_row from movie join movie_genre on movie.id = movie_genre.id_movie 
   join distributor on movie.id_distributor = distributor.id
   where title like '%$str%' and id_genre like '%$id_genre_movie%' and name like '%$distributor_movie%'";
   
    $result= mysqli_query($conn, $sql_page); 
    if (mysqli_num_rows($result)>0) { 
        $row=mysqli_fetch_assoc($result);
      
        @$page =$_GET["page"];
      
        $nbr_par_page = 3;
      
        $nbr_page = ceil($row['number_row']/$nbr_par_page);
      
        $debut =($page-1)*$nbr_par_page;
      
      
        $page_limit = $page+9;
        if ($nbr_page<$page_limit) {
            $page_limit = $nbr_page;
        }
        else {
         
        }
        echo "<div class='flex flex-col place-content-evenly text-center text-white font-bold'>";
        for ($page; $page <=$page_limit ; $page++) { 
            echo "<div class='bg-indigo-400 hover:bg-indigo-500 h-10 w-10 rounded-md'>";
            echo "<a href='?page=$page&submit=movie&reload=on' class=''>$page</a>";
            echo "</div>";
         
        }
        echo "</div>";
      
      
      
      
    }
   
}

function Reload_SearchBar($conn,$debut,$nbr_par_page,$nbr_page,$id_genre_movie,$distributor_movie)
{
    @$page =$_GET["page"];
    $submittype =$_GET["submit"];
    $reload =$_GET["reload"];
   
   
   
   
    if($submittype == "movie" and $reload == "on") {
      
        if (!isset($_COOKIE['movie'])) {
            $str= null;
        }
        else {
         
            $str =$_COOKIE['movie'];
         
        }  
        if (!isset($_COOKIE['movie_genre'])) {
            $id_genre_movie= null;
        }
        else {
         
            $id_genre_movie =$_COOKIE['movie_genre'];
         
        } 
        if (!isset($_COOKIE['movie_distributor'])) {
            $distributor_movie= null;
        }
        else {
         
            $distributor_movie =$_COOKIE['movie_distributor'];
         
        }   
      
        echo "<div class='flex flex-row place-content-evenly   mt-32'>";
      
        pagination_searchbar($conn, $debut, $nbr_par_page, $nbr_page, $str, $id_genre_movie, $distributor_movie);

      
        $sql ="SELECT movie.title , movie_genre.id_genre,distributor.name from movie join movie_genre on movie.id = movie_genre.id_movie 
      join distributor on movie.id_distributor = distributor.id
      where title like '%$str%' and id_genre like '%$id_genre_movie%' and name like '%$distributor_movie%' limit $debut,$nbr_par_page"; 
        $result=mysqli_query($conn, $sql); 
        if (mysqli_num_rows($result)>0) { 
         
            while ($row=mysqli_fetch_assoc($result)){ 
                echo "<div>";
                echo "<div>";
                affiche($row);
                echo "</div>";
                echo "<div class='mt-11 text-white text-lg'>";
                echo "<a>".$row["title"]."</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "<div>";
        }
        else {
            echo "Aucun film trouver";
        } 
      
    }
    else {
      
    }
   

}





function affiche($row)
{
   
    $recherche=$row["title"];
   
    $url = "http://www.omdbapi.com/?apikey=d0e2ede3&t=".urlencode($recherche)."&y=&plot=short&r=json"; 
   
    $json_response = file_get_contents($url);
    $object_response = json_decode($json_response);
   
    $poster_url="";
    if(!is_null($object_response) && isset($object_response->Poster)) {
        $poster_url = $object_response->Poster;
        echo "<img src='$poster_url'>"."\n";
    }
   
   
   
}





?>


