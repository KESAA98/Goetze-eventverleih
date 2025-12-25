<!-- backend/head.html -->
<style>
  :root{
    /* MUSS zu titel.html passen */
    --title-pad-x: 72px;
    --logo-pad-left: 32px;
    --nav-pad-right: 32px;

    --edge-left: calc(var(--title-pad-x) + var(--logo-pad-left));
    --edge-right: calc(var(--title-pad-x) + var(--nav-pad-right));

    --radius: 18px;
    --shadow-soft: 0 12px 34px rgba(0,0,0,.14);
  }

  .head-wrap{
    padding-top: var(--edge-left);
    padding-left: var(--edge-left);
    padding-right: var(--edge-right);
    padding-bottom: var(--edge-left);
    width:100%;
  }

  .hero{
    width:100%;
    min-height:55vh;
    border-radius: var(--radius);
    overflow:hidden;
    position:relative;
    background:#d9dde3;
    box-shadow: var(--shadow-soft);
  }

  .hero__bg{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    transform: scale(1.02);
  }

  .hero__dim{
    position:absolute;
    inset:0;
    background: rgba(0,0,0,.35);
  }

  .items-strip{
    width:100%;
    margin-top:18px;
    height:25vh;
    min-height:180px;
    border-radius: var(--radius);
    overflow:hidden;
    background:#fff;
    box-shadow: var(--shadow-soft);
  }

  .items-strip__inner{
    height:100%;
    display:flex;
    align-items:center;
    gap:14px;
    padding:14px;
    overflow-x:auto;
    overflow-y:hidden;
    scroll-behavior:smooth;
  }

  .item-thumb{
    flex:0 0 auto;
    height: calc(25vh - 28px);
    max-height:180px;
    aspect-ratio: 1/1;
    border-radius:14px;
    overflow:hidden;
    background:#eef1f6;
    box-shadow:0 6px 18px rgba(0,0,0,.10);
    position:relative;
  }

  .item-thumb img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
  }

  .item-ph::after{
    content:"";
    position:absolute; inset:0;
    background: linear-gradient(135deg, rgba(0,0,0,.06), rgba(0,0,0,.02));
  }

  @media (max-width:700px){
    :root{
      --title-pad-x:40px;
      --logo-pad-left:16px;
      --nav-pad-right:16px;
    }
  }
  @media (max-width:560px){
    :root{
      --title-pad-x:22px;
      --logo-pad-left:10px;
      --nav-pad-right:10px;
    }
    .items-strip{ min-height:160px; }
    .item-thumb{ max-height:160px; }
  }
</style>

<section class="head-wrap" aria-label="Header-Bereich">
  <div class="hero" id="hero">
    <div class="hero__bg" id="heroBg"></div>
    <div class="hero__dim"></div>
  </div>

  <div class="items-strip">
    <div class="items-strip__inner" id="itemsRow"></div>
  </div>
</section>

<script>
(function(){
  const BG_MANIFEST    = "image/hintergrund/manifest.json";
  const ITEMS_MANIFEST = "Items/manifest.json";

  async function loadManifest(path){
    const res = await fetch(path, { cache: "no-store" });
    if(!res.ok) throw new Error(path + " -> " + res.status);
    const data = await res.json();
    if(!Array.isArray(data)) throw new Error(path + " ist kein Array");
    return data;
  }

  function pickRandom(arr){
    return arr[Math.floor(Math.random() * arr.length)];
  }

  function shuffle(arr){
    const a = arr.slice();
    for(let i=a.length-1;i>0;i--){
      const j = Math.floor(Math.random() * (i+1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  }

  function renderPlaceholders(count){
    const row = document.getElementById("itemsRow");
    if(!row) return;
    row.innerHTML = "";
    for(let i=0;i<count;i++){
      const d = document.createElement("div");
      d.className = "item-thumb item-ph";
      row.appendChild(d);
    }
  }

  async function setRandomHero(){
    const bgEl = document.getElementById("heroBg");
    if(!bgEl) return;

    try{
      const files = await loadManifest(BG_MANIFEST);
      if(!files.length) return;

      const file = pickRandom(files);
      // manifest enthält nur Dateinamen
      bgEl.style.backgroundImage = "url('image/hintergrund/" + file + "')";
    }catch(e){
      console.warn("Hintergrund-Manifest fehlt/fehlerhaft:", e);
    }
  }

  async function renderItemRow(){
    const row = document.getElementById("itemsRow");
    if(!row) return;

    try{
      const files = await loadManifest(ITEMS_MANIFEST);
      if(!files.length) { renderPlaceholders(10); return; }

      const picks = shuffle(files).slice(0, 12);
      row.innerHTML = "";

      for(const src of picks){
        const wrap = document.createElement("div");
        wrap.className = "item-thumb";

        const img = document.createElement("img");
        img.loading = "lazy";
        img.alt = "";
        img.src = src;

        // falls ein Bild fehlt -> placeholder statt kaputtem Icon
        img.onerror = () => {
          wrap.classList.add("item-ph");
          img.remove();
        };

        wrap.appendChild(img);
        row.appendChild(wrap);
      }
    }catch(e){
      console.warn("Items-Manifest fehlt/fehlerhaft:", e);
      renderPlaceholders(10);
    }
  }

  (async function(){
    await Promise.all([setRandomHero(), renderItemRow()]);
  })();
})();
</script>
