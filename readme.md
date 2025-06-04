# Dashboard de Monitorización para Servidores VPS

Este proyecto es un **dashboard de monitorización** que muestra en tiempo real las métricas de rendimiento de un servidor VPS. Incluye gráficos interactivos y estadísticas detalladas sobre CPU, RAM, almacenamiento, tráfico de red, procesos, particiones, servicios y certificados SSL.

---

## 🚀 **Características**

- **Gráficos en tiempo real** de CPU, RAM, Disco y Red utilizando `Chart.js`.
- **Métricas detalladas**, incluyendo:
  - Carga del sistema (1, 5, 15 minutos)
  - Uptime
  - Procesos principales con consumo de CPU y memoria
  - Particiones de almacenamiento
  - Tráfico de red (RX/TX)
  - Servicios del sistema
  - Certificados SSL activos
  - Logs del sistema
- **Protección con autenticación HTTP básica**.

---

## 📋 **Requisitos**

1. **Servidor web:** Apache o Nginx.
2. **PHP:** Versión 7.4 o superior.
3. **Extensiones de PHP necesarias:** 
   - `shell_exec` habilitado para la ejecución de comandos del sistema.

---

## 📦 **Instalación**

1. **Clona este repositorio** en el directorio de tu servidor web:
   ```bash
   git clone https://github.com/joanmedina/monitor-vps.git
   ```

2. **Copia el contenido del proyecto** al directorio donde estará alojado el dashboard.

3. Ejecuta `composer install` para descargar las dependencias.

4. **Crea un archivo `.env`** con las credenciales de acceso siguiendo el ejemplo de `.env.example`.

4. **Define los servicios a vigilar** editando `config.php`.

---

## 🔐 **Protección del dashboard**

Este proyecto utiliza **autenticación HTTP básica** para proteger las métricas del servidor. Las credenciales se establecen mediante variables de entorno definidas en el archivo `.env`.

### **Ejemplo del archivo `.env`:**
```env
USUARIO_PERMITIDO=admin
PASSWORD_PERMITIDA=<hash de la contraseña>
```

Para generar un hash compatible puedes ejecutar:
```bash
php generate_hash.php
```

O bien utilizar directamente PHP desde la consola:
```bash
php -r "echo password_hash('tu_password', PASSWORD_DEFAULT);"
```

## ⚙️ **Personalizar servicios a vigilar**

Edita `config.php` para indicar los servicios que deseas monitorizar.

```php
<?php
return [
    'services' => ['apache2', 'mysql']
];
```

---

## 🛠️ **Estructura del Proyecto**

```
monitor-vps/
│
├── assets/
│   ├── css/
│   │   └── styles.css           # Hoja de estilos personalizada
│   ├── js/
│   │   └── app.js               # Lógica de actualización de métricas
│
├── metrics/
│   ├── cpu.php                  # Métricas de CPU
│   ├── memory.php               # Métricas de RAM
│   ├── disk.php                 # Métricas de disco
│   └── ...                      # Otros scripts de métricas
│
├── index.php                    # Página principal del dashboard
├── monitor_data.php             # API de datos en formato JSON
├── auth.php                     # Protección con autenticación HTTP
├── README.md                    # Documentación del proyecto
```

---

## 🖼️ **Capturas de Pantalla**

### **Dashboard en ejecución**

![Captura de pantalla del dashboard](https://github.com/joanmedina/monitor-vps/blob/main/assets/dashboard.png)

---

## 📚 **Comandos del sistema utilizados**

El proyecto utiliza varios comandos para obtener métricas del sistema. Algunos ejemplos:

- **Carga del sistema:** `uptime`
- **Procesos activos:** `ps -eo pid,comm,%cpu,%mem --sort=-%cpu | head -n 10`
- **Espacio en disco:** `df -h`
- **Estado de servicios:** `systemctl status`
- **Conexiones abiertas:** `netstat -tunap`

Asegúrate de que el usuario del servidor tenga permisos para ejecutar estos comandos.

---

## 🤝 **Contribuciones**

¡Las contribuciones son bienvenidas! Si tienes ideas para mejorar este dashboard o encuentras un problema, crea un **issue** o envía un **pull request**.

---

## 📝 **Licencia**

Este proyecto está bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

