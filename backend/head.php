<?php
// ===============================
// KONFIG
// ===============================
$bgDir    = __DIR__ . '/../image/hintergrund';
$itemsDir = __DIR__ . '/../Items';
$bgWeb    = 'image/hintergrund';
$itemsWeb = 'Items';

$allowedExt = ['jpg','jpeg','png','webp'];

// ===============================
// HILFSFUNKTIONEN
// ===============================
function getImages($dir, $exts){
  if(!is_dir($dir)) return [];
  $out = [];
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
  foreach($it as $f){
    if($f->isFile()){
      $e = strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION));
      if(in_array($e, $exts)){
        $out[] = $f->getPathname();
      }
    }
  }
  return $out;
}

function pickRandom($arr, $count = 1){
  if(empty($arr)) return [];
  shuffle($arr);
  return array_slice($arr, 0, $count);
}

// ===============================
// DATEN SAMMELN
// ===============================
$bgImages    = getImages($bgDir, $allowedExt);
$itemImages = getImages($itemsDir, $allowedExt);

$heroBg  = pickRandom($bgImages, 1);
$items   = pickRandom($itemImages, 12);
?>

<style>
:root{
  /* MUSS ZU titel.html PASSEN */
  --title-pad-x: 72px;
  --logo-pad-left: 32px;
  --nav-pad-right: 32px;

  --edge-left: calc(var(--title-pad-x) + var(--logo-pad-left));
  --edge-right: calc(var(--title-pad-x) + var(--nav-pad-right));

  --radius: 18px;
  --shadow-soft: 0 12px 34px rgba(0,0,0,.14);
}

/* Wrapper */
.head-wrap{
  padding-top: var(--edge-left);
  padding-left: var(--edge-left);
  padding-right: var(--edge-right);
  padding-bottom: var(--edge-left);
  width:100%;
}

/* HERO */
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
}

.hero__dim{
  position:absolute;
  inset:0;
  background: rgba(0,0,0,.35);
}

/* ITEM-STRIP */
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
}

.item-thumb{
  flex:0 0 auto;
  height: calc(25vh - 28px);
  max-height:180px;
  aspect-ratio:1/1;
  border-radius:14px;
  overflow:hidden;
  background:#eef1f6;
  box-shadow:0 6px 18px rgba(0,0,0,.10);
}

.item-thumb img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}

/* Mobile */
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
}
</style>

<section class="head-wrap" aria-label="Header">
  <div class="hero">
    <?php if(!empty($heroBg)): 
      $rel = str_replace(realpath(__DIR__.'/..').'/', '', realpath($heroBg[0]));
    ?>
      <div class="hero__bg" style="background-image:url('<?= $rel ?>')"></div>
    <?php endif; ?>
    <div class="hero__dim"></div>
  </div>

  <div class="items-strip">
    <div class="items-strip__inner">
      <?php if(!empty($items)): ?>
        <?php foreach($items as $img):
          $rel = str_replace(realpath(__DIR__.'/..').'/', '', realpath($img));
        ?>
          <div class="item-thumb">
            <img src="<?= $rel ?>" alt="" loading="lazy">
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php for($i=0;$i<10;$i++): ?>
          <div class="item-thumb"></div>
        <?php endfor; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
