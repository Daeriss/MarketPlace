

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

function AfficherPanier() {

    

    for (var key in sessionStorage) { // alert(typeof sessionStorage[key] +" "+sessionStorage[key])

        if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

            var quantiteCourante = (parseInt(sessionStorage[key].split(',')[1], 10));
            nbSingleProduitsPanier++;
            nbProduitsPanier += quantiteCourante;
            // on transforme le contenu du sessionStoragege en int pour l'ajouter au prix (c'était un string :s)
            // prix qu'on multiplie par la quantité
            total += ((parseInt(sessionStorage[key].split(',')[3], 10)) * quantiteCourante);
        }
    }


    var child = document.getElementById("panier");

    for (var key in sessionStorage) {

        if (typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {

            var name = sessionStorage[key].split(',')[2];
            var prix = sessionStorage[key].split(',')[3];
            var quantite = sessionStorage[key].split(',')[1];
            
            var key2 = "\'" + key + "\'";
            var name2 = "\'" + name + "\'";
            var prix2 = "\'" + prix + "\'";
            child.innerHTML += "<li>  " + quantite + " " + name + ", a " + prix + "€ soit au total" + prix * quantite + '<button class="m-3" onclick="ajouterPanier(' + key2 + ',' + name2 + ', ' + prix2 + ')"> + </button> <button onclick="retirerPanier(' + key2 + ',' + name2 + ', ' + prix2 + ' )")>-</button>';
            child.innerHTML += "</li>  ";
        }

    }
    child.innerHTML += "<br> Prix total : " + total + "<ul>";

}



function flushPanier() {

    document.location.reload(); 
}

function ajouterPanier(id,name, price) {

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

    
   var panier = ["0" , "0", "0"];
    var totalPrice = 0;
    var i = 0;
    for (var key in sessionStorage){

        
        if(typeof sessionStorage[key] == "string" && sessionStorage[key].split(',')[0] == "CNC") {
    
            var prix = sessionStorage[key].split(',')[3];
            var quantite = sessionStorage[key].split(',')[1];
            totalPrice += ((parseInt(sessionStorage[key].split(',')[3], 10)) * quantite);
            panier [i] = key; 
            panier [i+1] = quantite;  
        }
        i+=2;  
    }

    panier [i+1] = totalPrice;

    var url = $('#checkout-btn').val();

    console.log(panier);
    delimiter = '^';
    var postArray = panier.join(delimiter);
    jsonpanier = JSON.stringify(panier)
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

function setPanier() {

    panier = getPanier();
    var input = document.getElementById('input');
    input.setAttribute("value" , panier);

}




