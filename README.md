# Car service teszt feladat

Ez a repository a Flexinform Integrált Kft. számára beadott car service teszt feladatot tartalmazza.

## Technológiák

- **PHP:** 8.2  
- **Laravel:** 12.0
- **Vue.js:** 3.5
- **Adatbázis:** MySQL  
- **Node.js & npm:** A frontend csomagok telepítéséhez és futtatásához

## Telepítési Útmutató
1. **Repository klónozása:**
    ```bash
    git clone https://github.com/KollarMaTT/flexinform_carservice.git
    ```

2. **Navigálás a projekt mappába:**
    ```bash
    cd flexinform_carservice-main
    ```

3. **Környezeti változók beállítása:**
    - Másold az `.env.example` fájlt `.env` névre:
    ```bash
    cp .env.example .env
    ```
    - Nyisd meg az `.env` fájlt, és konfiguráld az adatbázis.

4. **PHP függőségek telepítése:**
    ```bash
    composer install
    ```

5. **Node.js csomagok telepítése:**
    ```bash
    npm install
    ```

6. **Alkalmazás kulcs generálása:**
    ```bash
    php artisan key:generate
    ```

7. **Adatbázis migrációk futtatása:**
    ```bash
    php artisan migrate
    ```

8. **Frontend indítása:**
    ```bash
    npm run dev
    ```

9. **Szerver indítása:**
    ```bash
    php artisan serve
    ```

## Készítette:

Kollár Máté