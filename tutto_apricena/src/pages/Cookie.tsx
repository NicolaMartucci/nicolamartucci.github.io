import { Link } from 'react-router-dom';

export default function Cookie() {
  return (
    <main className="min-h-screen bg-[#F8F6F1]">
      <div className="bg-[#1A1A2E] pt-28 pb-14">
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          <h1 className="text-3xl md:text-4xl font-black text-white" style={{ fontFamily: "'Playfair Display', serif" }}>
            Cookie Policy
          </h1>
          <p className="text-white/50 mt-2">Ultimo aggiornamento: Giugno 2025</p>
        </div>
      </div>
      <div className="max-w-4xl mx-auto px-4 sm:px-6 py-12">
        <div className="bg-white rounded-2xl p-8 shadow-sm space-y-6 text-[#1C1C1C] text-sm leading-relaxed">
          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>
              1. Cosa sono i cookie
            </h2>
            <p>
              I cookie sono piccoli file di testo che i siti web salvano nel browser dell'utente durante la navigazione.
              Servono a far funzionare il sito correttamente, a migliorare l'esperienza utente e a raccogliere informazioni statistiche anonime.
            </p>
          </section>

          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>
              2. Tipologie di cookie utilizzati
            </h2>
            <div className="space-y-4">
              <div className="bg-[#F8F6F1] rounded-xl p-4">
                <h3 className="font-bold text-[#1A1A2E] mb-1">Cookie tecnici (necessari)</h3>
                <p className="text-[#6B7280]">
                  Essenziali per il funzionamento del sito. Non richiedono consenso. Gestiscono sessioni di navigazione, preferenze di lingua e funzionalità di base.
                </p>
              </div>
              <div className="bg-[#F8F6F1] rounded-xl p-4">
                <h3 className="font-bold text-[#1A1A2E] mb-1">Cookie analitici (Google Analytics)</h3>
                <p className="text-[#6B7280]">
                  Utilizziamo Google Analytics per raccogliere dati statistici anonimi sulla navigazione (pagine visitate, durata sessione, provenienza geografica).
                  I dati sono aggregati e non permettono l'identificazione del singolo utente. È possibile disattivare Google Analytics tramite il componente aggiuntivo del browser disponibile su{' '}
                  <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer" className="text-[#E8A838] hover:underline">
                    tools.google.com/dlpage/gaoptout
                  </a>.
                </p>
              </div>
            </div>
          </section>

          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>
              3. Cookie di terze parti
            </h2>
            <p>
              TuttoApricena può includere contenuti o link a siti di terze parti (es. Google Maps, social media). 
              Tali servizi possono installare i propri cookie, soggetti alle rispettive privacy policy.
              TuttoApricena non ha controllo su tali cookie.
            </p>
          </section>

          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>
              4. Come gestire i cookie
            </h2>
            <p className="mb-3">
              L'utente può gestire, disabilitare o eliminare i cookie tramite le impostazioni del proprio browser:
            </p>
            <ul className="space-y-1 list-disc list-inside text-[#6B7280]">
              <li>Chrome: Impostazioni → Privacy e sicurezza → Cookie</li>
              <li>Firefox: Opzioni → Privacy e sicurezza → Cookie e dati dei siti web</li>
              <li>Safari: Preferenze → Privacy → Gestisci dati sito web</li>
              <li>Edge: Impostazioni → Privacy, ricerca e servizi → Cookie</li>
            </ul>
            <p className="mt-3 text-[#6B7280]">
              La disabilitazione dei cookie potrebbe compromettere alcune funzionalità del sito.
            </p>
          </section>

          <section>
            <h2 className="text-lg font-bold text-[#1A1A2E] mb-3" style={{ fontFamily: "'Playfair Display', serif" }}>
              5. Aggiornamenti alla Cookie Policy
            </h2>
            <p>
              TuttoApricena si riserva il diritto di aggiornare questa Cookie Policy in qualsiasi momento.
              Le modifiche saranno pubblicate su questa pagina con la data di aggiornamento.
            </p>
          </section>

          <div className="pt-4 border-t border-[#EEEAE3]">
            <p className="text-[#6B7280]">
              Per ulteriori informazioni, consulta la nostra{' '}
              <Link to="/privacy" className="text-[#E8A838] hover:underline">Privacy Policy</Link>{' '}
              o contattaci a{' '}
              <a href="mailto:info@tuttoapricena.it" className="text-[#E8A838] hover:underline">
                info@tuttoapricena.it
              </a>
            </p>
          </div>
        </div>
      </div>
    </main>
  );
}
