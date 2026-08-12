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
                    <dl class="row mb-0">
                        <dt class="col-5">Contact</dt>
                        <dd class="col-7">${escapeHtml(trajet.auteur_prenom)} ${escapeHtml(trajet.auteur_nom)}</dd>
                        <dt class="col-5">Telephone</dt>
                        <dd class="col-7">${escapeHtml(trajet.auteur_telephone)}</dd>
                        <dt class="col-5">Email</dt>
                        <dd class="col-7">${escapeHtml(trajet.auteur_email)}</dd>
                        <dt class="col-5">Places totales</dt>
                        <dd class="col-7">${escapeHtml(String(trajet.nb_places_total))}</dd>
                    </dl>
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
