<?php require_once 'includes/header.php'; ?>

<section class="contact-page container">
    <div class="center-text">
        <h1>Contactez-nous</h1>
        <p>Une question ? Un problème technique ? Notre équipe est là pour vous aider</p>
    </div>

    <div class="contact-grid">
        <form class="contact-form glass-card">
            <h2>Envoyez-nous un message</h2>
            <div class="input-group">
                <label>Nom complet *</label>
                <input type="text" placeholder="Jean Dupont" required>
            </div>
            <div class="input-group">
                <label>Email *</label>
                <input type="email" placeholder="jean@example.com" required>
            </div>
            <div class="input-group">
                <label>Sujet *</label>
                <select>
                    <option>Question Technique</option>
                    <option>Commercial / Offres</option>
                    <option>Autre</option>
                </select>
            </div>
            <div class="input-group">
                <label>Message *</label>
                <textarea rows="5" placeholder="Décrivez votre demande..."></textarea>
            </div>
            <button class="btn-primary">Envoyer le message</button>
        </form>

        <aside class="contact-info">
            <div class="info-card">
                <h3>Adresse</h3>
                <p>42 Avenue des Jeux, 75001 Paris, France</p>
            </div>
            <div class="info-card">
                <h3>Email</h3>
                <p>contact@nebula-gaming.fr</p>
            </div>
        </aside>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>