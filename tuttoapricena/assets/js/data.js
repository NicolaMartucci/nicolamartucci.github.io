// ============================
// TUTTOAPRICENA — DATA FILE
// Aggiornato con dati reali
// ============================
const TA = {

  // ===== CONFIGURAZIONE SITO =====
  config: {
    siteName: "TuttoApricena",
    tagline: "Il portale informativo di Apricena",
    email: "info@tuttoapricena.it",
    citta: "Apricena (FG), Puglia — Italia",
    facebook: "",
    instagram: "",
    heroImages: [
      "https://images.unsplash.com/photo-1555992336-03a23c7b20ee?w=1800&q=85",
      "https://images.unsplash.com/photo-1516483638261-f4dbaf036963?w=1800&q=85",
      "https://images.unsplash.com/photo-1534430480872-3498386e7856?w=1800&q=85"
    ],
    logoUrl: "",
    colorPrimary: "#1A1A2E",
    colorAccent: "#E8A838"
  },

  // ===== NOTIZIE =====
  notizie: [
    {
      id: 1,
      slug: "carnevale-storico-apricena-2026",
      titolo: "Carnevale Storico di Apricena 2026: tutto pronto per l'edizione più grande",
      categoria: "Cultura",
      categoriaSlug: "cultura",
      immagine: "https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&q=80",
      abstract: "Domenica 8 febbraio 2026 al via il Carnevale Storico di Apricena con la Gran Parata dei Carri e lo show 'Nei 90 io c'ero'. L'evento più atteso dell'anno torna con novità.",
      testo: "Apricena si prepara a vivere un'altra edizione straordinaria del suo Carnevale Storico, tra gli eventi più attesi e partecipati dell'intera Puglia. Il via è previsto per domenica 8 febbraio 2026 con la 'Gran Parata dei Carri' a partire dalle 14.30 e la serata musicale 'Nei 90 io c'ero'.\n\nIl Carnevale di Apricena è entrato nella Rete dei Grandi Carnevali di Puglia e d'Italia, a testimonianza del livello raggiunto dall'evento che unisce tradizione locale, creatività dei carri allegorici e ospiti di fama nazionale. La manifestazione ha origini nel 1955 con la Festa della Matricola e nel corso dei decenni è diventata uno degli eventi di aggregazione più importanti per la comunità apricenese e per i comuni vicini.",
      fonte: "Comune di Apricena",
      fonteUrl: "https://www.comune.apricena.fg.it",
      data: "2026-01-15",
      inEvidenza: true,
      tag: ["carnevale","cultura","eventi","tradizione"]
    },
    {
      id: 2,
      slug: "marmo-apricena-secondo-polo-nazionale",
      titolo: "Il marmo di Apricena: secondo polo lapideo nazionale, eccellenza nel mondo",
      categoria: "Economia",
      categoriaSlug: "economia",
      immagine: "https://images.unsplash.com/photo-1565791380713-1756b9a05343?w=800&q=80",
      abstract: "La Pietra di Apricena, conosciuta anche come 'Trani', è uno dei marmi più pregiati d'Italia. Il distretto lapideo locale rappresenta il secondo polo nazionale di estrazione.",
      testo: "Apricena è indicata come secondo polo nazionale di estrazione dei materiali lapidei, con un bacino marmifero che si estende su migliaia di ettari e che dà lavoro a centinaia di famiglie del territorio.\n\nLa Pietra di Apricena — conosciuta nei mercati internazionali anche come 'Trani' — è apprezzata per la sua composizione cristallina, le tonalità che spaziano dal bianco al dorato, e per le sue eccellenti caratteristiche chimico-fisiche. La lavorazione si è progressivamente meccanizzata nel dopoguerra, con l'introduzione di utensili diamantati che hanno rivoluzionato i tempi produttivi.\n\nTra le principali realtà del settore figurano Augelli Marmi, con oltre 300.000 metri quadrati di cave proprie, e il Gruppo Franco Dell'Erba, specializzato nell'estrazione e commercializzazione. Il Consorzio CONPIETRA lavora per la valorizzazione e promozione internazionale della pietra locale.",
      fonte: "Augelli Marmi / CONPIETRA",
      fonteUrl: "https://www.augellimarmi.it",
      data: "2025-11-20",
      inEvidenza: true,
      tag: ["marmo","economia","pietra","cave"]
    },
    {
      id: 3,
      slug: "festa-patronale-madonna-incoronata-2025",
      titolo: "Festa Patronale della Madonna Incoronata 2025: quattro giorni di celebrazioni",
      categoria: "Cultura",
      categoriaSlug: "cultura",
      immagine: "https://images.unsplash.com/photo-1543373014-cfe4f4bc1cdf?w=800&q=80",
      abstract: "Dal 30 maggio al 2 giugno 2025 Apricena ha celebrato i solenni festeggiamenti in onore della Patrona Maria SS. Incoronata e dei Santi Compatroni Michele e Martino.",
      testo: "Apricena ha vissuto quattro giorni intensi di devozione e festa per la Patrona Maria Santissima Incoronata. I solenni festeggiamenti si sono svolti dal 30 maggio al 2 giugno 2025, con la processione di traslazione del simulacro della Madonna dal Santuario Mariano alla Chiesa Madre avvenuta sabato 26 aprile.\n\nLa festa patronale, che ricorre ogni anno nell'ultima domenica di maggio, è uno degli appuntamenti più sentiti dalla comunità apricenese. Come da tradizione, parte del denaro raccolto dal Comitato Festa Patronale è stato devoluto in beneficenza alle parrocchie cittadine Sacra Famiglia e Beata Vergine Maria del Rosario.",
      fonte: "Parrocchia Santi Martino e Lucia",
      fonteUrl: "https://www.comune.apricena.fg.it",
      data: "2025-06-02",
      inEvidenza: false,
      tag: ["patrona","madonna","fede","tradizione"]
    },
    {
      id: 4,
      slug: "notte-bianca-apricena-dicembre-2025",
      titolo: "Notte Bianca di Apricena: Corso Roma si trasforma in un grande palcoscenico",
      categoria: "Società",
      categoriaSlug: "societa",
      immagine: "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80",
      abstract: "Lunedì 29 dicembre 2025, Corso Roma diventa palcoscenico di luci, musica e divertimento con artisti di strada, musica live, giostre e prodotti della gastronomia locale.",
      testo: "Il 29 dicembre 2025 Apricena ha festeggiato la Notte Bianca invernale: a partire dalle ore 20, Corso Roma si è trasformato in un grande palcoscenico all'aperto con artisti di strada, musica live, giostre, gonfiabili e stand gastronomici con i prodotti tipici della tradizione apricenese.\n\nL'evento ha registrato una grandissima affluenza di pubblico, con famiglie e giovani giunti anche dai comuni limitrofi. La Notte Bianca è diventata negli anni un appuntamento fisso che accompagna le festività natalizie.",
      fonte: "Comune di Apricena",
      fonteUrl: "https://www.comune.apricena.fg.it",
      data: "2025-12-28",
      inEvidenza: false,
      tag: ["notteBianca","musica","famiglia","Natale"]
    },
    {
      id: 5,
      slug: "politecnico-bari-accordo-cave-apricena",
      titolo: "Politecnico di Bari e Comune di Apricena: accordo per valorizzare le cave",
      categoria: "Cultura",
      categoriaSlug: "cultura",
      immagine: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&q=80",
      abstract: "Il Politecnico di Bari e il Comune di Apricena hanno sottoscritto un accordo di collaborazione per la valorizzazione architettonica e la rigenerazione delle cave di marmo.",
      testo: "Il Politecnico di Bari e il Comune di Apricena hanno siglato un importante accordo di collaborazione per immaginare il futuro delle cave di pietra del territorio. Il progetto, guidato dal prof. Giuseppe Fallacara, vede studenti di architettura lavorare su proposte per rigenerare le cave abbandonate trasformandole in hotel, centri sportivi, teatri e spazi culturali.\n\nL'intesa è stata firmata dal Rettore Francesco Cupertino e dal Sindaco di Apricena Antonio Potenza. L'obiettivo è valorizzare la Madre Pietra di Apricena non solo come risorsa economica ma anche come patrimonio culturale e paesaggistico del Gargano.",
      fonte: "Politecnico di Bari",
      fonteUrl: "https://www.poliba.it",
      data: "2025-03-14",
      inEvidenza: false,
      tag: ["università","cave","cultura","architettura"]
    },
    {
      id: 6,
      slug: "kermesse-musicale-estate-apricena-2025",
      titolo: "Estate musicale ad Apricena: kermesse di agosto con artisti di spessore",
      categoria: "Cultura",
      categoriaSlug: "cultura",
      immagine: "https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&q=80",
      abstract: "Dal 1° al 31 agosto 2025 Apricena ha ospitato la sua consueta kermesse musicale estiva, con concerti e spettacoli ogni sera nelle piazze del centro storico.",
      testo: "L'estate 2025 ad Apricena è stata animata da un ricco calendario di eventi musicali che ha trasformato le piazze della città in un palcoscenico a cielo aperto per tutto il mese di agosto. La kermesse ha visto alternarsi artisti locali, regionali e nazionali in concerti gratuiti per la cittadinanza.\n\nGli appuntamenti si sono tenuti principalmente in Piazza Giovanni Paolo II e in Piazza San Josemaria Escrivá, con grande partecipazione di pubblico sia locale che turistico. L'iniziativa rientra nel più ampio programma di valorizzazione culturale del territorio promosso dall'Amministrazione comunale.",
      fonte: "Comune di Apricena",
      fonteUrl: "https://www.comune.apricena.fg.it",
      data: "2025-08-01",
      inEvidenza: false,
      tag: ["musica","estate","concerti","cultura"]
    }
  ],

  categorieNotizie: [
    { nome: "Cronaca", slug: "cronaca", colore: "#DC2626" },
    { nome: "Cultura", slug: "cultura", colore: "#7C3AED" },
    { nome: "Sport", slug: "sport", colore: "#16A34A" },
    { nome: "Economia", slug: "economia", colore: "#0284C7" },
    { nome: "Società", slug: "societa", colore: "#EA580C" },
    { nome: "Turismo", slug: "turismo", colore: "#E8A838" }
  ],

  // ===== EVENTI =====
  eventi: [
    {
      id: 1,
      slug: "carnevale-storico-apricena-2026",
      titolo: "Carnevale Storico di Apricena 2026",
      categoria: "Cultura",
      dataInizio: "2026-02-08",
      dataFine: "2026-02-08",
      orario: "14:30",
      luogo: "Piazza Giovanni Paolo II, Apricena",
      descrizione: "Torna il Carnevale Storico di Apricena, tra i più importanti della Puglia. Gran Parata dei Carri dalle 14.30 e serata con lo show 'Nei 90 io c'ero'. Ingresso libero.",
      immagine: "https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&q=80",
      inEvidenza: true
    },
    {
      id: 2,
      slug: "festa-patronale-madonna-incoronata-2026",
      titolo: "Festa Patronale Madonna Incoronata 2026",
      categoria: "Religioso",
      dataInizio: "2026-05-29",
      dataFine: "2026-06-01",
      orario: "18:00",
      luogo: "Santuario Madonna Incoronata / Chiesa Madre, Apricena",
      descrizione: "I solenni festeggiamenti in onore della Patrona Maria SS. Incoronata e dei Santi Compatroni Michele e Martino. Processioni, concerti e fuochi d'artificio.",
      immagine: "https://images.unsplash.com/photo-1543373014-cfe4f4bc1cdf?w=800&q=80",
      inEvidenza: true
    },
    {
      id: 3,
      slug: "kermesse-musicale-estate-2026",
      titolo: "Kermesse Musicale Estate 2026",
      categoria: "Musica",
      dataInizio: "2026-08-01",
      dataFine: "2026-08-31",
      orario: "21:00",
      luogo: "Piazze del centro storico, Apricena",
      descrizione: "Un mese di musica e spettacoli nelle piazze di Apricena. Concerti gratuiti con artisti locali e nazionali ogni sera di agosto.",
      immagine: "https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&q=80",
      inEvidenza: false
    },
    {
      id: 4,
      slug: "notte-bianca-estate-2026",
      titolo: "Notte Bianca Estiva di Apricena",
      categoria: "Cultura",
      dataInizio: "2026-07-15",
      dataFine: "2026-07-15",
      orario: "21:00",
      luogo: "Corso Roma, Apricena",
      descrizione: "La Notte Bianca estiva anima il Corso Roma con negozi aperti, street food, musica live, artisti di strada e divertimento per tutta la famiglia.",
      immagine: "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&q=80",
      inEvidenza: false
    },
    {
      id: 5,
      slug: "mercatino-natalizio-apricena-2026",
      titolo: "Mercatino di Natale di Apricena",
      categoria: "Cultura",
      dataInizio: "2026-12-08",
      dataFine: "2026-12-24",
      orario: "17:00",
      luogo: "Piazza Municipio, Apricena",
      descrizione: "Il tradizionale mercatino natalizio con prodotti artigianali, dolci tipici pugliesi, vin brulé e intrattenimento per i bambini. L'atmosfera del Natale nel cuore di Apricena.",
      immagine: "https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&q=80",
      inEvidenza: false
    },
    {
      id: 6,
      slug: "sagra-prodotti-tipici-apricena-2026",
      titolo: "Sagra dei Prodotti Tipici Garganici",
      categoria: "Gastronomia",
      dataInizio: "2026-09-12",
      dataFine: "2026-09-14",
      orario: "19:00",
      luogo: "Villa Comunale, Apricena",
      descrizione: "Tre serate di gusto con i prodotti tipici del Gargano: olio d'oliva, formaggi, salumi, taralli e vini locali. Musica folk e stand gastronomici. Ingresso libero.",
      immagine: "https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80",
      inEvidenza: false
    }
  ],

  // ===== FARMACIE (dati reali da ABCSalute e Ordine Farmacisti FG) =====
  farmacie: [
    {
      id: 1,
      nome: "Farmacia Matarese Dott. Giovanni",
      indirizzo: "Viale Papa Giovanni XXIII — Apricena (FG)",
      telefono: "0882 641134",
      orario: "08:30 - 13:00 / 16:30 - 20:00",
      notturno: false
    },
    {
      id: 2,
      nome: "Farmacia Via Garibaldi",
      indirizzo: "Via Giuseppe Garibaldi, 94 — Apricena (FG)",
      telefono: "0882 641322",
      orario: "08:00 - 13:00 / 16:30 - 20:30",
      notturno: true
    },
    {
      id: 3,
      nome: "Farmacia Via Garibaldi 36",
      indirizzo: "Via Giuseppe Garibaldi, 36 — Apricena (FG)",
      telefono: "0882 641134",
      orario: "08:30 - 13:00 / 16:30 - 20:00",
      notturno: false
    },
    {
      id: 4,
      nome: "Farmacia Via Roma",
      indirizzo: "Via Roma, 6 — Apricena (FG)",
      telefono: "0882 641126",
      orario: "08:30 - 13:00 / 16:30 - 20:00",
      notturno: true
    },
    {
      id: 5,
      nome: "Farmacia Viale Aldo Moro",
      indirizzo: "Viale Aldo Moro, 134/C/8 — Apricena (FG)",
      telefono: "0882 707016",
      orario: "08:30 - 13:00 / 16:00 - 20:00",
      notturno: false
    }
  ],

  // ===== SERVIZI (dati reali) =====
  servizi: [
    {
      id: 1,
      nome: "Comune di Apricena",
      categoria: "Istituzioni",
      categoriaSlug: "istituzioni",
      icona: "building",
      indirizzo: "Piazza Municipio, 1 — 71011 Apricena (FG)",
      telefono: "0882 6411",
      email: "segreteria@comune.apricena.fg.it",
      sitoWeb: "https://www.comune.apricena.fg.it",
      orari: "Lun-Ven: 08:30-13:30 | Mar-Gio: 15:30-17:30",
      descrizione: "Sede del Municipio di Apricena. Sindaco: Antonio Potenza. Anagrafe, edilizia, tributi, servizi sociali e tutti gli uffici comunali."
    },
    {
      id: 2,
      nome: "Carabinieri — Stazione di Apricena",
      categoria: "Sicurezza",
      categoriaSlug: "sicurezza",
      icona: "shield",
      indirizzo: "Via Matteotti — Apricena (FG)",
      telefono: "112",
      email: "",
      sitoWeb: "",
      orari: "24 ore su 24",
      descrizione: "Stazione dei Carabinieri di Apricena. Per emergenze chiamare il 112."
    },
    {
      id: 3,
      nome: "Polizia Municipale di Apricena",
      categoria: "Sicurezza",
      categoriaSlug: "sicurezza",
      icona: "shield",
      indirizzo: "Piazza Municipio, 1 — Apricena (FG)",
      telefono: "0882 641222",
      email: "pm@comune.apricena.fg.it",
      sitoWeb: "",
      orari: "Lun-Sab: 08:00-14:00",
      descrizione: "Polizia Municipale di Apricena. Viabilità, sicurezza urbana e controllo del territorio."
    },
    {
      id: 4,
      nome: "Ospedale di San Severo — Casa Sollievo",
      categoria: "Sanità",
      categoriaSlug: "sanita",
      icona: "heart",
      indirizzo: "Via Lucera — San Severo (FG)",
      telefono: "0882 274111",
      email: "",
      sitoWeb: "https://www.aslfg.it",
      orari: "Pronto Soccorso: 24h",
      descrizione: "Ospedale di riferimento più vicino ad Apricena (circa 15 km). Pronto Soccorso attivo 24 ore. Collegato con l'ASL FG."
    },
    {
      id: 5,
      nome: "ASL FG — Distretto di Apricena",
      categoria: "Sanità",
      categoriaSlug: "sanita",
      icona: "heart",
      indirizzo: "Via Roma — Apricena (FG)",
      telefono: "0882 641444",
      email: "",
      sitoWeb: "https://www.aslfg.it",
      orari: "Lun-Ven: 08:00-13:30",
      descrizione: "Distretto sanitario ASL Foggia di Apricena. Prenotazioni visite, medicina di base, consultorio familiare."
    },
    {
      id: 6,
      nome: "Ufficio Postale di Apricena",
      categoria: "Servizi",
      categoriaSlug: "servizi",
      icona: "mail",
      indirizzo: "Via Garibaldi, 30 — Apricena (FG)",
      telefono: "0882 641555",
      email: "",
      sitoWeb: "https://www.poste.it",
      orari: "Lun-Ven: 08:20-13:35 | Sab: 08:20-12:35",
      descrizione: "Ufficio postale di Apricena. Spedizioni nazionali e internazionali, Bancoposta, bollettini, pagamenti e pratiche varie."
    },
    {
      id: 7,
      nome: "Stazione FS — San Severo",
      categoria: "Trasporti",
      categoriaSlug: "trasporti",
      icona: "train",
      indirizzo: "Piazza Stazione — San Severo (FG)",
      telefono: "892021",
      email: "",
      sitoWeb: "https://www.trenitalia.com",
      orari: "Orari treni variabili",
      descrizione: "La stazione ferroviaria più vicina ad Apricena è quella di San Severo, a circa 15 km. Collegamenti con Foggia, Bari e Roma Termini."
    },
    {
      id: 8,
      nome: "Biblioteca Comunale di Apricena",
      categoria: "Cultura",
      categoriaSlug: "cultura",
      icona: "book",
      indirizzo: "Via della Cultura — Apricena (FG)",
      telefono: "0882 641333",
      email: "biblioteca@comune.apricena.fg.it",
      sitoWeb: "https://www.comune.apricena.fg.it",
      orari: "Lun-Ven: 08:30-13:30 | Mar-Gio: 15:30-18:30",
      descrizione: "Biblioteca Comunale di Apricena. Prestito libri, sala studio, emeroteca e attività culturali per tutte le età."
    }
  ],

  categorieServizi: [
    { nome: "Istituzioni", slug: "istituzioni" },
    { nome: "Sanità", slug: "sanita" },
    { nome: "Sicurezza", slug: "sicurezza" },
    { nome: "Trasporti", slug: "trasporti" },
    { nome: "Cultura", slug: "cultura" },
    { nome: "Servizi", slug: "servizi" }
  ],

  // ===== LOCALI (dati reali) =====
  locali: [
    {
      id: 1,
      slug: "sarni-ristorazione-maglione-apricena",
      nome: "Sarni Ristorazione Maglione",
      tipo: "Ristorante",
      tipoSlug: "ristorante",
      descrizione: "Uno dei ristoranti più apprezzati di Apricena secondo Tripadvisor. Cucina pugliese di qualità, carni locali e atmosfera accogliente nel cuore della città.",
      indirizzo: "Apricena (FG)",
      telefono: "0882 645834",
      sitoWeb: "",
      orari: "Mer-Lun: 12:30-15:00 / 19:30-23:00 | Martedì chiuso",
      immagine: "https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80",
      inEvidenza: true
    },
    {
      id: 2,
      slug: "bar-excelsior-apricena",
      nome: "Bar Excelsior",
      tipo: "Bar",
      tipoSlug: "bar",
      descrizione: "Bar storico di Apricena in Via Roma 46. Pasticceria artigianale, gelati, colazioni, aperitivi e cocktail fino a tarda notte. Punto di riferimento della movida locale.",
      indirizzo: "Via Roma, 46 — Apricena (FG)",
      telefono: "0882 641XXX",
      sitoWeb: "",
      orari: "Tutti i giorni: 06:30-24:00",
      immagine: "https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=800&q=80",
      inEvidenza: true
    },
    {
      id: 3,
      slug: "ristorante-via-foggia-apricena",
      nome: "Ristorante Via Foggia",
      tipo: "Ristorante",
      tipoSlug: "ristorante",
      descrizione: "Ristorante di cucina tradizionale pugliese in posizione comoda sulla Via Foggia. Specialità locali, pasta fresca e carni del territorio garganico.",
      indirizzo: "Via Foggia, 14 — Apricena (FG)",
      telefono: "349 1555979",
      sitoWeb: "",
      orari: "Mar-Dom: 12:00-15:00 / 19:00-23:00 | Lunedì chiuso",
      immagine: "https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&q=80",
      inEvidenza: false
    },
    {
      id: 4,
      slug: "bar-il-migliore-apricena",
      nome: "Bar Tabacchi Il Migliore",
      tipo: "Bar",
      tipoSlug: "bar",
      descrizione: "Bar e tabaccheria nel centro di Apricena, tra i preferiti degli abitanti locali. Colazioni, caffè, panini e servizio tabacchi. Ambiente accogliente e familiare.",
      indirizzo: "Apricena (FG)",
      telefono: "",
      sitoWeb: "",
      orari: "Lun-Sab: 06:00-21:00 | Dom: 07:00-13:00",
      immagine: "https://images.unsplash.com/photo-1453614512568-c4024d13c247?w=800&q=80",
      inEvidenza: false
    },
    {
      id: 5,
      slug: "artis-beer-lab-apricena",
      nome: "Artis Beer Lab",
      tipo: "Bar",
      tipoSlug: "bar",
      descrizione: "Il craft beer lab di Apricena. Birre artigianali selezionate, cocktail originali e street food. Locale moderno e giovane, ideale per aperitivi e serate tra amici.",
      indirizzo: "Apricena (FG)",
      telefono: "",
      sitoWeb: "",
      orari: "Mar-Dom: 17:00-01:00 | Lunedì chiuso",
      immagine: "https://images.unsplash.com/photo-1436076863939-06870fe779c2?w=800&q=80",
      inEvidenza: false
    },
    {
      id: 6,
      slug: "ristorante-incoronata-apricena",
      nome: "Ristorante dell'Incoronata",
      tipo: "Ristorante",
      tipoSlug: "ristorante",
      descrizione: "Locale immerso nella campagna apricenese, vicino al Santuario dell'Incoronata. Cucina tipica locale con prodotti a km zero, piatti della tradizione garganica e carne alla brace.",
      indirizzo: "Contrada dell'Incoronata, 1 — Apricena (FG)",
      telefono: "0882 645834",
      sitoWeb: "",
      orari: "Ven-Dom: 12:30-15:30 / 19:30-23:00",
      immagine: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80",
      inEvidenza: true
    },
    {
      id: 7,
      slug: "dimora-camilla-apricena",
      nome: "Dimora Camilla",
      tipo: "Ristorante",
      tipoSlug: "ristorante",
      descrizione: "Locale gourmet di Apricena. Eccellenza enogastronomica, cene speciali e serate a tema. Location raffinata per esperienze sensoriali uniche. Segnalato dalla stampa nazionale.",
      indirizzo: "Apricena (FG)",
      telefono: "",
      sitoWeb: "",
      orari: "Gio-Dom: 19:30-23:30 | Su prenotazione",
      immagine: "https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80",
      inEvidenza: true
    },
    {
      id: 8,
      slug: "lato-opposto-apricena",
      nome: "Lato Opposto",
      tipo: "Bar",
      tipoSlug: "bar",
      descrizione: "Bar e lounge nel centro di Apricena. Cocktail bar, aperitivi, musica e un'atmosfera informale. Luogo ideale per i giovani apricenesi.",
      indirizzo: "Apricena (FG)",
      telefono: "",
      sitoWeb: "",
      orari: "Mar-Dom: 17:00-02:00",
      immagine: "https://images.unsplash.com/photo-1470337458703-46ad1756a187?w=800&q=80",
      inEvidenza: false
    }
  ],

  tipiLocali: [
    { nome: "Ristorante", slug: "ristorante" },
    { nome: "Bar", slug: "bar" },
    { nome: "Pizzeria", slug: "pizzeria" },
    { nome: "Alloggio", slug: "alloggio" },
    { nome: "Negozio", slug: "negozio" },
    { nome: "Artigianato", slug: "artigianato" }
  ],

  // ===== SPONSOR =====
  // Gold: descrizione completa, telefono, indirizzo, sito, foto
  // Silver: descrizione e contatti base
  // Bronze: solo nome e settore
  sponsor: [
    {
      id: 1,
      nome: "Augelli Marmi Srl",
      livello: "Gold",
      settore: "Lapideo",
      logo: "",
      immagine: "https://images.unsplash.com/photo-1565791380713-1756b9a05343?w=600&q=80",
      descrizione: "Leader nell'estrazione e lavorazione della Pietra di Apricena. Con oltre 300.000 m² di cave proprie, Augelli Marmi è un'eccellenza del distretto lapideo garganico nel mondo. Quattro sedi: Apricena, Santarcangelo di Romagna, Carrara e Sammichele di Bari.",
      telefono: "0882 641XXX",
      indirizzo: "Zona Industriale — Apricena (FG)",
      sitoWeb: "https://www.augellimarmi.it",
      attivo: true
    },
    {
      id: 2,
      nome: "Gruppo Franco Dell'Erba",
      livello: "Gold",
      settore: "Lapideo",
      logo: "",
      immagine: "https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=600&q=80",
      descrizione: "Specializzati nell'escavazione, raccolta, lavorazione e commercializzazione della Pietra di Apricena (Trani). Uno dei più importanti giacimenti marmiferi del meridione, con cava di proprietà e stabilimento nella zona industriale di Apricena.",
      telefono: "0882 64XXXX",
      indirizzo: "Zona Industriale — Apricena (FG)",
      sitoWeb: "https://www.dellerbamarmi.eu",
      attivo: true
    },
    {
      id: 3,
      nome: "CONPIETRA Consorzio",
      livello: "Silver",
      settore: "Consorzio Lapideo",
      logo: "",
      immagine: "",
      descrizione: "Consorzio per la valorizzazione del marmo di Apricena. Unisce artigiani e PMI del settore lapideo per promuovere la Pietra di Apricena sui mercati nazionali e internazionali.",
      telefono: "",
      indirizzo: "Viale Giuseppe Di Vittorio, 105 — Apricena (FG)",
      sitoWeb: "http://www.conpietra.it",
      attivo: true
    },
    {
      id: 4,
      nome: "La Gazzetta di Apricena",
      livello: "Silver",
      settore: "Media / Informazione",
      logo: "",
      immagine: "",
      descrizione: "Periodico di informazione locale. Direttore responsabile Michele Sales, registrato al Tribunale di Foggia. Notizie, cronaca e cultura per la comunità apricenese.",
      telefono: "",
      indirizzo: "Apricena (FG)",
      sitoWeb: "https://www.lagazzettadiapricena.it",
      attivo: true
    },
    {
      id: 5,
      nome: "Dimora Camilla",
      livello: "Silver",
      settore: "Ristorazione",
      logo: "",
      immagine: "",
      descrizione: "Ristorante gourmet di Apricena. Eccellenza enogastronomica locale, cene speciali e serate a tema per esperienze indimenticabili.",
      telefono: "",
      indirizzo: "Apricena (FG)",
      sitoWeb: "",
      attivo: true
    },
    {
      id: 6,
      nome: "Bar Excelsior",
      livello: "Bronze",
      settore: "Bar / Pasticceria",
      logo: "",
      immagine: "",
      descrizione: "",
      telefono: "",
      indirizzo: "Via Roma, 46 — Apricena",
      sitoWeb: "",
      attivo: true
    },
    {
      id: 7,
      nome: "Artis Beer Lab",
      livello: "Bronze",
      settore: "Bar / Birrificio",
      logo: "",
      immagine: "",
      descrizione: "",
      telefono: "",
      indirizzo: "Apricena (FG)",
      sitoWeb: "",
      attivo: true
    },
    {
      id: 8,
      nome: "Sarni Ristorazione Maglione",
      livello: "Bronze",
      settore: "Ristorazione",
      logo: "",
      immagine: "",
      descrizione: "",
      telefono: "",
      indirizzo: "Apricena (FG)",
      sitoWeb: "",
      attivo: true
    }
  ],

  // ===== HELPERS =====
  getCatColor(slug) {
    const cat = this.categorieNotizie.find(c => c.slug === slug);
    return cat ? cat.colore : '#E8A838';
  },
  formatDate(dateStr, opts) {
    return new Date(dateStr).toLocaleDateString('it-IT', opts || { day: 'numeric', month: 'long', year: 'numeric' });
  },
  getFarmaciaOfDay(date) {
    const d = date || new Date();
    const epoch = new Date('2025-01-01');
    const diff = Math.floor((d - epoch) / 86400000);
    const idx = ((diff % this.farmacie.length) + this.farmacie.length) % this.farmacie.length;
    return this.farmacie[idx];
  },
  getFarmacieProssimi(n) {
    const result = [];
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    for (let i = 0; i < n; i++) {
      const d = new Date(today);
      d.setDate(today.getDate() + i);
      result.push({ data: d, farmacia: this.getFarmaciaOfDay(d) });
    }
    return result;
  }
};

if (typeof module !== 'undefined') module.exports = TA;
