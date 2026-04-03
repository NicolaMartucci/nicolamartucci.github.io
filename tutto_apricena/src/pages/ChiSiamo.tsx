import { Link } from 'react-router-dom';
import { Heart, Target, Newspaper, MapPin } from 'lucide-react';

export default function ChiSiamo() {
  return (
    <main style={{ minHeight: '100vh', background: 'var(--color-surface)', paddingTop: 72 }}>
      {/* Header */}
      <div style={{ background: 'var(--color-primary)', padding: '48px 24px 60px', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', top: -40, right: -40, width: 280, height: 280, borderRadius: '50%', background: 'rgba(232,168,56,0.08)', pointerEvents: 'none' }} />
        <div style={{ maxWidth: 900, margin: '0 auto', position: 'relative', zIndex: 1 }}>
          <span style={{ display: 'block', color: 'var(--color-accent)', fontSize: 11, fontWeight: 700, letterSpacing: '0.18em', textTransform: 'uppercase', marginBottom: 8 }}>Il progetto</span>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(2rem, 5vw, 3rem)', fontWeight: 900, color: '#fff', marginBottom: 14 }}>
            Chi siamo
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 17, lineHeight: 1.7, maxWidth: 580 }}>
            TuttoApricena è un portale informativo indipendente dedicato alla città di Apricena, nella provincia di Foggia, in Puglia.
          </p>
        </div>
      </div>

      <div style={{ maxWidth: 900, margin: '0 auto', padding: '52px 24px 80px' }}>
        {/* Mission */}
        <div style={{ background: '#fff', borderRadius: 20, padding: '36px 40px', boxShadow: '0 4px 24px rgba(26,26,46,0.08)', marginBottom: 24 }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 14, marginBottom: 20 }}>
            <div style={{ width: 50, height: 50, borderRadius: 14, background: 'rgba(232,168,56,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
              <Target size={24} color="var(--color-accent)" />
            </div>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.5rem', fontWeight: 800, color: 'var(--color-primary)' }}>La nostra missione</h2>
          </div>
          <p style={{ color: 'var(--color-text-muted)', lineHeight: 1.8, fontSize: 15, marginBottom: 14 }}>
            Vogliamo essere il punto di riferimento digitale per tutti coloro che vivono, lavorano o visitano Apricena.
            Un portale che raccoglie notizie, eventi, servizi e attività locali in un unico posto, accessibile a tutti e aggiornato ogni giorno.
          </p>
          <p style={{ color: 'var(--color-text-muted)', lineHeight: 1.8, fontSize: 15 }}>
            Non siamo un'istituzione, non siamo un organo di stampa ufficiale. Siamo un progetto nato dall'amore per la nostra città,
            con l'obiettivo di rendere l'informazione locale più semplice, moderna e accessibile.
          </p>
        </div>

        {/* Values */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 16, marginBottom: 24 }} className="values-grid">
          {[
            { icon: <Newspaper size={22} />, titolo: 'Informazione', testo: 'Raccogliamo notizie da fonti affidabili, sempre citate. Non produciamo contenuti giornalistici originali.' },
            { icon: <Heart size={22} />, titolo: 'Comunità', testo: 'Siamo un progetto di e per la comunità apricenese. Cittadini, turisti e attività locali al centro di tutto.' },
            { icon: <MapPin size={22} />, titolo: 'Territorio', testo: 'Apricena, il Gargano, la provincia di Foggia: il nostro territorio è la nostra identità.' },
          ].map((v, i) => (
            <div key={i} style={{ background: 'var(--color-primary)', borderRadius: 18, padding: '28px 24px', textAlign: 'center' }}>
              <div style={{ width: 50, height: 50, borderRadius: 14, background: 'rgba(232,168,56,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'var(--color-accent)', margin: '0 auto 16px' }}>
                {v.icon}
              </div>
              <h3 style={{ fontFamily: 'var(--font-display)', color: '#fff', fontSize: 18, fontWeight: 700, marginBottom: 8 }}>{v.titolo}</h3>
              <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 13, lineHeight: 1.7 }}>{v.testo}</p>
            </div>
          ))}
        </div>

        {/* Editorial note */}
        <div style={{ background: '#fff', borderRadius: 20, padding: '30px 36px', boxShadow: '0 4px 24px rgba(26,26,46,0.08)', marginBottom: 28 }}>
          <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-primary)', marginBottom: 14 }}>
            Nota editoriale
          </h2>
          <p style={{ color: 'var(--color-text-muted)', lineHeight: 1.8, fontSize: 15 }}>
            Le notizie pubblicate su TuttoApricena provengono da fonti terze (siti istituzionali, testate giornalistiche, comunicati ufficiali).
            Ogni articolo riporta chiaramente la fonte originale. Non ci assumiamo responsabilità per contenuti prodotti da terzi.
          </p>
        </div>

        {/* CTA */}
        <div style={{ textAlign: 'center' }}>
          <Link to="/contatti" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            background: 'var(--color-accent)', color: 'var(--color-primary)',
            fontWeight: 700, fontSize: 15, padding: '14px 32px', borderRadius: 50, textDecoration: 'none',
          }}>
            Contattaci
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 720px) { .values-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
