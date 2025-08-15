# Mini Blog alkalmazás

Ez egy "mini-blog" alkalmazás, ahol a Symfony backend REST API-t biztosít a bejegyzések létrehozásához, módosításához, törléséhez és lekérdezéséhez.
A frontend egy HTML oldal amely jQuery és AJAX segítségével kommunikál az API-val.
Az adatbázis SQLite-ként van konfigurálva, és a Twig sablonok szolgálják ki a felhasználói felületet.

## Projekt indítása

- Függőségek telepítése:
    composer install
  
- Adatbázis létrehozása és migrációk futtatása:
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate

- Szerver indítása:
    symfony server:start

- A frontend az alkalmazás gyökér URL-jén érhető el:
    http://127.0.0.1:8000/
