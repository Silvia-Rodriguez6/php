#!/bin/bash

# Script de Configuración de Apache para Espacio de Trabajo

# Función para mostrar mensaje de error y salir
error_exit() {
    echo "Error: $1" >&2
    exit 1
}

# Asegurar que el script se ejecute con sudo
if [ "$EUID" -ne 0 ]; then
    error_exit "Por favor, ejecute como root o con sudo"
fi

# Solicitar nombre del directorio del proyecto
while true; do
    read -p "Introduzca el nombre del directorio del proyecto (ejemplo: PHP_Apache): " PROJECT_DIR

    # Validar que el nombre del directorio no esté vacío
    if [ -z "$PROJECT_DIR" ]; then
        echo "El nombre del directorio del proyecto no puede estar vacío. Inténtelo de nuevo."
        continue
    fi

    # Ruta completa del proyecto
    PROJECT_PATH="/workspaces/$PROJECT_DIR"

    # Comprobar si el directorio existe
    if [ -d "$PROJECT_PATH" ]; then
        echo "El directorio $PROJECT_PATH existe, OK."
        break
    else
        echo "El directorio $PROJECT_PATH no existe. Vuelve a intentarlo..."
    fi
done

# Establecer nombre del servidor como localhost
echo "ServerName localhost" | sudo tee /etc/apache2/conf-available/servername.conf

# Habilitar configuración de nombre de servidor
sudo a2enconf servername

# Iniciar servicio Apache
sudo service apache2 start

# Establecer permisos para directorio web
sudo chmod -R 755 /var/www/html

# Ruta del archivo de configuración
CONFIG_FILE="/etc/apache2/sites-available/000-default.conf"

# Crear un archivo de configuración temporal
TEMP_CONFIG=$(mktemp)

# Procesar el archivo de configuración
sudo awk -v project_path="$PROJECT_PATH" '
    /^[ \t]*DocumentRoot/ {
        print "#" $0  # Comentar línea original
        print "        DocumentRoot \"" project_path "\""  # Nueva línea con comillas
        next
    }
    /<\/VirtualHost>/ {
        print "        <Directory \"" project_path "\">"
        print "                Options Indexes FollowSymLinks"
        print "                AllowOverride All"
        print "                Require all granted"
        print "        </Directory>"
    }
    {print}
' "$CONFIG_FILE" > "$TEMP_CONFIG"

# Reemplazar archivo original con el temporal
sudo mv "$TEMP_CONFIG" "$CONFIG_FILE"

# Verificar configuración de Apache
if ! sudo apache2ctl configtest; then
    error_exit "Error en la configuración de Apache. Por favor, revise manualmente."
fi

# Recargar Apache para aplicar cambios
sudo service apache2 reload

echo "¡Configuración de Apache completada con éxito!"
echo "Directorio del proyecto configurado: $PROJECT_PATH"
