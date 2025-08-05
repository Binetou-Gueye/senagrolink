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

    var alertPlaceholder = document.getElementById('liveAlertPlaceholder');
    function alert(message, type) {
    var wrapper = document.createElement('div')
    wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

    alertPlaceholder.append(wrapper)
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
        alertPlaceholder.innerHTML = ""
        try {

            const response = await fetch('http://localhost/agrolink/backend/api/produits.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(productData)
            });

            const result = await response.json();
            if (result.success == true) {
                alert('Votre nouveau produit est enregistré avec succés !', 'success')
                setTimeout(() => {
                    window.location.href = 'http://localhost/agrolink/frontend/senagrolink-boutique/agriculteur.html';
                }, 3000);
            }else{
                alert('Erreur lors de l\'enregistrement !', 'danger')
            }
    
    } catch (error) {
        // const messageDiv = document.getElementById('message');
        // messageDiv.className = 'message error';
        // messageDiv.textContent = 'Erreur de connexion au serveur';
        console.log("erreur")
    }   
    });
});