# Studio FParchitetto — sito web

Struttura del progetto pronta per l'hosting PHP (es. Aruba Easy Linux):

```
studio-fparchitetto/
├── index.html              Home — split screen Azienda / Ufficio Tecnico
├── impresa.html             Opere realizzate (gallerie a scorrimento + lightbox) + Chi siamo
├── tecnica.html              Impiantistica (distinta tecnica), soluzioni chiavi in mano, metodo e strumenti
├── preventivo.html           Richiesta di preventivo gratuito, pagina e form dedicati
├── contatti.html             Contatti, mappa Google e indicazioni stradali
├── news.php                  News pubbliche (legge data/news.json)
├── lavora-con-noi.html      Form candidature
├── assets/css/style.css     Palette, tipografia, layout
├── assets/js/main.js         Header, menu mobile, home split, lightbox galleria
├── data/news.json            Contenuti delle news (scritto dal CMS)
├── data/candidature.json     Creato automaticamente alla prima candidatura ricevuta
├── data/preventivi.json      Creato automaticamente alla prima richiesta di preventivo
├── uploads/news/              Immagini di anteprima caricate dal CMS
├── uploads/news-allegati/      Documenti scaricabili allegati alle news (PDF, Word, ecc.)
├── uploads/candidature/       CV caricati dal form "Lavora con noi"
├── uploads/preventivi/         Planimetrie/foto allegate alle richieste di preventivo
└── admin/
    ├── index.php             Pannello CMS "Gestione News" (richiede login)
    ├── login.php             Pagina di accesso al pannello
    ├── logout.php            Chiude la sessione
    ├── cambia-password.php   Per aggiornare la password da soli
    ├── .htaccess             Disabilita il listing della cartella
    └── api/
        ├── auth.php          Login, sessione, "ricordami", anti brute-force
        ├── auth-config.php   Credenziali (username + hash bcrypt, MAI in chiaro)
        ├── .htaccess         Blocca l'accesso diretto a auth-config.php
        ├── config.php        Percorsi, funzioni condivise e gestione errori PHP
        ├── save.php           Crea/aggiorna una news (bozza o pubblicata) — richiede login
        ├── list.php            Elenco news per il pannello — richiede login
        ├── delete.php          Eliminazione news — richiede login
        ├── candidatura.php    Riceve il form "Lavora con noi" (pubblico)
        └── preventivo.php     Riceve il form "Richiedi un preventivo" (pubblico)
```

## Accesso al pannello admin (protetto da login)

Il pannello `/admin/` ora richiede l'accesso con nome utente e password.

1. Vai su `tuosito.it/admin/` (o `tuosito.it/admin/login.php`): se non hai già una sessione attiva, verrai reindirizzato al login.
2. Inserisci username e password. Spunta **"Ricorda le mie credenziali su questo dispositivo"** se vuoi restare collegato anche dopo aver chiuso il browser (per 30 giorni, tramite un cookie sicuro — non salva mai la password in chiaro).
3. Dopo 5 tentativi di accesso falliti dallo stesso dispositivo, il sistema blocca ulteriori tentativi per 15 minuti (protezione anti-attacco).
4. Per cambiare la password in autonomia, una volta dentro clicca **"Cambia password"** in alto: la nuova password viene subito cifrata (hash bcrypt) e salvata — non è mai scritta in chiaro da nessuna parte, nemmeno nei file del sito.
5. Il pulsante **"Esci"** chiude la sessione e, se avevi spuntato "ricordami", elimina anche quel cookie.

**Nota tecnica:** la password non compare mai in chiaro nel codice: viene confrontata tramite un hash bcrypt (`auth-config.php`), e i file sensibili (`auth-config.php`, i JSON in `data/`) sono protetti da `.htaccess` contro l'accesso diretto via browser.

## Come usare il CMS delle news

1. Accedi al pannello come descritto sopra.
2. Compila titolo, testo e carica un'immagine di anteprima (trascinala o clicca sul riquadro).
3. **Salva bozza** → la news viene salvata ma non compare su `news.php`.
4. **Salva e pubblica** → la news compare subito sul sito, in cima alla lista.
5. Dalla tabella "News esistenti" puoi modificare, pubblicare/mettere in bozza o eliminare ogni news in un click.

Il CMS scrive tutto in `data/news.json`: nessun database da configurare, coerente con il resto dei tuoi progetti PHP.

## Da fare prima di andare online

- **Proteggere `/admin`**: al momento chiunque conosca l'indirizzo può accedere al pannello. Ti consiglio una protezione HTTP Basic Auth via `.htaccess` (te la preparo se vuoi) oppure un login con password, prima della messa online.
- **Permessi cartelle**: `data/`, `uploads/news/`, `uploads/news-allegati/` e `uploads/candidature/` devono essere scrivibili da PHP (chmod 755 o 775 a seconda della configurazione Aruba).
- **Font DIN**: il font richiesto (FF DIN) è a licenza commerciale, non distribuibile via CDN pubblico. Ho usato "Barlow Condensed" + "Barlow" (Google Fonts, gratuiti), la coppia più vicina per proporzioni e spirito. Se possiedi già i file della licenza DIN, li sostituiamo con un `@font-face` — il CSS è già pronto per il cambio, basta aggiornare le variabili `--font-display` e `--font-body`.
- **Contenuti segnaposto**: tutte le foto (picsum.photos), i testi e i dati di contatto (indirizzo, mappa, telefoni, email) sono di esempio, da sostituire con i contenuti reali dello studio.
- **Email candidature/preventivi**: `candidatura.php` e `preventivo.php` salvano le richieste in `data/*.json`; se vuoi ricevere subito una mail per ognuna, aggiungo l'invio con `mail()` o SMTP.
- **Palette**: aggiornata a bianco + grigio chiaro su tutto il sito. L'Azienda usa un grigio cemento come accento, l'Ufficio Tecnico non usa più colore nei testi (solo il grigio standard), per un risultato più sobrio e meno "a schema fisso".

## Duplicazione delle gallerie Opere

Le due gallerie a scorrimento in `impresa.html` (destra→sinistra e sinistra→destra) funzionano duplicando le card nel markup, per ottenere un loop continuo senza scatti. Per aggiungere una nuova opera, copia un blocco `.marquee-card` e incollalo **in entrambe le metà duplicate** dello stesso nastro. Ogni foto è cliccabile e apre un lightbox a schermo intero con frecce di navigazione.

## Risoluzione problemi

**Il pannello admin mostra "Errore di connessione al server" o "Il server ha risposto in modo inatteso" quando salvo una news**

Non è un problema di rete: quasi sempre è uno di questi due casi, tipici del passaggio da un test in locale all'hosting reale.

1. **Permessi di scrittura mancanti.** Le cartelle `data/`, `uploads/news/`, `uploads/news-allegati/`, `uploads/candidature/` e `uploads/preventivi/` devono essere scrivibili da PHP. Il file ZIP non porta con sé i permessi giusti una volta ricaricato via FTP: da pannello di gestione file di Aruba (o via client FTP, tasto destro → permessi), imposta quelle cartelle a **755**; se il salvataggio continua a fallire, prova **775** o, in ultima istanza, **777**.
2. **Versione di PHP troppo vecchia.** Il pannello richiede **PHP 7.4 o superiore** (va benissimo anche 8.x). Su Aruba puoi cambiare la versione di PHP dal pannello di gestione hosting. Con PHP inferiore alla 7.4 il salvataggio fallisce con un errore generico.

Il pannello ora mostra, sotto al messaggio di errore, il dettaglio della risposta ricevuta dal server: se compare un testo che parla di "permission denied" è il punto 1, se parla di errori di sintassi PHP è il punto 2.
