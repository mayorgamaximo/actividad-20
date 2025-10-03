<?php
/**
 * API para búsqueda AJAX
 * Retorna JSON con los resultados
 */
if (isset($_GET['api'])) {
    require_once 'config.php';
    
    header('Content-Type: application/json');
    
    $conexion = conectarDB();
    
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
    $zona = isset($_GET['zona']) ? $_GET['zona'] : '';
    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    
    $where = array();
    if (!empty($tipo)) {
        $where[] = "tipo = '" . mysqli_real_escape_string($conexion, $tipo) . "'";
    }
    if (!empty($zona)) {
        $where[] = "zona = '" . mysqli_real_escape_string($conexion, $zona) . "'";
    }
    
    $whereSQL = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
    
    $registrosPorPagina = 6;
    $comienzo = ($pagina - 1) * $registrosPorPagina;
    
    $totalQuery = mysqli_query($conexion, "SELECT COUNT(*) as total FROM viviendas $whereSQL");
    $totalRow = mysqli_fetch_array($totalQuery);
    $total = $totalRow['total'];
    
    $query = "SELECT * FROM viviendas $whereSQL LIMIT $comienzo, $registrosPorPagina";
    $registros = mysqli_query($conexion, $query);
    
    $viviendas = array();
    while ($reg = mysqli_fetch_assoc($registros)) {
        $viviendas[] = $reg;
    }
    
    cerrarDB($conexion);
    
    echo json_encode(array(
        'viviendas' => $viviendas,
        'total' => $total,
        'pagina' => $pagina,
        'totalPaginas' => ceil($total / $registrosPorPagina)
    ));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda AJAX con Fetch</title>
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
        
        .filtros-ajax {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .form-ajax {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }
        
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }
        
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-filtrar {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-filtrar:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .loading {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .grid-resultados {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }
        
        .card-tipo {
            font-size: 1.4em;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .card-precio {
            font-size: 2em;
            font-weight: bold;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-info {
            margin-bottom: 12px;
            color: #666;
            display: flex;
            align-items: center;
        }
        
        .card-info strong {
            margin-right: 8px;
            color: #333;
        }
        
        .extras-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }
        
        .extra-badge {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }
        
        .paginacion {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }
        
        .btn-pag {
            padding: 10px 20px;
            background: white;
            color: #667eea;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-pag:hover:not(:disabled) {
            background: #667eea;
            color: white;
        }
        
        .btn-pag:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-pag.activo {
            background: #667eea;
            color: white;
        }
        
        .info-resultados {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }
        
        .sin-resultados {
            background: white;
            padding: 60px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .sin-resultados h2 {
            color: #ff6b6b;
            margin-bottom: 10px;
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
        <h1>⚡ Búsqueda Dinámica con AJAX</h1>
        
        <div class="filtros-ajax">
            <form class="form-ajax" id="formFiltros">
                <div class="form-group">
                    <label>Tipo de Vivienda</label>
                    <select id="filtroTipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="Casa">Casa</option>
                        <option value="Departamento">Departamento</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Zona</label>
                    <select id="filtroZona" name="zona">
                        <option value="">Todas</option>
                        <option value="Norte">Norte</option>
                        <option value="Sur">Sur</option>
                        <option value="Este">Este</option>
                        <option value="Oeste">Oeste</option>
                        <option value="Centro">Centro</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-filtrar">🔍 Buscar</button>
            </form>
        </div>
        
        <div id="infoResultados"></div>
        <div id="resultados"></div>
        <div id="paginacion"></div>
        
        <a href="index.php" class="back-link">← Volver al inicio</a>
    </div>
    
    <script>
        let paginaActual = 1;
        let totalPaginas = 1;
        
        // Cargar resultados al iniciar
        document.addEventListener('DOMContentLoaded', () => {
            cargarViviendas();
        });
        
        // Manejar envío del formulario
        document.getElementById('formFiltros').addEventListener('submit', (e) => {
            e.preventDefault();
            paginaActual = 1;
            cargarViviendas();
        });
        
        // Función principal para cargar viviendas
        async function cargarViviendas() {
            const tipo = document.getElementById('filtroTipo').value;
            const zona = document.getElementById('filtroZona').value;
            
            mostrarLoading();
            
            try {
                const url = `ajax.php?api=1&tipo=${tipo}&zona=${zona}&pagina=${paginaActual}`;
                const response = await fetch(url);
                const data = await response.json();
                
                mostrarResultados(data);
            } catch (error) {
                document.getElementById('resultados').innerHTML = `
                    <div class="sin-resultados">
                        <h2>❌ Error al cargar datos</h2>
                        <p>${error.message}</p>
                    </div>
                `;
            }
        }
        
        // Mostrar indicador de carga
        function mostrarLoading() {
            document.getElementById('resultados').innerHTML = `
                <div class="loading">
                    <div class="loading-spinner"></div>
                    <p>Cargando resultados...</p>
                </div>
            `;
            document.getElementById('paginacion').innerHTML = '';
            document.getElementById('infoResultados').innerHTML = '';
        }
        
        // Mostrar resultados
        function mostrarResultados(data) {
            totalPaginas = data.totalPaginas;
            
            // Información de resultados
            document.getElementById('infoResultados').innerHTML = `
                <div class="info-resultados">
                    📊 Se encontraron ${data.total} vivienda${data.total !== 1 ? 's' : ''} - 
                    Página ${data.pagina} de ${data.totalPaginas}
                </div>
            `;
            
            if (data.viviendas.length === 0) {
                document.getElementById('resultados').innerHTML = `
                    <div class="sin-resultados">
                        <h2>😕 No se encontraron resultados</h2>
                        <p>Intenta ajustar los filtros de búsqueda</p>
                    </div>
                `;
                return;
            }
            
            // Crear tarjetas de viviendas
            let html = '<div class="grid-resultados">';
            data.viviendas.forEach(vivienda => {
                const extras = vivienda.extras ? vivienda.extras.split(',') : [];
                
                html += `
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tipo">${vivienda.tipo} #${vivienda.id}</div>
                            <div class="card-precio">$${formatearPrecio(vivienda.precio)}</div>
                        </div>
                        <div class="card-body">
                            <div class="card-info">
                                <strong>📍 Zona:</strong> ${vivienda.zona}
                            </div>
                            <div class="card-info">
                                <strong>📮 Dirección:</strong> ${vivienda.direccion}
                            </div>
                            <div class="card-info">
                                <strong>🛏 Dormitorios:</strong> ${vivienda.dormitorios}
                            </div>
                            <div class="card-info">
                                <strong>📐 Tamaño:</strong> ${vivienda.tamano} m²
                            </div>
                            ${extras.length > 0 ? `
                                <div class="extras-container">
                                    ${extras.map(extra => `<span class="extra-badge">${extra.trim()}</span>`).join('')}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            
            document.getElementById('resultados').innerHTML = html;
            
            // Crear paginación
            crearPaginacion(data.pagina, data.totalPaginas);
        }
        
        // Crear controles de paginación
        function crearPaginacion(pagina, total) {
            if (total <= 1) {
                document.getElementById('paginacion').innerHTML = '';
                return;
            }
            
            let html = '<div class="paginacion">';
            
            // Botón anterior
            html += `<button class="btn-pag" ${pagina === 1 ? 'disabled' : ''} 
                     onclick="cambiarPagina(${pagina - 1})">← Anterior</button>`;
            
            // Números de página
            for (let i = 1; i <= Math.min(total, 5); i++) {
                html += `<button class="btn-pag ${i === pagina ? 'activo' : ''}" 
                         onclick="cambiarPagina(${i})">${i}</button>`;
            }
            
            // Botón siguiente
            html += `<button class="btn-pag" ${pagina === total ? 'disabled' : ''} 
                     onclick="cambiarPagina(${pagina + 1})">Siguiente →</button>`;
            
            html += '</div>';
            
            document.getElementById('paginacion').innerHTML = html;
        }
        
        // Cambiar de página
        function cambiarPagina(nuevaPagina) {
            if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;
            paginaActual = nuevaPagina;
            cargarViviendas();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Formatear precio
        function formatearPrecio(precio) {
            return new Intl.NumberFormat('es-AR').format(precio);
        }
    </script>
</body>
</html>