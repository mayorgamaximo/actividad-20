<?php
/**
 * EJERCICIO 2: Listado con paginación
 * Muestra viviendas con paginación y navegación anterior/siguiente
 */
require_once 'config.php';

$conexion = conectarDB();

// Obtener página actual
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$registrosPorPagina = REGISTROS_POR_PAGINA;
$comienzo = ($pagina - 1) * $registrosPorPagina;

// Consultar total de registros
$totalQuery = mysqli_query($conexion, "SELECT COUNT(*) as total FROM viviendas");
$totalRow = mysqli_fetch_array($totalQuery);
$totalRegistros = $totalRow['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta con límite
$registros = mysqli_query($conexion, 
    "SELECT * FROM viviendas LIMIT $comienzo, $registrosPorPagina")
    or die("Problemas en la consulta: " . mysqli_error($conexion));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado Paginado - Ejercicio 2</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 10px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .info-paginacion {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .vivienda-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .vivienda-card:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .vivienda-info-izq {
            flex: 1;
        }
        
        .vivienda-titulo {
            font-size: 1.3em;
            font-weight: bold;
            color: #f5576c;
            margin-bottom: 5px;
        }
        
        .vivienda-detalle {
            color: #666;
            font-size: 0.95em;
        }
        
        .vivienda-precio {
            font-size: 1.8em;
            font-weight: bold;
            color: #27ae60;
            text-align: right;
        }
        
        .paginacion {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .btn-pagina {
            padding: 12px 24px;
            background: #f5576c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-pagina:hover:not(.disabled) {
            background: #d43f54;
            transform: scale(1.05);
        }
        
        .btn-pagina.disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .pagina-actual {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            padding: 0 15px;
        }
        
        .numeros-pagina {
            display: flex;
            gap: 8px;
        }
        
        .numero-pagina {
            padding: 10px 15px;
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .numero-pagina:hover {
            background: #f5576c;
            color: white;
        }
        
        .numero-pagina.activo {
            background: #f5576c;
            color: white;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: white;
            color: #f5576c;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            background: #f5576c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📑 Listado Paginado de Viviendas</h1>
        <div class="info-paginacion">
            Mostrando página <?php echo $pagina; ?> de <?php echo $totalPaginas; ?> 
            (Total: <?php echo $totalRegistros; ?> viviendas)
        </div>
        
        <?php while ($reg = mysqli_fetch_array($registros)): ?>
            <div class="vivienda-card">
                <div class="vivienda-info-izq">
                    <div class="vivienda-titulo">
                        <?php echo $reg['tipo']; ?> #<?php echo $reg['id']; ?>
                    </div>
                    <div class="vivienda-detalle">
                        📍 <?php echo $reg['zona']; ?> - 
                        <?php echo $reg['direccion']; ?> - 
                        🛏 <?php echo $reg['dormitorios']; ?> dorm. - 
                        📐 <?php echo $reg['tamano']; ?> m²
                    </div>
                </div>
                <div class="vivienda-precio">
                    $<?php echo number_format($reg['precio'], 0); ?>
                </div>
            </div>
        <?php endwhile; ?>
        
        <div class="paginacion">
            <?php if ($pagina > 1): ?>
                <a href="?pagina=<?php echo ($pagina - 1); ?>" class="btn-pagina">
                    ← Anterior
                </a>
            <?php else: ?>
                <span class="btn-pagina disabled">← Anterior</span>
            <?php endif; ?>
            
            <div class="numeros-pagina">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <span class="numero-pagina activo"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?pagina=<?php echo $i; ?>" class="numero-pagina">
                            <?php echo $i; ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            
            <?php if ($pagina < $totalPaginas): ?>
                <a href="?pagina=<?php echo ($pagina + 1); ?>" class="btn-pagina">
                    Siguiente →
                </a>
            <?php else: ?>
                <span class="btn-pagina disabled">Siguiente →</span>
            <?php endif; ?>
        </div>
        
        <a href="index.php" class="back-link">← Volver al inicio</a>
    </div>
</body>
</html>

<?php
cerrarDB($conexion);
?>