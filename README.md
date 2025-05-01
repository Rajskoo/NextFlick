# NextFlick - Správca filmov

NextFlick je webová aplikácia vytvorená v PHP pre správu filmov na pozretie a ich archiváciu.

## Funkcie

- Pridávanie nových filmov na zoznam
- Označovanie filmov ako pozrené/nepozrené
- Automatické presúvanie pozretých filmov do archívu
- Hodnotenie filmov pomocou ikoniek dinosaurov (1-5)
- Mazanie filmov zo zoznamu
- Plná integrácia s MySQL databázou

## Požiadavky

- PHP 7.4 alebo novší
- MySQL 5.7 alebo novší
- Webový server (Apache, Nginx, atď.)

## Inštalácia

1. Skopírujte súbory do priečinka vášho webového servera
2. Upravte súbor `config.php` podľa vašich nastavení databázy:
   ```php
   define('DB_HOST', 'localhost'); // adresa databázového servera
   define('DB_USER', 'username');  // používateľské meno pre databázu
   define('DB_PASS', 'password');  // heslo pre databázu
   define('DB_NAME', 'nextflick'); // názov databázy
   ```
3. Pri prvom spustení aplikácie sa automaticky vytvorí potrebná databáza a tabuľky, ak neexistujú

## Použitie

1. Otvorte aplikáciu vo webovom prehliadači
2. Kliknite na tlačidlo "Pridať film" pre pridanie nového filmu
3. Vyplňte požadované informácie a uložte
4. Pre označenie filmu ako pozretého kliknite na ikonu zaškrtnutia
5. Pre hodnotenie filmu kliknite na príslušný počet dinosaurov (1-5)
6. Pozreté filmy sa zobrazia v sekcii "Pozreté filmy"
7. Pre vymazanie filmu kliknite na ikonu koša

## Štruktúra súborov

- `index.php` - hlavná stránka aplikácie
- `config.php` - konfigurácia databázy
- `db.php` - pripojenie k databáze a inicializácia tabuliek
- `functions.php` - hlavné funkcie aplikácie
- `add_movie.php` - spracovanie pridávania nových filmov
- `update_movie.php` - aktualizácia stavu filmu (pozrené/nepozrené)
- `update_rating.php` - aktualizácia hodnotenia filmu
- `delete_movie.php` - mazanie filmov
- `css/style.css` - štýly aplikácie
- `js/script.js` - JavaScript funkcie aplikácie 