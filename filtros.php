<?php
/**
 * SISTEMA AVANZADO: Filtros dinámicos y búsqueda
 * Incluye filtros por tipo, zona, precio, dormitorios y extras
 */
require_once 'config.php';

$conexion = conectarDB();

// Obtener parámetros de filtro
$filtroTipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filtroZona = isset($_GET['zona']) ? $_GET['zona'] : '';
$filtroPrecioMin = isset($_GET['precio_min']) ? (float)$_GET['precio_min'] : 0;
$filtroPrecioMax = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 999999999;
$filtroDormitorios = isset($_GET['dormitorios']) ? (int)$_GET['dormitorios'] : 0;
$filtroExtras = isset($_GET['extras']) ? $_GET['extras'] : '';
$ordenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : 'id';

// Construir consulta con filtros
$where = array();
if (!empty($filtroTipo)) {
    $where[] = "tipo = '" . mysqli_real_escape_string($conexion, $filtroTipo) . "'";
}
if (!empty($filtroZona)) {
    $where[] = "zona = '" . mysqli_real_escape_string($conexion, $filtroZona) . "'";
}
if ($filtroPrecioMin > 0 || $filtroPrecioMax < 999999999) {
    $where[] = "precio BETWEEN $filtroPrecioMin AND $filtroPrecioMax";
}
if ($filtroDormitorios > 0) {
    $where[] = "dormitorios = $filtroDormitorios";
}
if (!empty($filtroExtras)) {
    $where[] = "FIND_IN_SET('" . mysqli_real_escape_string($conexion, $filtroExtras) . "', extras) > 0";
}

$whereSQL = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

// Obtener opciones para los filtros
$tipos = mysqli_query($conexion, "SELECT DISTINCT tipo FROM viviendas ORDER BY tipo");
$zonas = mysqli_query($conexion, "SELECT DISTINCT zona FROM viviendas ORDER BY zona");

// Paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

$registrosPorPagina = REGISTROS_POR_PAGINA;
$comienzo = ($pagina - 1) * $registrosPorPagina;

// Consultar total de registros con filtros
$totalQuery = mysqli_query($conexion, "SELECT COUNT(*) as total FROM viviendas $whereSQL");
$totalRow = mysqli_fetch_array($totalQuery);
$totalRegistros = $totalRow['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal con filtros, orden y límite
$query = "SELECT * FROM viviendas $whereSQL ORDER BY $ordenar LIMIT $comienzo, $registrosPorPagina";
$registros = mysqli_query($conexion, $query)
    or die("Problemas en la consulta: " . mysqli_error($conexion));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda Avanzada - Filtros Dinámicos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .panel-filtros {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .filtros-titulo {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .form-filtros {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        
        .form-group select,
        .form-group input {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        
        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #43e97b;
        }
        
        .botones-filtro {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-buscar {
            background: #43e97b;
            color: white;
            flex: 1;
        }
        
        .btn-buscar:hover {
            background: #2ed168;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-limpiar {
            background: #ff6b6b;
            color: white;
        }
        
        .btn-limpiar:hover {
            background: #ee5a52;
        }
        
        .resultados-info {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .resultados-texto {
            font-weight: bold;
            color: #333;
        }
        
        .grid-viviendas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card-vivienda {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .card-vivienda:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        
        .card-header {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            padding: 15px;
        }
        
        .card-tipo {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .card-precio {
            font-size: 1.8em;
            font-weight: bold;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-detalle {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #666;
        }
        
        .card-detalle strong {
            margin-right: 8px;
            color: #333;
        }
        
        .card-extras {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }
        
        .tag-extra {
            background: #43e97b;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }
        
        .paginacion {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-pag {
            padding: 10px 20px;
            background: white;
            color: #43e97b;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-pag:hover:not(.disabled) {
            background: #43e97b;
            color: white;
        }
        
        .btn-pag.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-pag.activo {
            background: #43e97b;
            color: white;
        }
        
        .sin-resultados {
            background: white;
            border-radius: 12px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .sin-resultados h2 {
            color: #ff6b6b;
            font-size: 2em;
            margin-bottom: 15px;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: white;
            color: #43e97b;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            background: #43e97b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Búsqueda Avanzada de Viviendas</h1>
        
        <div class="panel-filtros">
            <div class="filtros-titulo">⚙️ Filtros de Búsqueda</div>
            <form method="GET" class="form-filtros">
                <div class="form-group">
                    <label>Tipo de Vivienda</label>
                    <select name="tipo">
                        <option value="">Todos</option>
                        <?php while ($t = mysqli_fetch_array($tipos)): ?>
                            <option value="<?php echo $t['tipo']; ?>" 
                                <?php echo ($filtroTipo == $t['tipo']) ? 'selected' : ''; ?>>
                                <?php echo $t['tipo']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Zona</label>
                    <select name="zona">
                        <option value="">Todas</option>
                        <?php while ($z = mysqli_fetch_array($zonas)): ?>
                            <option value="<?php echo $z['zona']; ?>"
                                <?php echo ($filtroZona == $z['zona']) ? 'selected' : ''; ?>>
                                <?php echo $z['zona']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Precio Mínimo</label>
                    <input type="number" name="precio_min" 
                           value="<?php echo ($filtroPrecioMin > 0) ? $filtroPrecioMin : ''; ?>" 
                           placeholder="0">
                </div>
                
                <div class="form-group">
                    <label>Precio Máximo</label>
                    <input type="number" name="precio_max" 
                           value="<?php echo ($filtroPrecioMax < 999999999) ? $filtroPrecioMax : ''; ?>" 
                           placeholder="999999">
                </div>
                
                <div class="form-group">
                    <label>Dormitorios</label>
                    <select name="dormitorios">
                        <option value="0">Cualquiera</option>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo $i; ?>"
                                <?php echo ($filtroDormitorios == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?> dormitorio<?php echo ($i > 1) ? 's' : ''; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Extras</label>
                    <select name="extras">
                        <option value="">Cualquiera</option>
                        <option value="Piscina" <?php echo ($filtroExtras == 'Piscina') ? 'selected' : ''; ?>>Piscina</option>
                        <option value="Jardín" <?php echo ($filtroExtras == 'Jardín') ? 'selected' : ''; ?>>Jardín</option>
                        <option value="Garage" <?php echo ($filtroExtras == 'Garage') ? 'selected' : ''; ?>>Garage</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Ordenar por</label>
                    <select name="ordenar">
                        <option value="id" <?php echo ($ordenar == 'id') ? 'selected' : ''; ?>>ID</option>
                        <option value="precio ASC" <?php echo ($ordenar == 'precio ASC') ? 'selected' : ''; ?>>Precio (menor a mayor)</option>
                        <option value="precio DESC" <?php echo ($ordenar == 'precio DESC') ? 'selected' : ''; ?>>Precio (mayor a menor)</option>
                        <option value="dormitorios DESC" <?php echo ($ordenar == 'dormitorios DESC') ? 'selected' : ''; ?>>Más dormitorios</option>
                        <option value="tamano DESC" <?php echo ($ordenar == 'tamano DESC') ? 'selected' : ''; ?>>Más grande</option>
                    </select>
                </div>
                
                <div class="botones-filtro" style="grid-column: 1 / -1;">
                    <button type="submit" class="btn btn-buscar">🔍 Buscar</button>
                    <a href="filtros.php" class="btn btn-limpiar">🔄 Limpiar Filtros</a>
                </div>
            </form>
        </div>
        
        <?php if ($totalRegistros > 0): ?>
            <div class="resultados-info">
                <span class="resultados-texto">
                    📊 Se encontraron <?php echo $totalRegistros; ?> vivienda<?php echo ($totalRegistros != 1) ? 's' : ''; ?>
                </span>
                <span>Página <?php echo $pagina; ?> de <?php echo $totalPaginas; ?></span>
            </div>
            
            <div class="grid-viviendas">
                <?php while ($reg = mysqli_fetch_array($registros)): ?>
                    <div class="card-vivienda">
                        <div class="card-header">
                            <div class="card-tipo"><?php echo $reg['tipo']; ?> #<?php echo $reg['id']; ?></div>
                            <div class="card-precio">$<?php echo number_format($reg['precio'], 0); ?></div>
                        </div>
                        <div class="card-body">
                            <div class="card-detalle">
                                <strong>📍 Zona:</strong> <?php echo $reg['zona']; ?>
                            </div>
                            <div class="card-detalle">
                                <strong>📮 Dirección:</strong> <?php echo $reg['direccion']; ?>
                            </div>
                            <div class="card-detalle">
                                <strong>🛏 Dormitorios:</strong> <?php echo $reg['dormitorios']; ?>
                            </div>
                            <div class="card-detalle">
                                <strong>📐 Tamaño:</strong> <?php echo $reg['tamano']; ?> m²
                            </div>
                            
                            <?php if (!empty($reg['extras'])): ?>
                                <div class="card-extras">
                                    <?php 
                                    $extras = explode(',', $reg['extras']);
                                    foreach ($extras as $extra): 
                                    ?>
                                        <span class="tag-extra"><?php echo trim($extra); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <?php if ($totalPaginas > 1): ?>
                <div class="paginacion">
                    <?php if ($pagina > 1): ?>
                        <a href="?pagina=<?php echo ($pagina - 1); ?>&<?php echo http_build_query(array_diff_key($_GET, ['pagina' => ''])); ?>" 
                           class="btn-pag">← Anterior</a>
                    <?php else: ?>
                        <span class="btn-pag disabled">← Anterior</span>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= min($totalPaginas, 5); $i++): ?>
                        <a href="?pagina=<?php echo $i; ?>&<?php echo http_build_query(array_diff_key($_GET, ['pagina' => ''])); ?>" 
                           class="btn-pag <?php echo ($i == $pagina) ? 'activo' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="?pagina=<?php echo ($pagina + 1); ?>&<?php echo http_build_query(array_diff_key($_GET, ['pagina' => ''])); ?>" 
                           class="btn-pag">Siguiente →</a>
                    <?php else: ?>
                        <span class="btn-pag disabled">Siguiente →</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="sin-resultados">
                <h2>😕 No se encontraron resultados</h2>
                <p>Intenta ajustar los filtros de búsqueda</p>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-link">← Volver al inicio</a>
    </div>
</body>
</html>

<?php
cerrarDB($conexion);
?>