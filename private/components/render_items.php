<?php
/**
 * ThickImage Component
 *
 * @param string $imagePath   Lien vers l'image
 * @param int    $layerCount  Nombre de couches (épaisseur)
 */
function renderThickImage(
    string $imagePath  = '/assets/image0.png',
    int    $layerCount = 30
): void {
    static $instanceCount = 0;
    $id  = 'ti' . (++$instanceCount);
    $src = htmlspecialchars($imagePath);
?>
<canvas id="<?= $id ?>" width="1200" height="1200" style="background:transparent;display:block;cursor:grab;"></canvas>
 
<script type="module">
import * as THREE from 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.module.js';
 
(function () {
    const SRC    = '<?= $src ?>';
    const LAYERS = <?= $layerCount ?>;
    const GAP    = 0.004; // espacement Z fixe entre plans
 
    /* ── Renderer transparent 1200×1200 ── */
    const canvas = document.getElementById('<?= $id ?>');
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setSize(1200, 1200);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0); // fond 100% transparent
 
    /* ── Scene ── */
    const scene  = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(45, 1, 0.001, 1000);
    camera.position.z = 1.5;
 
    /* ── Charger l'image ── */
    new THREE.TextureLoader().load(SRC, (tex) => {
        tex.minFilter = THREE.LinearFilter;
        tex.magFilter = THREE.LinearFilter;
 
        /* Taille frustum à z=1.5 */
        const fH = 2 * Math.tan((45 * Math.PI / 180) / 2) * 1.5; // ~1.243
        const fW = fH; // canvas carré 1:1
 
        /* L'image tient entièrement dans le canvas (contain) */
        const imgW  = tex.image.naturalWidth  || tex.image.width;
        const imgH  = tex.image.naturalHeight || tex.image.height;
        const imgAR = imgW / imgH;
 
        let planeW, planeH;
        if (imgAR >= 1) {
            planeW = fW;
            planeH = fW / imgAR;
        } else {
            planeH = fH;
            planeW = fH * imgAR;
        }
 
        /* ── Plans empilés ── */
        const group      = new THREE.Group();
        const totalDepth = (LAYERS - 1) * GAP;
 
        for (let i = 0; i < LAYERS; i++) {
            const isFront = (i === LAYERS - 1);
            const mesh = new THREE.Mesh(
                new THREE.PlaneGeometry(planeW, planeH),
                new THREE.MeshBasicMaterial({
                    map:         tex,
                    side:        THREE.DoubleSide,
                    transparent: true,
                    alphaTest:   0.1,
                    depthWrite:  true,
                    depthTest:   true,
                })
            );
            mesh.position.z = -totalDepth + i * GAP;
            mesh.renderOrder = i;
            group.add(mesh);
        }
        scene.add(group);
 
        /* ── Contrôle drag (pan + rotate) ── */
        let isDragging = false;
        let prevX = 0, prevY = 0;
        let rotX = 0, rotY = 0;
        let panX = 0, panY = 0;
        let zoom = 1.5; // camZ initial
 
        /* Clic droit = pan, clic gauche = rotate */
        canvas.addEventListener('contextmenu', e => e.preventDefault());
 
        canvas.addEventListener('mousedown', (e) => {
            isDragging = true;
            prevX = e.clientX;
            prevY = e.clientY;
            canvas.style.cursor = 'grabbing';
        });
 
        window.addEventListener('mouseup', () => {
            isDragging = false;
            canvas.style.cursor = 'grab';
        });
 
        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const dx = e.clientX - prevX;
            const dy = e.clientY - prevY;
            prevX = e.clientX;
            prevY = e.clientY;
 
            if (e.buttons === 1) {
                // Gauche → rotation
                rotY += dx * 0.005;
                rotX -= dy * 0.005;
            } else if (e.buttons === 2) {
                // Droit → pan
                panX += dx * 0.001 * zoom;
                panY -= dy * 0.001 * zoom;
            }
        });
 
        /* Scroll → zoom */
        canvas.addEventListener('wheel', (e) => {
            e.preventDefault();
            zoom += e.deltaY * 0.002;
            zoom = Math.max(0.3, Math.min(10, zoom));
        }, { passive: false });
 
        /* Touch support */
        let lastTouchDist = null;
        canvas.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                isDragging = true;
                prevX = e.touches[0].clientX;
                prevY = e.touches[0].clientY;
            }
        }, { passive: true });
 
        canvas.addEventListener('touchend', () => {
            isDragging = false;
            lastTouchDist = null;
        }, { passive: true });
 
        canvas.addEventListener('touchmove', (e) => {
            if (e.touches.length === 1 && isDragging) {
                const dx = e.touches[0].clientX - prevX;
                const dy = e.touches[0].clientY - prevY;
                prevX = e.touches[0].clientX;
                prevY = e.touches[0].clientY;
                rotY += dx * 0.005;
                rotX -= dy * 0.005;
            } else if (e.touches.length === 2) {
                const dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                if (lastTouchDist !== null) {
                    zoom -= (dist - lastTouchDist) * 0.005;
                    zoom = Math.max(0.3, Math.min(10, zoom));
                }
                lastTouchDist = dist;
            }
        }, { passive: true });
 
        /* ── Rendu ── */
        (function tick() {
            requestAnimationFrame(tick);
            group.rotation.x = rotX;
            group.rotation.y = rotY;
            group.position.x = panX;
            group.position.y = panY;
            camera.position.z = zoom;
            renderer.render(scene, camera);
        })();
    });
})();
</script>
<?php
}
?>