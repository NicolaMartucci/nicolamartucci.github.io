import { Phone, MapPin, Clock, Moon, ExternalLink } from 'lucide-react';

const farmacieReali = [
  {
    id: 1,
    nome: "Farmacia Florio",
    indirizzo: "Corso Garibaldi, 94/96 — 71011 Apricena (FG)",
    telefono: "0882 641322",
    orario: "Lun-Sab: 08:00-13:00 / 16:30-20:30",
    orarioDomenica: "Turni a rotazione",
    descrizione: "Farmacia storica nel centro di Apricena, in Corso Garibaldi. Ampia gamma di farmaci, parafarmaci, prodotti cosmetici e fitoterapici.",
    notturno: false,
    colore: "#0284C7",
  },
  {
    id: 2,
    nome: "Farmacia dell'Incoronata",
    indirizzo: "Via Roma, 6 — 71011 Apricena (FG)",
    telefono: "0882 641126",
    orario: "Lun-Sab: 08:00-13:00 / 16:30-20:30",
    orarioDomenica: "Turni a rotazione",
    descrizione: "Farmacia in Via Roma, dedicata alla Madonna SS. Incoronata, patrona di Apricena. Farmaci da banco, prodotti per la salute e il benessere.",
    notturno: false,
    colore: "#7C3AED",
  },
  {
    id: 3,
    nome: "Farmacia della Luna",
    indirizzo: "Viale Aldo Moro, 134/C/8 — 71011 Apricena (FG)",
    telefono: "0882 643574",
    orario: "Lun-Sab: 08:30-13:00 / 16:30-20:30",
    orarioDomenica: "Turni a rotazione",
    descrizione: "Farmacia moderna in Viale Aldo Moro, nella zona residenziale di Apricena. Specializzata in prodotti cosmetici, omeopatici e integratori.",
    notturno: false,
    colore: "#EA580C",
  },
  {
    id: 4,
    nome: "Farmacia Matarese",
    indirizzo: "Viale Papa Giovanni XXIII, 33/A — 71011 Apricena (FG)",
    telefono: "0882 641134",
    orario: "Lun-Sab: 08:00-13:00 / 16:30-20:30",
    orarioDomenica: "Servizio notturno",
    descrizione: "Farmacia Matarese, con servizio notturno durante i turni. Situata in Viale Papa Giovanni XXIII. Completa gamma di prodotti farmaceutici e parafarmaceutici.",
    notturno: true,
    colore: "#16A34A",
  },
];

export default function Farmacie() {
  return (
    <main style={{ paddingTop: 72 }}>
      {/* Header */}
      <section style={{
        background: 'var(--color-primary)', padding: '64px 24px 56px',
        position: 'relative', overflow: 'hidden',
      }}>
        <div style={{
          position: 'absolute', top: '-20%', right: '-5%',
          width: 400, height: 400, borderRadius: '50%',
          background: 'radial-gradient(circle, rgba(22,163,74,0.07) 0%, transparent 70%)',
          pointerEvents: 'none',
        }} />
        <div style={{ maxWidth: 1280, margin: '0 auto', position: 'relative' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
            <div style={{ width: 24, height: 2, background: '#16A34A', borderRadius: 2 }} />
            <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.18em', color: '#16A34A', textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>
              Salute
            </span>
          </div>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(2rem, 4vw, 3rem)', fontWeight: 600, color: '#fff', letterSpacing: '-0.02em', marginBottom: 12 }}>
            Farmacie di Apricena
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.5)', fontSize: 15, fontFamily: 'var(--font-body)', maxWidth: 560, lineHeight: 1.7 }}>
            Le 4 farmacie presenti nel comune di Apricena (FG) con orari, indirizzi e numeri di telefono.
            I turni di reperibilità ruotano tra le farmacie: verifica il turno aggiornato sul sito ufficiale.
          </p>
          <a
            href="https://www.farmaciediturno.org/comune.asp?cod=71004"
            target="_blank"
            rel="noopener noreferrer"
            style={{
              display: 'inline-flex', alignItems: 'center', gap: 8,
              marginTop: 20, background: 'rgba(22,163,74,0.15)',
              border: '1px solid rgba(22,163,74,0.3)',
              color: '#4ade80', fontSize: 13, fontWeight: 700,
              fontFamily: 'var(--font-body)', padding: '10px 20px', borderRadius: 8,
              textDecoration: 'none',
            }}
          >
            <ExternalLink size={14} />
            Turni di oggi in tempo reale — farmaciediturno.org
          </a>
        </div>
      </section>

      {/* Cards farmacie */}
      <section style={{ padding: '64px 24px 80px', background: 'var(--color-surface)' }}>
        <div style={{ maxWidth: 1280, margin: '0 auto' }}>
          <div style={{
            display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: 24,
          }} className="farmacie-grid">
            {farmacieReali.map(farmacia => (
              <div key={farmacia.id} style={{
                background: '#fff', borderRadius: 20,
                padding: '28px 32px',
                border: '1px solid rgba(212,168,67,0.08)',
                boxShadow: '0 4px 24px rgba(14,14,24,0.06)',
                position: 'relative', overflow: 'hidden',
                transition: 'all 0.3s ease',
              }}
                onMouseEnter={e => {
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 12px 40px rgba(14,14,24,0.12)';
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(-4px)';
                }}
                onMouseLeave={e => {
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 4px 24px rgba(14,14,24,0.06)';
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                }}
              >
                {/* Top color bar */}
                <div style={{
                  position: 'absolute', top: 0, left: 0, right: 0, height: 3,
                  background: farmacia.colore, borderRadius: '20px 20px 0 0',
                }} />

                <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', marginBottom: 16 }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
                    <div style={{
                      width: 48, height: 48, borderRadius: 12,
                      background: farmacia.colore + '18',
                      border: `1px solid ${farmacia.colore}33`,
                      display: 'flex', alignItems: 'center', justifyContent: 'center',
                      fontSize: 22, flexShrink: 0,
                    }}>
                      ✚
                    </div>
                    <div>
                      <h3 style={{
                        fontFamily: 'var(--font-display)', fontSize: 20, fontWeight: 700,
                        color: 'var(--color-primary)', letterSpacing: '-0.01em', marginBottom: 2,
                      }}>
                        {farmacia.nome}
                      </h3>
                      {farmacia.notturno && (
                        <div style={{
                          display: 'inline-flex', alignItems: 'center', gap: 4,
                          background: 'rgba(22,163,74,0.08)',
                          border: '1px solid rgba(22,163,74,0.2)',
                          borderRadius: 50, padding: '2px 8px',
                        }}>
                          <Moon size={9} color="#16A34A" />
                          <span style={{ color: '#16A34A', fontSize: 10, fontWeight: 700, fontFamily: 'var(--font-body)', letterSpacing: '0.08em' }}>Servizio notturno</span>
                        </div>
                      )}
                    </div>
                  </div>
                  <a href={`tel:${farmacia.telefono.replace(/\s/g, '')}`} style={{
                    display: 'flex', alignItems: 'center', gap: 6,
                    background: farmacia.colore,
                    color: '#fff', fontSize: 12, fontWeight: 700,
                    fontFamily: 'var(--font-body)',
                    padding: '8px 14px', borderRadius: 8,
                    textDecoration: 'none', transition: 'all 0.2s',
                    boxShadow: `0 4px 12px ${farmacia.colore}44`,
                    flexShrink: 0,
                  }}>
                    <Phone size={12} />
                    {farmacia.telefono}
                  </a>
                </div>

                <p style={{
                  color: 'var(--color-text-muted)', fontSize: 13.5,
                  fontFamily: 'var(--font-body)', lineHeight: 1.7,
                  marginBottom: 20,
                }}>
                  {farmacia.descrizione}
                </p>

                <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
                  <div style={{ display: 'flex', alignItems: 'flex-start', gap: 10 }}>
                    <MapPin size={14} color={farmacia.colore} style={{ flexShrink: 0, marginTop: 2 }} />
                    <span style={{ color: 'var(--color-text)', fontSize: 13, fontFamily: 'var(--font-body)' }}>
                      {farmacia.indirizzo}
                    </span>
                  </div>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <Clock size={14} color={farmacia.colore} style={{ flexShrink: 0 }} />
                    <span style={{ color: 'var(--color-text)', fontSize: 13, fontFamily: 'var(--font-body)' }}>
                      {farmacia.orario}
                    </span>
                  </div>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <span style={{ width: 14, flexShrink: 0, textAlign: 'center', fontSize: 12 }}>📅</span>
                    <span style={{ color: 'var(--color-text-muted)', fontSize: 13, fontFamily: 'var(--font-body)' }}>
                      Domenica e festivi: {farmacia.orarioDomenica}
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Info box */}
          <div style={{
            marginTop: 40,
            background: 'rgba(212,168,67,0.06)',
            border: '1px solid rgba(212,168,67,0.2)',
            borderRadius: 14, padding: '20px 24px',
            display: 'flex', alignItems: 'flex-start', gap: 14,
          }}>
            <span style={{ fontSize: 20, flexShrink: 0 }}>ℹ️</span>
            <div>
              <div style={{ fontSize: 13, fontWeight: 700, color: 'var(--color-primary)', fontFamily: 'var(--font-body)', marginBottom: 4 }}>
                Come funzionano i turni a rotazione
              </div>
              <div style={{ fontSize: 13, color: 'var(--color-text-muted)', fontFamily: 'var(--font-body)', lineHeight: 1.7 }}>
                I turni di reperibilità notturna ruotano tra le 4 farmacie di Apricena. Per conoscere con certezza quale farmacia è di turno oggi, consulta il sito{' '}
                <a href="https://www.farmaciediturno.org/comune.asp?cod=71004" target="_blank" rel="noopener noreferrer" style={{ color: 'var(--color-accent-dark)', fontWeight: 700, textDecoration: 'underline' }}>
                  farmaciediturno.org
                </a>{' '}oppure controlla il cartello turni esposto all'esterno di ogni farmacia. Orari e turni sono soggetti a variazioni.
              </div>
            </div>
          </div>
        </div>
      </section>

      <style>{`
        @media (max-width: 767px) {
          .farmacie-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </main>
  );
}
