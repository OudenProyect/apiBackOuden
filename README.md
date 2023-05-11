### Requisitios

- PHP 8.1
- MySQL 5.7

### Instalación de dependencias y base de datos.

- `composer install`
- `php bin/console doctrine:database:create`
- `php bin/console doctrine:migrations:migrate`

### Crear claves de JWT (Token)

- `php bin/console lexik:jwt:generate-keypair` (Crea una carpeta config/jwt con las claves privada y pública)
- 

### INSTALACION CORS
`composer require nelmio/cors-bundle`