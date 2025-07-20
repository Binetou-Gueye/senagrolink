document.addEventListener('DOMContentLoaded', function(e) {
    const farmerForm = document.getElementById('addProductForm');
    var produit = JSON.parse(localStorage.getItem('produit'))
    if (produit) {
        document.getElementById('productName').value = produit.nom; 
        document.getElementById('productCategory').value = produit.categorie; 
        document.getElementById('productPrice').value = produit.prix_unitaire; 
        document.getElementById('productUnit').value = produit.unite_vente; 
        document.getElementById('productStock').value = produit.quantite_stock; 
        document.getElementById('productDescription').value = produit.description; 
        document.getElementById('organicCheck').checked = produit.certification =='Agriculture biologique' ? true : false
        document.getElementById('localCheck').checked = produit.certification =='Produit local' ? true : false
    }
    // Inscription Agriculteur
    farmerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        var user = JSON.parse(localStorage.getItem('currentUser'))
        console.log(typeof(user))
        console.log(user.boutique)
        // Récupérer les valeurs du formulaire
        const productData = {
            id_agriculteur : user.id,
            id_boutique : user.boutique.id_boutique,
            nom: document.getElementById('productName').value,
            categorie: document.getElementById('productCategory').value,
            prix_unitaire: document.getElementById('productPrice').value,
            unite_vente: document.getElementById('productUnit').value,
            quantite_stock: document.getElementById('productStock').value,
            description: document.getElementById('productDescription').value,
            certification: document.getElementById('organicCheck').checked ? 'Agriculture biologique' :  "Produit local"
        };
                    
        try {

            const response = await fetch('http://localhost/agrolink/backend/api/produits.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });

    //     const result = await response.json();
            
    //     if (result.success == true) {
    //         localStorage.setItem('currentUser', JSON.stringify(newFarmer));            
            
    //         // Afficher message de succès
    //         successMsg.textContent = "Inscription réussie en tant qu'agriculteur!";
    //         successMsg.classList.remove('d-none');
    //         receptionMsg.classList.remove('d-none');
            
    //         // Redirection après 3 secondes
    //         setTimeout(() => {
    //             window.location.href = 'http://localhost/agrolink/fontend/senagrolink-boutique/agriculteur.html';
    //         }, 3000);
    //     } else {
    //         errorMsg.textContent = "Erreur lors de l\'inscription";
    //         errorMsg.classList.remove('d-none');
    //         setTimeout(() => errorMsg.classList.add('d-none'), 3000);
    //         return;
    //     }
    } catch (error) {
        // const messageDiv = document.getElementById('message');
        // messageDiv.className = 'message error';
        // messageDiv.textContent = 'Erreur de connexion au serveur';
        console.log("erreur")
    }   
    });
});