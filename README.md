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

### BASE DE DATOS INSERTAR
- en la tabla de features, se debe insertar los parametros por el que se filtraran, en primera son:
        - parking
        - balcony
        - Swimming pool
        - Fireplace
        - Storage room
        - Garden
        - chimney

- tabla services_location
        - Girona
        - Barcelona


ejemplo
        {
      id: 7,
      name: 'parking',
      des: 'Parking',
    },
    {
      id: 10,
      name: 'balcon',
      des: 'Balcony',
    },
    {
      id: 8,
      name: 'piscina',
      des: 'Swimming pool',
    },
    {
      id: 12,
      name: 'terraza',
      des: 'Fireplace',
    },
    {
      id: 11,
      name: 'trastero',
      des: 'Storage room',
    },
    {
      id: 14,
      name: 'jardin',
      des: 'Garden',
    },
    {
      id: 7,
      name: 'calentador',
      des: 'Heater',
    }