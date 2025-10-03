<?php
/**
 * EJERCICIO 1: Listado de viviendas
 * Muestra todas las viviendas de la base de datos
 */
require_once 'config.php';

$conexion = conectarDB();

// Consulta para obtener todas las viviendas
$registros = mysqli_query($conexion, "SELECT * FROM viviendas")
    or die("Problemas en la consulta: " . mysqli_error($conexion));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Viviendas - Ejercicio 1</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .vivienda-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .vivienda-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.2);
        }
        
        .vivienda-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #667eea;
        }
        
        .vivienda-tipo {
            font-size: 1.5em;
            font-weight: bold;
            color: #667eea;
        }
        
        .vivienda-precio {
            font-size: 1.8em;
            font-weight: bold;
            color: #27ae60;
        }
        
        .vivienda-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .info-item {
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 0.9em;
        }
        
        .info-value {
            color: #333;
            font-size: 1.1em;
        }
        
        .extras {
            margin-top: 15px;
            padding: 10px;
            background: #e3f2fd;
            border-radius: 6px;
        }
        
        .extras-tag {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            margin-right: 8px;
            margin-top: 5px;
            font-size: 0.9em;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            background: #667eea;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏠 Listado Completo de Viviendas</h1>
        
        <?php while ($reg = mysqli_fetch_array($registros)): ?>
            <div class="vivienda-card">
                <div class="vivienda-header">
                    <span class="vivienda-tipo">
                        <?php echo $reg['tipo']; ?> #<?php echo $reg['id']; ?>
                    </span>
                    <span class="vivienda-precio">
                        $<?php echo number_format($reg['precio'], 2); ?>
                    </span>
                </div>
                
                <div class="vivienda-info">
                    <div class="info-item">
                        <div class="info-label">📍 Zona:</div>
                        <div class="info-value"><?php echo $reg['zona']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📮 Dirección:</div>
                        <div class="info-value"><?php echo $reg['direccion']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">🛏 Dormitorios:</div>
                        <div class="info-value"><?php echo $reg['dormitorios']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">📐 Tamaño:</div>
                        <div class="info-value"><?php echo $reg['tamano']; ?> m²</div>
                    </div>
                </div>
                
                <?php if (!empty($reg['extras'])): ?>
                    <div class="extras">
                        <strong>✨ Extras:</strong>
                        <?php 
                        $extras = explode(',', $reg['extras']);
                        foreach ($extras as $extra): 
                        ?>
                            <span class="extras-tag"><?php echo trim($extra); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        
        <a href="index.php" class="back-link">← Volver al inicio</a>
    </div>
</body>
</html>

<?php
cerrarDB($conexion);
?>