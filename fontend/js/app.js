const form = document.getElementById('myForm');
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Collecte les données du formulaire
    const formData = new FormData(form);

    // Transforme les données en objet JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });

    // Envoie les données en JSON
    fetch('https://formspree.io/f/xkggbeqb', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData), // Données au format JSON
    })
    .then(response => {
        if (response.ok) {
            alert('Formulaire soumis avec succès.');
            form.reset(); // Réinitialise le formulaire
        } else {
            alert('Erreur lors de la soumission du formulaire.');
        }
    })
    .catch(error => {
        alert('Une erreur réseau est survenue.');
        console.error(error);
    });
});

