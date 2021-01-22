

// function AfficherPanier() {

//     var nbProduitsPanier = 0,
//     totalPrice = 0,
//     nbSingleProduitsPanier = 0;

//     for (var key in sessionStorage) { // alert(typeof sessionStorage[key] +" "+sessionStorage[key])

//         if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

//             var quantiteCourante = (parseInt(sessionStorage[key].split(',')[1], 10));
//             nbSingleProduitsPanier++;
//             nbProduitsPanier += quantiteCourante;
//             // on transforme le contenu du sessionStoragege en int pour l'ajouter au prix (c'était un string :s)
//             // prix qu'on multiplie par la quantité
//             totalPrice += ((parseInt(sessionStorage[key].split(',')[3], 10)) * quantiteCourante);
//         }
//     }

//     var child = document.getElementById("panier");
//     child.innerHTML += "<p> contenu du Panier :" + nbSingleProduitsPanier + " produits (" + nbProduitsPanier + "differents) </p><ul>";
//     child.innerHTML += "<br><button onclick=\"resetPanier()\" >Reset le panier </button><br>"

//     for (var key in sessionStorage) {

//         if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

//             var name = sessionStorage[key].split(',')[2];
//             var prix = sessionStorage[key].split(',')[3];
//             var quantite = sessionStorage[key].split(',')[1];
//             child.innerHTML += "<li>  " + quantite + " " + name + ", a " + prix + "€ soit au total" + prix * quantite + " ";

//             var key2 = "\'" + key + "\'";
//             var name2 = "\'" + name + "\'";
//             var prix2 = "\'" + prix + "\'";
//             child.innerHTML += '<button onclick="ajouterPanier(' + key2 + ',' + name2 + ', ' + prix2 + ')">+</button> <button onclick="retirerPanier(' + key2 + ',' + name2 + ', ' + prix2 + ' )")>-</button>';
//             child.innerHTML += "</li>  ";
//         }

//     }
//     child.innerHTML += "<br> Total Price : " + totalPrice + "<ul>";

// }

var nbProduitsPanier = 0,
total = 0,
nbSingleProduitsPanier = 0;


function getPanierHTML()
{
    var contentToAppend="<ul>";

    for (var key in sessionStorage) { // alert(typeof sessionStorage[key] +" "+sessionStorage[key])

        if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

            var quantiteCourante = (parseInt(sessionStorage[key].split(',')[1], 10));
            nbSingleProduitsPanier++;
            nbProduitsPanier += quantiteCourante;
            // on transforme le contenu du sessionStoragege en int pour l'ajouter au prix (c'était un string :s)
            // prix qu'on multiplie par la quantité
            total += ((parseFloat(sessionStorage[key].split(',')[3], 10)) * quantiteCourante);
        }
    }




    for (var key in sessionStorage) {

        if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

            var name = sessionStorage[key].split(',')[2];
            var prix = sessionStorage[key].split(',')[3];
            var quantite = sessionStorage[key].split(',')[1];
            
            var key2 = "\'" + key + "\'";
            var name2 = "\'" + name + "\'";
            var prix2 = "\'" + prix + "\'";
            contentToAppend += "<li>  " + quantite + " " + name + ", a " + prix + "€ soit au total" + prix * quantite + '<button class="m-3" onclick="ajouterPanier(' + key2 + ',' + name2 + ', ' + prix2 + ')"> + </button> <button onclick="retirerPanier(' + key2 + ',' + name2 + ', ' + prix2 + ' )")>-</button>';
            contentToAppend += "</li>  ";
        }

    }
    contentToAppend += "<br> Prix total : " + total + "</ul>";
    console.log(contentToAppend);
    return contentToAppend;
}




function getPanierHTMLNoButton()
{

    var contentToAppend="<ul>";

    for (var key in sessionStorage) { // alert(typeof sessionStorage[key] +" "+sessionStorage[key])

        if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

            var quantiteCourante = (parseFloat(sessionStorage[key].split(',')[1], 10));
            nbSingleProduitsPanier++;
            nbProduitsPanier += quantiteCourante;
            // on transforme le contenu du sessionStoragege en int pour l'ajouter au prix (c'était un string :s)
            // prix qu'on multiplie par la quantité
            total += ((parseFloat(sessionStorage[key].split(',')[3], 10)) * quantiteCourante);
        }
    }


    for (var key in sessionStorage) {

        if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

            var name = sessionStorage[key].split(',')[2];
            var prix = sessionStorage[key].split(',')[3];
            var quantite = sessionStorage[key].split(',')[1];
            
            var key2 = "\'" + key + "\'";
            var name2 = "\'" + name + "\'";
            var prix2 = "\'" + prix + "\'";
            contentToAppend += "<li>  " + quantite + " " + name + ", a " + prix + "€ soit au total" + prix * quantite;
            contentToAppend += "</li>  ";
        }

    }


    if(panierEstVide())
    {
        return '';
    }
    contentToAppend += "<br> Prix total : " + total + "</ul>";
    return contentToAppend;
    
}



function AfficherPanier(idToDisplayIn) {

    var child = document.getElementById(idToDisplayIn);
    child.innerHTML+=getPanierHTML();

}

function AfficherPanierNoBouton(idToDisplayIn) {

    var child = document.getElementById(idToDisplayIn);
    child.innerHTML+=getPanierHTMLNoButton();

}


function appendPanierChild(id)
{
    var child = document.getElementById(id);
    var contextCurrent= child.innerHTML;
    child.innerHTML += getPanierHTML();
    child.inerHTML +=contextCurrent;
}

function flushPanier() {
    document.location.reload(); //on rechharge la page
    DisplayPanierHeader();      //on réaffiche la prévisualisation du panier dans le header 
}


function ajouterPanierdepuisShop(id,name, price, shopId)
{
    if(sessionStorage["CNCShop"]==null || sessionStorage["CNCShop"]==undefined || sessionStorage.getItem("CNCShop")==shopId  )
    {
        sessionStorage.setItem("CNCShop", shopId);
        ajouterPanier(id,name, price );
    }  

    if(sessionStorage.getItem("CNCShop")!=shopId){
        if ( confirm( "Etes vous sur de vouloir ajouter ce produit? Vous ne pouvez avoir un panier que dans une seule boutique, le panier sera remis à 0 " ) ) {
            resetPanier();
            ajouterPanier(id,name, price );
        }
    }
}

//sous fonction - permet d'ajouter a la session et de formater le panier 
function ajouterPanier(id,name, price ) {

            var alreadyExistentItem = (sessionStorage.getItem(id));

            if (alreadyExistentItem == null || alreadyExistentItem == undefined) {
                var addedItem = "CNC," + 1 + "," + name + "," + price;
                sessionStorage.setItem(id, addedItem);
                alert('premier ajout');
            } else {
                var quantity = alreadyExistentItem.split(",")[1];
                quantity++;
                var addedItem = "CNC," + quantity + "," + name + "," + price;
                sessionStorage.setItem(id, addedItem);
            }
            flushPanier()
;
            //

}

function retirerPanier(id,name, price) {

    var alreadyExistentItem = (sessionStorage.getItem(id));

    if (alreadyExistentItem == null || alreadyExistentItem == undefined) {
        
    } else {
        var quantity = alreadyExistentItem.split(",")[1];
        quantity--;
        if (quantity == 0 ){
            sessionStorage.removeItem(id);
        }
        else {
            var addedItem = "CNC," + quantity + "," + name + "," + price;
            sessionStorage.setItem(id, addedItem);
        }
    }
    flushPanier()
}


function resetPanier() {
    sessionStorage.clear();
    flushPanier();
}

function getPanier() {
    
   var panier = [];
    var totalPrice = 0;
    var i = 0;
    for (var key in sessionStorage){

        if(typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {
    
            var prix = sessionStorage[key].split(',')[3];
            var quantite = sessionStorage[key].split(',')[1];
            totalPrice += ((parseFloat(sessionStorage[key].split(',')[3], 10)) * quantite);
            panier [i] = key; 
            panier [i+1] = quantite;  
            i+=2;
        }
    }

    panier [i+1] = totalPrice;

    var url = $('#checkout-btn').val();

    console.log(panier);
    delimiter = '^';
    var postArray = panier.join(delimiter);
    //jsonpanier = JSON.stringify(panier)
    // xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");
    // var a = 3;
    //  $.ajax({ 
    //      type: "POST", 
    //      url: url, 
    //      data: { a: a }, 
    //      success: function() { 
    //             alert("Success"); 
    //       } 
    //     });

    // var neSw = {"data": {"ne": ne, "sw": sw}};
    // $.ajax({
    //     url: url,
    //     type: "post",
    //     data: neSw,
    //     dataType: 'json'
    //  })

    return postArray;
    
       
}
/*$(document).ready(function() {
    // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
});*/

function setPanier() {

    panier = getPanier();
    var input = document.getElementById('cart_input');
    input.setAttribute("value" , panier);
    input.setAttribute("type" , "hidden");

}

function getPanierSize()
{
    var size = 0;
    for(var i in sessionStorage)
    {
        if (typeof sessionStorage[i] == "string" && sessionStorage[i].split(',')[0] == "CNC")
         {
             var quantite = (parseInt(sessionStorage[i].split(',')[1]));
             size+=quantite;
         }
    }
    return size;

}


function PanierInitialise(shopId)
{
    sessionStorage["CNCShop"]=shopId;
}

function DisplayPanierHeader()
{
    document.getElementById("cartValue").innerHTML=getPanierSize();

    if(panierEstVide())
    {
        console.log("panierVide");
        document.getElementById("dropDownContent").innerHTML="<p>Votre panier est vide</p>";
    }
}

function panierEstVide()
{
    return  (getPanierSize()==0);
}





