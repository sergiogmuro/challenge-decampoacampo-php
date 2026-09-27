<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error <?php echo $code; ?></title>
</head>
<body>
<h1>¡Algo salió mal! (Código <?php echo $code; ?>)</h1>
<p><?php echo htmlspecialchars($message); ?></p>
<pre><?php $trace ? print_r(traceFormatter($trace)) : ''; ?></pre>
<pre><?php $trace ? var_dump($trace) : ''; ?></pre>
</body>
</html>
