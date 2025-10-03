<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Viviendas - Inicio</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .header h1 {
            color: white;
            font-size: 3em;
            margin-bottom: 15px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        }
        
        .header p {
            color: white;
            font-size: 1.3em;
            opacity: 0.95;
        }
        
        .grid-opciones {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .opcion-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .opcion-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .opcion-card .icono {
            font-size: 4em;
            margin-bottom: 20px;
        }
        
        .opcion-card h2 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        
        .opcion-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .opcion-card .badge {
            display: inline-block;
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }
        
        .ejercicio-1 .icono { color: #667eea; }
        .ejercicio-2 .icono { color: #f5576c; }
        .ejercicio-3 .icono { color: #43e97b; }
        .ejercicio-4 .icono { color: #fa8231; }
        
        .info-footer {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .info-footer h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        
        .info-footer p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 10px;
        }
        
        .info-footer .creditos {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            font-weight: bold;
            color: #333;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .opcion-card {
            animation: fadeIn 0.6s ease forwards;
            opacity: 0;
        }
        
        .opcion-card:nth-child(1) { animation-delay: 0.1s; }
        .opcion-card:nth-child(2) { animation-delay: 0.2s; }
        .opcion-card:nth-child(3) { animation-delay: 0.3s; }
        .opcion-card:nth-child(4) { animation-delay: 0.4s; }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .header p {
                font-size: 1em;
            }
            
            .grid-opciones {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 Sistema de Gestión de Viviendas</h1>
            <p>ACTIVIDAD 20: Implementación de Listados y Filtrados Dinámicos</p>
        </div>
        
        <div class="grid-opciones">
            <a href="ejercicio1.php" class="opcion-card ejercicio-1">
                <div class="icono">📋</div>
                <h2>Ejercicio 1</h2>
                <p>Listado completo de todas las viviendas con información detallada en formato de tarjetas elegantes.</p>
                <span class="badge">Listado Simple</span>
            </a>
            
            <a href="ejercicio2.php" class="opcion-card ejercicio-2">
                <div class="icono">📑</div>
                <h2>Ejercicio 2</h2>
                <p>Listado con sistema de paginación completo. Navegación anterior/siguiente y números de página interactivos.</p>
                <span class="badge">Con Paginación</span>
            </a>
            
            <a href="filtros.php" class="opcion-card ejercicio-3">
                <div class="icono">🔍</div>
                <h2>Búsqueda Avanzada</h2>
                <p>Sistema completo de filtros dinámicos por tipo, zona, precio, dormitorios y extras con múltiples combinaciones.</p>
                <span class="badge">Filtros Avanzados</span>
            </a>
            
            <a href="ajax.php" class="opcion-card ejercicio-4">
                <div class="icono">⚡</div>
                <h2>Versión AJAX</h2>
                <p>Búsqueda dinámica con JavaScript Fetch API. Carga de resultados instantánea sin recargar la página.</p>
                <span class="badge">Desafío Extra</span>
            </a>
        </div>
        
        <div class="info-footer">
            <h3>📚 Sobre el Proyecto</h3>
            <p><strong>Materia:</strong> Proyecto de Implementación de Sitios web Dinámicos</p>
            <p><strong>Institución:</strong> EEST N.º 1 "Eduardo Ader" - Vicente López</p>
            <p><strong>Año:</strong> 7° 2° B - 2025</p>
            <p><strong>Tecnologías:</strong> PHP, MySQL, JavaScript, HTML5, CSS3, AJAX</p>
            <p><strong>Características:</strong> Listados dinámicos, Paginación, Filtros, API REST</p>
            <div class="creditos">
                💻 Actividad 20 - Listados y Filtrados Dinámicos | Desarrollo Web 2025 🚀
            </div>
        </div>
    </div>
</body>
</html>