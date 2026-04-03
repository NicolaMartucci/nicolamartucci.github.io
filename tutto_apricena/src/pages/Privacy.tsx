export default function Privacy() {
  return (
    <main className="min-h-screen bg-[#F8F6F1]">
      <div className="bg-[#1A1A2E] pt-28 pb-14">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          <h1 className="text-3xl md:text-4xl font-black text-white" style={{ fontFamily: "'Playfair Display', serif" }}>
            Privacy Policy
          </h1>
          <p className="text-white/50 mt-2">Ultimo aggiornamento: Giugno 2025</p>
        </div>
      </div>
      <div className="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <div className="bg-white rounded-2xl p-8 shadow-sm space-y-6 text-[#1C1C1C] text-sm leading-relaxed">
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>1. Titolare del trattamento</h2>
            <p>TuttoApricena è un portale informativo indipendente. Per informazioni: <a href="mailto:info@tuttoapricena.it" className="text-[#E8A838] hover:underline">info@tuttoapricena.it</a></p>
          </section>
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>2. Dati raccolti</h2>
            <p>Il sito raccoglie esclusivamente i dati forniti volontariamente dall'utente tramite il modulo di contatto (nome, email, messaggio). Non raccogliamo dati sensibili. Il sito utilizza cookie tecnici e analitici per il corretto funzionamento e per analisi statistiche anonime.</p>
          </section>
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>3. Finalità del trattamento</h2>
            <p>I dati raccolti tramite il modulo di contatto sono utilizzati esclusivamente per rispondere alle richieste degli utenti. Non vengono ceduti a terzi.</p>
          </section>
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>4. Cookie</h2>
            <p>Il sito utilizza cookie tecnici necessari al funzionamento e cookie analitici anonimi (Google Analytics). Per maggiori dettagli consulta la nostra <a href="/cookie" className="text-[#E8A838] hover:underline">Cookie Policy</a>.</p>
          </section>
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>5. Diritti dell'utente</h2>
            <p>In conformità al GDPR (Regolamento UE 2016/679), l'utente ha diritto di accesso, rettifica, cancellazione e portabilità dei propri dati. Per esercitare questi diritti: <a href="mailto:info@tuttoapricena.it" className="text-[#E8A838] hover:underline">info@tuttoapricena.it</a></p>
          </section>
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>6. Fonti esterne</h2>
            <p>TuttoApricena aggrega contenuti da fonti terze, sempre citate. Non è responsabile dei contenuti, delle policy privacy o dei cookie delle fonti esterne collegate.</p>
          </section>
        </div>
      </div>
    </main>
  );
}
