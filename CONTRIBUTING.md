# Guía de Contribución - ClubesPadel

¡Gracias por tu interés en contribuir a ClubesPadel! 🎾

## Cómo Contribuir

### Reportar Bugs

Si encuentras un bug, por favor abre un issue con:
- Descripción clara del problema
- Pasos para reproducir
- Comportamiento esperado vs actual
- Capturas de pantalla (si aplica)
- Información del sistema (PHP, MySQL, navegador)

### Sugerir Mejoras

Para sugerir nuevas características:
- Abre un issue con la etiqueta "enhancement"
- Describe claramente la funcionalidad propuesta
- Explica por qué sería útil
- Si es posible, proporciona ejemplos

### Pull Requests

1. **Fork el repositorio**
2. **Crea una rama** para tu feature:
   ```bash
   git checkout -b feature/nueva-caracteristica
   ```
3. **Sigue las convenciones de código**:
   - PHP PSR-12 para estilo de código
   - Nombres descriptivos para variables y funciones
   - Comentarios donde sea necesario
   - Mantén la consistencia con el código existente

4. **Commits claros**:
   ```
   git commit -m "Add: Nueva característica X"
   git commit -m "Fix: Corrige error en Y"
   git commit -m "Update: Mejora en Z"
   ```

5. **Push y crea Pull Request**:
   ```bash
   git push origin feature/nueva-caracteristica
   ```

### Convenciones de Código

#### PHP
```php
<?php
namespace Controllers;

use Core\Controller;

class ExampleController extends Controller {
    
    public function index() {
        // Tu código aquí
    }
    
    private function helperMethod() {
        // Métodos privados en camelCase
    }
}
```

#### SQL
- Usa prepared statements siempre
- Nombres de tablas en plural
- Nombres de columnas en snake_case

#### HTML/Views
- Indentación de 4 espacios
- Cierra todas las etiquetas
- Usa Bootstrap 5 clases

### Testing

Antes de enviar un PR:
1. Prueba en navegadores principales
2. Verifica que no hay errores PHP
3. Confirma que las queries SQL funcionan
4. Valida el responsive design

### Áreas que Necesitan Ayuda

- [ ] Tests unitarios y de integración
- [ ] Documentación en inglés
- [ ] Mejoras de UI/UX
- [ ] Optimización de queries
- [ ] Integración de APIs de pago
- [ ] Sistema de caché

### Código de Conducta

- Se respetuoso y profesional
- Acepta críticas constructivas
- Enfócate en lo mejor para el proyecto
- Ayuda a otros contribuyentes

### Preguntas

Si tienes dudas sobre cómo contribuir:
- Abre un issue con la etiqueta "question"
- Revisa issues existentes
- Lee la documentación completa

## Licencia

Al contribuir, aceptas que tu código se distribuirá bajo la misma licencia del proyecto (MIT).

---

¡Gracias por hacer de ClubesPadel un mejor sistema! 🚀
