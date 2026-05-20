<?php require_once "../../includes/header.php"; ?>
<?php require_once "../../backend/db.php"; ?>

<section class="menu-page">
    <h1 class="menu-title">Nos Menus</h1>
    <p class="menu-subtitle">Affinez votre recherche</p>

    <div class="menu-filters">

        <div class="filter-block">
            <h2>Prix</h2>
            <p class="filter-value"><span id="prix_val">800</span> € max</p>
            <input type="range" id="prix" min="0" max="800" value="800">
        </div>

        <div class="filter-block">
            <h2>Personnes</h2>
            <p class="filter-value"><span id="pers_val">40</span> min</p>
            <input type="range" id="personnes" min="1" max="40" value="40">
        </div>

        <div class="filter-block">
            <h2>Thèmes</h2>
            <div class="check-list">
                <?php
                $themes = $pdo->query("SELECT * FROM theme")->fetchAll();
                foreach ($themes as $t):
                ?>
                    <label class="check-item">
                        <input type="checkbox" class="theme" value="<?= $t["theme_id"] ?>">
                        <span><?= htmlspecialchars($t["libelle"]) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="filter-block">
            <h2>Régimes</h2>
            <div class="check-list">
                <?php
                $regimes = $pdo->query("SELECT * FROM regime")->fetchAll();
                foreach ($regimes as $r):
                ?>
                    <label class="check-item">
                        <input type="checkbox" class="regime" value="<?= $r["regime_id"] ?>">
                        <span><?= htmlspecialchars($r["libelle"]) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div id="menus" class="menus-results"></div>
</section>

<script>
const prixInput = document.getElementById("prix");
const persInput = document.getElementById("personnes");

prixInput.addEventListener("input", () => {
    document.getElementById("prix_val").innerText = prixInput.value;
});

persInput.addEventListener("input", () => {
    document.getElementById("pers_val").innerText = persInput.value;
});

function loadMenus() {
    const prix = prixInput.value;
    const personnes = persInput.value;

    let themes = [];
    document.querySelectorAll(".theme:checked").forEach(el => {
        themes.push(el.value);
    });

    let regimes = [];
    document.querySelectorAll(".regime:checked").forEach(el => {
        regimes.push(el.value);
    });

    fetch(`menus_data.php?prix=${prix}&personnes=${personnes}&themes=${themes.join(",")}&regimes=${regimes.join(",")}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById("menus").innerHTML = data;
        });
}

document.querySelectorAll(".menu-filters input").forEach(el => {
    el.addEventListener("change", loadMenus);
});

document.querySelectorAll(".menu-filters input[type='range']").forEach(el => {
    el.addEventListener("input", loadMenus);
});

function toggleMenu(id) {
    const content = document.getElementById("menu-" + id);
    const icon = document.getElementById("icon-" + id);

    if (content.style.display === "block") {
        content.style.display = "none";
        icon.innerText = "+";
    } else {
        content.style.display = "block";
        icon.innerText = "−";
    }
}

loadMenus();
</script>

<?php require_once "../../includes/footer.php"; ?>