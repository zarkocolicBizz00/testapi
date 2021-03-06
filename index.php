<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test REST api</title>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>


</head>

<?php

include "obrada.php";

?>

<body>
    
    <h1>Inicijalna stranica</h1>
    <!-- Ubacio sam post ovde kao method (IZBACI KASNIJE PA POKRENI ) -->
    <form action="">
        <div id="odabir tabele">
            <input type="radio" name = "odabir_tabele" id="radio_kategorija" value = "kategorija">
            <label for="radio_kategorija">kategorija</label>
            <input type="radio" name = "odabir_tabele" id="radio_novosti" value = "novosti">
            <label for="radio_novosti">novosti</label>
        </div>

        <div id="http_zahtev">
            <input type="radio" name = "http_zahtev" id="get" value = "get">
            <label for="get">GET</label>
            <input type="radio" name = "http_zahtev" id="post" value = "post">
            <label for="post">POST</label>
            <input type="radio" name = "http_zahtev" id="put" value = "put">
            <label for="get">PUT</label>
            <input type="radio" name = "http_zahtev" id="delete" value = "delete">
            <label for="delete">DELETE</label>
        </div>

        <!-- Div sekcija za prikaz odgovora GET zahteva sa servera -->

        <pre id="get_odgovor"></pre>

        <!-- Div sekcija za POST formu za novosti -->

        <div id="novosti_post">
            <input type="text" name = "naslov_novosti" placeholder="Unesite naslov novosti">
            <br>
            <textarea name="tekst_novosti" id="tekst_novosti" cols="30" rows="10" placeholder="Unesite_tekst"></textarea>
            <br>
            
            <label for="kategorija_odabir">Kategorija:</label>
            <select name="kategorija_odabir" id="kategorija_odabir">
                <?php
                
                    $mydb -> select("kategorije", "*", null, null, null);
                    while($red = $mydb->getResult()->fetch_object()):
                
                ?>

                <option value="<?php echo $red->id?>"><?php echo $red->kategorija?></option>
                        <?php endwhile; ?>

            </select>
        </div>

        <!-- Div sekcija za POST formu za kategorie -->

        <div id="kategorije_post">
            <input type="text" name = "kategorija_naziv" id="kategorija_naziv" placeholder="Unesite naziv nove kategorije">
        </div>

        <!-- Div sekcija za DELETE formu za novosti i kategorije -->

        <div id="brisanje_reda">
            <input type="text" name = "brisanje" id="brisanje" placeholder="Unesite id koji zelite da obrisete">
        </div>

        <!-- Div sekcija za PUT formu za kategorie -->

        <div id="kategorije_put">
            <input type="text" name = "kategorija_id" id="kategorija_id" placeholder="Unesite ID kategorije">
            <br>
            <input type="text" name = "kategorija_naziv_put" id="kategorija_naziv_put" placeholder="Unesite novi naziv za kategoriju">
        </div>

        <!-- Div sekcija za PUT formu za novosti -->


        <div id="novosti_put">
            <input type="text" name = "novosti_put" id = "novosti_id" placeholder="Unesite ID novosti">
            <br>
            <input type="text" name = "naslov_novosti_put" placeholder="Unesite novi naslov novosti">
            <br>
            <textarea name="tekst_novosti_put" id="tekst_novosti_put" cols="30" rows="10" placeholder="Unesite_tekst_put"></textarea>
            <br>
            
            <label for="kategorija_odabir_put">Odaberite novu kategoriju:</label>
            <select name="kategorija_odabir_put" id="kategorija_odabir_put">
                
            <?php
                
                $mydb -> select("kategorije", "*", null, null, null);
                while($red = $mydb->getResult()->fetch_object()):
            
            ?>

            <option value="<?php echo $red->id?>"><?php echo $red->kategorija?></option>
                    <?php endwhile; ?>

            </select>
        </div>
        
        <div id="greska">

        </div>
        
        <!-- novo submit dugme za posalji zahtev -->
  
        <!-- <div id="submit">
            <input type = "submit" name="posalji" id = "posalji" value = "Posalji zahtev">
        </div>  -->

        <div id="submit">
            <button type="button">Posalji zahtev</button>
        </div>

    </form>

</body>
</html>

<script>

    var nizBlokova = ["get_odgovor", "novosti_post", "kategorije_post", "brisanje_reda", "kategorije_put", "novosti_put"]

    function skloniBlokove(){
        for(const blok of nizBlokova){
            document.getElementById(blok).style.display = "none";
        }
    }

    skloniBlokove();

    $("input[name= http_zahtev]").on("click",prikaziBlok);
    $("input[name= odabir_tabele]").on("click",resetHTTP);
    $("button").on("click", posaljiZahtev);

    function prikaziBlok(){

        switch($("input[name=http_zahtev]:checked")[0].id){

            case "get":
                skloniBlokove();
                document.getElementById("get_odgovor").innerHTML = "";
                document.getElementById(nizBlokova[0]).style.display="block";
                break;
            case "post":
                skloniBlokove();
                if($("input[name=odabir_tabele]:checked").length == 0){
                    document.getElementById(nizBlokova[6]).innerHTML = "Morate odabrati tabelu za manipulaciju";
                    document.getElementById(nizBlokova[6]).style.display="block";
                }else{
                    var tabela = $("input[name=odabir_tabele]:checked")[0].id;
                    if(tabela == "radio_kategorija"){
                        document.getElementById(nizBlokova[2]).style.display="block";
                    }else if(tabela == "radio_novosti"){
                        document.getElementById(nizBlokova[1]).style.display="block";
                    }
                }
                break;
            case "put":
                skloniBlokove();
                if($("input[name=odabir_tabele]:checked").length == 0){
                    document.getElementById(nizBlokova[6]).innerHTML = "Morate odabrati tabelu za manipulaciju";
                    document.getElementById(nizBlokova[6]).style.display="block";
                }else{
                    var tabela = $("input[name=odabir_tabele]:checked")[0].id;
                    if(tabela == "radio_kategorija"){
                        document.getElementById(nizBlokova[4]).style.display="block";
                    }else if(tabela == "radio_novosti"){
                        document.getElementById(nizBlokova[5]).style.display="block";
                    }
                }
                break;
            case "delete":
                skloniBlokove();
                if($("input[name=odabir_tabele]:checked").length == 0){
                    document.getElementById(nizBlokova[6]).innerHTML = "Morate odabrati tabelu za manipulaciju";
                    document.getElementById(nizBlokova[6]).style.display="block";
                }else{
                    var tabela = $("input[name=odabir_tabele]:checked")[0].id;
                    document.getElementById(nizBlokova[2]).style.display="block";
                }
                break;

        }

    }

    function resetHTTP(){
        skloniBlokove();
        $("input[name= http_zahtev]").prop('checked',false);
    
    }
    //sve sto je potrebno da bi uzeli podatke sa resta
    //ovde mi se javlja prva greska koju nemam pojma da resim
    //mislim da je u pitanju neko lose povezivanje ili instanciranje jer mi uopste ne ulazi u switch sto znaci da ne ulazi ni u prvo if
    function posaljiZahtev(){

        if(($("input[name=odabir_tabele]:checked").length != 0) && ($("input[name=http_zahtev]:checked").length != 0)){
            var tabela = $("input[name=odabir_tabele]:checked")[0].id;
            switch( $("input[name=http_zahtev]:checked")[0].id){
                case "get":
                    if(tabela == "radio_novosti"){
                        $.getJSON("http://localhost/rest/api/novosti",function(data){
                            document.getElementById("get_odgovor").innerHTML = JSON.stringify(data,null,2);
                        });
                    }else{
                        $.getJSON("http://localhost/rest/api/kategorije",function(data){
                            document.getElementById("get_odgovor").innerHTML = JSON.stringify(data,null,2);
                        });
                    }
                    break;
                
                case "post":
                    if(tabela == "radio_novosti"){
                        var values = {
                            "naslov": $("input[name=naslov_novosti]").val(),
                            "tekst": $("#tekst_novosti").val(),
                            "kategorija_id": parseInt($("#kategorija_odabir").val())
                        };
                        console.log(values)
                        $.post("http://localhost/rest/api/novosti", JSON.stringify(values), function(data){
                            alert("Odgovor od servera> "+data['poruka']);
                        });
                    }else{
                        var values = {
                            "kategorija": $("input[name=kategorija_naziv]").val(),
                        };
                        console.log(values)
                        $.post("http://localhost/rest/api/kategorije", JSON.stringify(values), function(data){
                            alert("Odgovor od servera> "+data['poruka']);
                        });
                    }
                    break;

                case "put":

                    if(tabela=="radio_novosti"){
                        var values={
                            "naslov": $("input[name=naslov_novosti_put]").val() ,
                            "tekst": $("#tekst_novosti_put").val(),
                            "kategorija_id": parseInt($("#kategorija_odabir_put").val())
                        };

                        $.ajax({
                            url: "http://localhost/rest/api/novosti"+parseInt($("input[name=novosti_id]").val()),
                            type: "PUT",
                            data:JSON.stringify(values),
                            success: function(data) {
                                alert("Odgovor sa servera> "+data["poruka"]);
                            }
                        });

                        // $.put("http://localhost/rest/api/novosti" + parseInt($("input[name=novosti_id]").val()),JSON.stringify(values),function(data){
                        //     alert("Odgovor sa servera> "+data["poruka"]);
                        // });
                        // $.ajax({
                        //     url:"http://localhost/rest/api/novosti/"+parseInt($("input[name=novosti_id]").val()),
                        //     type:"PUT",
                        //     data:JSON.stringify(values)
                        // }).done(function(data){
                        //     alert("Odgovor sa servera> "+data["poruka"]);
                        // });
                    
                    }else{
                        var values={
                            "kategorija": $("input[name=kategorija_naziv_put").val() 
                        };

                        $.ajax({
                            url: "http://localhost/rest/api/kategorije"+parseInt($("input[name=kategorija_id]").val()),
                            type: "PUT",
                            data:JSON.stringify(values),
                            success: function(data) {
                                alert("Odgovor sa servera> "+data["poruka"]);
                            }
                        });

                        // $.put("http://localhost/rest/api/kategorije" + parseInt($("input[name=kategorija_id]").val()),JSON.stringify(values),function(data){
                        //     alert("Odgovor sa servera> "+data["poruka"]);
                        // });
                            
                        // $.ajax({
                        //     url:"http://localhost/rest/api/kategorije/"+parseInt($("input[name=kategorija_id]").val()),
                        //     type:"PUT",
                        //     data:JSON.stringify(values)
                        // }).done(function(data){
                        //     alert("Odgovor sa servera> "+data["poruka"]);
                        // });
                    }
                    break;
                    
                case "delete":
                    if(tabela=="radio_novosti"){
                        $.ajax({
                            url:"http://localhost/rest/api/novosti"+parseInt($("input[name=brisanje]").val()),
                            type:"DELETE",
                            data:JSON.stringify(values)
                        }).done(function(data){
                            alert("Odgovor sa servera> "+data["poruka"]);
                        });
                    }else{
                        $.ajax({
                            url:"http://localhost/rest/api/kategorije"+parseInt($("input[name=brisanje]").val()),
                            type:"DELETE",
                            data:JSON.stringify(values)
                        }).done(function(data){
                            alert("Odgovor sa servera> "+data["poruka"]);
                        });
                    }
                    break;

                default:
                    console.log("def");
            }

        }else{
            console.log("koji kurac");
        }

    }

</script>