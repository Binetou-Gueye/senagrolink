document.addEventListener('DOMContentLoaded', async () => {
   // 2. Appel API pour récupérer les boutiques
        const response = await fetch(`http://localhost/agrolink/backend/api/boutiques.php`,);   

        const boutiques = await response.json();
        const nombre_boutiques =boutiques.length
        console.log()
        
        const number_boutiques = document.getElementById('number_boutiques');
        number_boutiques.innerHTML = `Découvrez nos ${JSON.stringify(nombre_boutiques)} meilleures boutiques`

        // Afficher les boutiques
        const container = document.getElementById('card-body');
        container.innerHTML = ''; // Vider le conteneur

        if (boutiques.length === 0) {
            container.innerHTML = '<p>Aucun boutiques disponible</p>';
            return;
        }


        boutiques.forEach(boutique => {
            const card = document.createElement('div');
            card.className = 'col-lg-4 col-md-6 animate-fade';
            card.innerHTML = `
                <div class="shop-card">
                    <div class="shop-header">
                        <img src="pictures/boutiqueniayes.jpg" alt="">

                    </div>
                    <div class="shop-body">
                        <h3>${boutique.nom_boutique}</h3>
                        <p class="location"><i class="bi bi-geo-alt"></i> ${boutique.nom_boutique}</p>
                        <div class="shop-info">
                            <span class="rating"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-half"></i> 4.7</span>
                            <span><i class="bi bi-box"></i> 5 produits</span>
                        </div>
                        <a href="visite.html#id=${boutique.id_boutique}" class="btn btn-shop">Visiter la boutique</a>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });
});