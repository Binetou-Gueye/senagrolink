// Exemple avec un formulaire HTML
document.getElementById("register-form").addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const formData = {
        username: document.getElementById("username").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
    };

    try {
        const response = await fetch('http://localhost/SenAgroBackend/api/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();
        
        if (data.status === "success") {
            alert("Inscription réussie !");
            window.location.href = "login.html"; // Redirection
        } else {
            alert("Erreur : " + data.message);
        }
    } catch (error) {
        console.error("Erreur :", error);
        alert("Une erreur réseau est survenue.");
    }
});