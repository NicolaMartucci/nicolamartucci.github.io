# Studio Arké — sito web

Struttura del progetto pronta per l'hosting PHP (es. Aruba Easy Linux):

```
studio-arke/
├── index.html              Home — split screen Impresa / Parte Tecnica
├── impresa.html             Opere realizzate (gallerie a scorrimento) + Chi siamo
├── tecnica.html              Impiantistica (distinta tecnica), soluzioni chiavi in mano, ipotesi progetti
├── preventivo.html           Richiesta di preventivo gratuito, pagina e form dedicati
├── news.php                  News pubbliche (legge data/news.json)
├── lavora-con-noi.html      Form candidature
├── assets/css/style.css     Palette, tipografia, layout
├── assets/js/main.js         Header, menu mobile, home split
├── data/news.json            Contenuti delle news (scritto dal CMS)
├── data/candidature.json     Creato automaticamente alla prima candidatura ricevuta
├── data/preventivi.json      Creato automaticamente alla prima richiesta di preventivo
├── uploads/news/              Immagini di anteprima caricate dal CMS
├── uploads/candidature/       CV caricati dal form "Lavora con noi"
├── uploads/preventivi/         Planimetrie/foto allegate alle richieste di preventivo
└── admin/
    ├── index.html            Pannello CMS "Gestione News"
    └── api/
        ├── config.php        Percorsi e funzioni condivise
        ├── save.php           Crea/aggiorna una news (bozza o pubblicata)
        ├── list.php            Elenco news per il pannello
        ├── delete.php          Eliminazione news
        ├── candidatura.php    Riceve il form "Lavora con noi"
        └── preventivo.php     Riceve il form "Richiedi un preventivo"
```

## Come usare il CMS delle news

1. Apri `tuosito.it/admin/` (richiede PHP: va caricato su hosting, non aperto da file locale).
2. Compila titolo, testo e carica un'immagine di anteprima (trascinala o clicca sul riquadro).
3. **Salva bozza** → la news viene salvata ma non compare su `news.php`.
4. **Salva e pubblica** → la news compare subito sul sito, in cima alla lista.
5. Dalla tabella "News esistenti" puoi modificare, pubblicare/mettere in bozza o eliminare ogni news in un click.

Il CMS scrive tutto in `data/news.json`: nessun database da configurare, coerente con il resto dei tuoi progetti PHP.

## Da fare prima di andare online

- **Proteggere `/admin`**: al momento chiunque conosca l'indirizzo può accedere al pannello. Ti consiglio una protezione HTTP Basic Auth via `.htaccess` (te la preparo se vuoi) oppure un login con password, prima della messa online.
- **Permessi cartelle**: `data/`, `uploads/news/` e `uploads/candidature/` devono essere scrivibili da PHP (chmod 755 o 775 a seconda della configurazione Aruba).
- **Font DIN**: il font richiesto (FF DIN) è a licenza commerciale, non distribuibile via CDN pubblico. Ho usato "Barlow Condensed" + "Barlow" (Google Fonts, gratuiti), la coppia più vicina per proporzioni e spirito. Se possiedi già i file della licenza DIN, li sostituiamo con un `@font-face` — il CSS è già pronto per il cambio, basta aggiornare le variabili `--font-display` e `--font-body`.
- **Contenuti segnaposto**: tutte le foto (picsum.photos), i testi, i numeri e i progetti in "Ipotesi di progetti" sono di esempio, da sostituire con i contenuti reali dello studio.
- **Email candidature/preventivi**: `candidatura.php` e `preventivo.php` salvano le richieste in `data/*.json`; se vuoi ricevere subito una mail per ognuna, aggiungo l'invio con `mail()` o SMTP.
- **Palette**: aggiornata a bianco + grigio chiaro su tutto il sito. L'Impresa usa un grigio cemento come accento, la Parte Tecnica non usa più colore nei testi (solo il grigio standard), per un risultato più sobrio e meno "a schema fisso".

## Duplicazione delle gallerie Opere

Le due gallerie a scorrimento in `impresa.html` (destra→sinistra e sinistra→destra) funzionano duplicando le card nel markup, per ottenere un loop continuo senza scatti. Per aggiungere una nuova opera, copia un blocco `.marquee-card` e incollalo **in entrambe le metà duplicate** dello stesso nastro.
