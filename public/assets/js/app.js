document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('trajetDetailsModal');
    if (!modalEl) {
        return;
    }

    const modal = new bootstrap.Modal(modalEl);
    const body = modalEl.querySelector('.modal-body');

    document.querySelectorAll('[data-trajet-details]').forEach((button) => {
        button.addEventListener('click', async () => {
            const trajetId = button.getAttribute('data-trajet-details');
            body.innerHTML = '<p class="text-center mb-0">Chargement...</p>';
            modal.show();

            try {
                const response = await fetch(`/trajets/${trajetId}/details`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (!response.ok) {
                    throw new Error('Requete echouee');
                }

                const data = await response.json();
                const trajet = data.trajet;

                body.innerHTML = `
                    <p>Auteur : <strong>${escapeHtml(trajet.auteur_prenom)} ${escapeHtml(trajet.auteur_nom)}</strong></p>
                    <p>Telephone : <strong>${escapeHtml(trajet.auteur_telephone)}</strong></p>
                    <p>Email : <strong>${escapeHtml(trajet.auteur_email)}</strong></p>
                    <p class="mb-0">Nombre total de places : <strong>${escapeHtml(String(trajet.nb_places_total))}</strong></p>
                `;
            } catch (error) {
                body.innerHTML = '<p class="text-danger mb-0">Impossible de charger les details.</p>';
            }
        });
    });

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }
});
