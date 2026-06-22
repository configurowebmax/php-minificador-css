<?php
/**
 * Minificador de CSS
 */
header('Content-Type: text/html; charset=utf-8');

$cssOriginal = $cssMin = '';
$ahorro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cssOriginal = $_POST['css'] ?? '';
    if (trim($cssOriginal) !== '') {
        $css = $cssOriginal;
        // 1) Quitar comentarios
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // 2) Espacios alrededor de caracteres
        $css = preg_replace('/\s*([{}:;,>~+])\s*/', '$1', $css);
        // 3) Quitar espacios al inicio/final
        $css = trim($css);
        // 4) Múltiples espacios a uno (fuera de strings)
        $css = preg_replace('/\s+/', ' ', $css);
        // 5) Quitar último ; antes de }
        $css = str_replace(';}', '}', $css);
        // 6) Ceros innecesarios (0.5 -> .5)
        $css = preg_replace('/(^|[^0-9])0\.([0-9]+)/', '$1.$2', $css);

        $cssMin = $css;
        $antes = strlen($cssOriginal);
        $despues = strlen($cssMin);
        $ahorro = [
            'antes'   => $antes,
            'despues' => $despues,
            'bytes'   => $antes - $despues,
            'porcentaje' => $antes > 0 ? round((1 - $despues / $antes) * 100, 1) : 0,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Minificador de CSS Online | ConfiguroWeb</title>
<meta name="description" content="Minifica tu CSS para acelerar tu web. Elimina espacios, comentarios y optimiza valores. Muestra el ahorro en bytes. Gratis en ConfiguroWeb.">
<meta name="keywords" content="minificador css, css minifier, optimizar css, comprimir css, performance web">
<link rel="canonical" href="https://demoscweb.com/github/php-minificador-css/">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"WebApplication","name":"Minificador de CSS","applicationCategory":"DeveloperApplication","operatingSystem":"Any","offers":{"@type":"Offer","price":"0","priceCurrency":"USD"},"author":{"@type":"Person","name":"ConfiguroWeb","url":"https://configuroweb.com"}}
</script>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
  <h1>🎨 Minificador de CSS</h1>
  <p class="subtitle">Optimiza tu CSS y acelera tu web</p>
</header>
<main>
  <form method="POST">
    <label for="css">Pega tu CSS</label>
    <textarea name="css" id="css" rows="10" placeholder="body {
  margin: 0;
  padding: 0;
  /* Comentario */
  font-family: Arial, sans-serif;
}" required><?php echo htmlspecialchars($cssOriginal); ?></textarea>
    <button type="submit" class="btn-primary">🎨 Minificar</button>
  </form>

  <?php if ($ahorro !== null): ?>
  <div class="resultados">
    <h2>CSS Minificado</h2>
    <div class="tarjeta-destacada">
      <span class="etiqueta">Ahorro</span>
      <span class="valor-grande"><?php echo $ahorro['porcentaje']; ?>%</span>
    </div>
    <div class="grid-3">
      <div class="tarjeta-sm"><span class="etiqueta">Tamaño original</span><span class="valor-sm"><?php echo $ahorro['antes']; ?> bytes</span></div>
      <div class="tarjeta-sm"><span class="etiqueta">Tamaño final</span><span class="valor-sm"><?php echo $ahorro['despues']; ?> bytes</span></div>
      <div class="tarjeta-sm"><span class="etiqueta">Bytes ahorrados</span><span class="valor-sm pos"><?php echo $ahorro['bytes']; ?></span></div>
    </div>

    <label for="cssMin" style="margin-top:1rem;display:block;font-weight:600">CSS Resultante:</label>
    <textarea id="cssMin" rows="6" readonly style="width:100%;font-family:monospace;font-size:.85rem;padding:.8rem;border-radius:8px;border:1px solid #ccc;word-break:break-all"><?php echo htmlspecialchars($cssMin); ?></textarea>

    <p class="interpretacion">
      🎨 Tu CSS se redujo de <strong><?php echo $ahorro['antes']; ?></strong> a <strong><?php echo $ahorro['despues']; ?></strong> bytes
      (ahorro del <strong><?php echo $ahorro['porcentaje']; ?>%</strong>).
      Esto acelera la carga de tu web y mejora tu puntaje en Google PageSpeed.
    </p>
  </div>
  <?php endif; ?>

  <section class="info">
    <h2>¿Qué hace el minificador?</h2>
    <ul style="line-height:1.8">
      <li>🗑️ Elimina <strong>comentarios</strong> <code>/* ... */</code></li>
      <li>🗑️ Elimina <strong>espacios</strong>, saltos de línea y tabuladores innecesarios</li>
      <li>🗑️ Quita el <strong>último punto y coma</strong> antes de <code>}</code></li>
      <li>🔧 Simplifica ceros: <code>0.5rem</code> → <code>.5rem</code></li>
    </ul>
    <p>⚠️ Guarda siempre una copia del CSS original. El código minificado es difícil de leer y editar.</p>
  </section>
</main>
<footer>
  <p>Desarrollado por <a href="https://configuroweb.com" target="_blank">ConfiguroWeb</a> ·
     <a href="https://appscweb.com/citas/" target="_blank">Sistema de Citas</a> ·
     <a href="https://appscweb.com/negocios/" target="_blank">Gestión de Negocios</a></p>
  <p>&copy; <?php echo date('Y'); ?> ConfiguroWeb</p>
</footer>
<script src="assets/script.js"></script>
</body>
</html>