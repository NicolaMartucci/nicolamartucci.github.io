import { useParams, Link } from 'react-router-dom';
import { MapPin, Clock, Phone, Globe, ArrowLeft, ArrowRight, Star } from 'lucide-react';
import { locali } from '../data/mockData';

const tipoColors: Record<string, string> = {
  Ristorante: '#EA580C',
  Bar: '#E8A838',
  Alloggio: '#0284C7',
  Negozio: '#16A34A',
  Artigianato: '#7C3AED',
};

export default function SingleLocale() {
  const { slug } = useParams();
  const locale = locali.find(l => l.slug === slug);
  const suggeriti = locali.filter(l => l.slug !== slug).slice(0, 3);

  if (!locale) {
    return (
      <main style={{ paddingTop: 72, minHeight: '80vh', display: 'flex', alignItems: 'center', justifyContent: 'center', flexDirection: 'column', gap: 16, background: 'var(--color-surface)' }}>
        <div style={{ fontSize: 48 }}>🏠</div>
        <h1 style={{ fontFamily: 'var(--font-display)', fontSize: '2rem', color: 'var(--color-primary)' }}>Attività non trovata</h1>
        <Link to="/locali" style={{ color: 'var(--color-accent)', textDecoration: 'none', fontWeight: 600, fontFamily: 'var(--font-body)' }}>← Torna ai locali</Link>
      </main>
    );
  }

  const tipoColor = tipoColors[locale.tipo] || '#D4A843';

  return (
    <main style={{ paddingTop: 72, background: 'var(--color-surface)', minHeight: '100vh' }}>

      {/* ── Hero ── */}
      <div style={{ position: 'relative', height: 'clamp(300px, 48vh, 500px)', overflow: 'hidden' }}>
        <img
          src={locale.immagine}
          alt={locale.nome}
          style={{ width: '100%', height: '100%', objectFit: 'cover' }}
        />
        <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(14,14,24,0.92) 0%, rgba(14,14,24,0.45) 55%, rgba(14,14,24,0.1) 100%)' }} />
        {/* Color bar top */}
        <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: tipoColor }} />

        {/* Back */}
        <Link to="/locali" style={{
          position: 'absolute', top: 24, left: 24,
          display: 'inline-flex', alignItems: 'center', gap: 7,
          background: 'rgba(14,14,24,0.7)', backdropFilter: 'blur(12px)',
          color: '#fff', fontSize: 12, fontWeight: 700, fontFamily: 'var(--font-body)',
          padding: '8px 18px', borderRadius: 8, textDecoration: 'none',
          border: '1px solid rgba(255,255,255,0.12)',
        }}>
          <ArrowLeft size={13} /> Locali
        </Link>

        {/* Title */}
        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, padding: '32px 24px', maxWidth: 1000, margin: '0 auto' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 12 }}>
            <span style={{
              display: 'inline-block', background: tipoColor,
              color: '#fff', fontSize: 10, fontWeight: 800, letterSpacing: '0.14em',
              textTransform: 'uppercase', padding: '5px 14px', borderRadius: 50, fontFamily: 'var(--font-body)',
            }}>
              {locale.tipo}
            </span>
            {locale.inEvidenza && (
              <span style={{
                display: 'inline-flex', alignItems: 'center', gap: 5,
                background: 'rgba(212,168,67,0.9)', color: 'var(--color-primary)',
                fontSize: 9, fontWeight: 800, letterSpacing: '0.1em', textTransform: 'uppercase',
                padding: '5px 12px', borderRadius: 50, fontFamily: 'var(--font-body)',
              }}>
                <Star size={10} fill="currentColor" /> Consigliato
              </span>
            )}
          </div>
          <h1 style={{
            fontFamily: 'var(--font-display)', color: '#fff',
            fontSize: 'clamp(1.8rem, 4.5vw, 3rem)',
            fontWeight: 600, lineHeight: 1.1, letterSpacing: '-0.02em', marginBottom: 10,
          }}>
            {locale.nome}
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.65)', fontSize: 13, fontFamily: 'var(--font-body)', display: 'flex', alignItems: 'center', gap: 6 }}>
            <MapPin size={13} color={tipoColor} style={{ flexShrink: 0 }} />
            {locale.indirizzo}
          </p>
        </div>
      </div>

      {/* ── Contenuto principale ── */}
      <div style={{ maxWidth: 1000, margin: '0 auto', padding: '48px 24px 80px' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 320px', gap: 32 }} className="locale-layout">

          {/* ── Colonna sinistra ── */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>

            {/* Descrizione */}
            <div style={{
              background: '#fff', borderRadius: 20, padding: '32px 36px',
              boxShadow: '0 2px 20px rgba(14,14,24,0.06)',
              border: '1px solid rgba(212,168,67,0.08)',
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 18 }}>
                <div style={{ width: 20, height: 2, background: tipoColor, borderRadius: 2 }} />
                <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.15em', color: tipoColor, textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>Chi siamo</span>
              </div>
              <p style={{
                fontSize: 16, lineHeight: 1.85,
                color: 'var(--color-text)', fontFamily: 'var(--font-body)',
              }}>
                {locale.descrizione}
              </p>
            </div>

            {/* Orari */}
            <div style={{
              background: '#fff', borderRadius: 20, padding: '28px 32px',
              boxShadow: '0 2px 20px rgba(14,14,24,0.06)',
              border: '1px solid rgba(212,168,67,0.08)',
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16 }}>
                <div style={{ width: 20, height: 2, background: tipoColor, borderRadius: 2 }} />
                <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.15em', color: tipoColor, textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>Orari</span>
              </div>
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                <div style={{
                  width: 40, height: 40, borderRadius: 10, flexShrink: 0,
                  background: tipoColor + '18', border: `1px solid ${tipoColor}33`,
                  display: 'flex', alignItems: 'center', justifyContent: 'center',
                }}>
                  <Clock size={17} color={tipoColor} />
                </div>
                <p style={{ fontSize: 14.5, color: 'var(--color-text)', fontFamily: 'var(--font-body)', lineHeight: 1.65 }}>
                  {locale.orari}
                </p>
              </div>
            </div>

            {/* Proprietario CTA */}
            <div style={{
              background: 'var(--color-primary)', borderRadius: 20, padding: '24px 28px',
              display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 16,
              border: '1px solid rgba(212,168,67,0.15)',
            }}>
              <div>
                <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, fontFamily: 'var(--font-body)', marginBottom: 4 }}>Sei il proprietario?</p>
                <p style={{ color: '#fff', fontSize: 14, fontWeight: 600, fontFamily: 'var(--font-body)' }}>Aggiorna le informazioni del tuo locale</p>
              </div>
              <Link to="/contatti" style={{
                display: 'inline-flex', alignItems: 'center', gap: 7, flexShrink: 0,
                background: 'linear-gradient(135deg, var(--color-accent), var(--color-accent-light))',
                color: 'var(--color-primary)', fontSize: 12, fontWeight: 800,
                fontFamily: 'var(--font-body)',
                padding: '10px 20px', borderRadius: 8, textDecoration: 'none',
                boxShadow: '0 4px 14px rgba(212,168,67,0.3)',
              }}>
                Contattaci <ArrowRight size={13} />
              </Link>
            </div>
          </div>

          {/* ── Colonna destra: info ── */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>

            {/* Card info contatti */}
            <div style={{
              background: '#fff', borderRadius: 20, padding: '24px',
              boxShadow: '0 2px 20px rgba(14,14,24,0.06)',
              border: '1px solid rgba(212,168,67,0.08)',
            }}>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>

                {/* Indirizzo */}
                <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                  <div style={{
                    width: 40, height: 40, borderRadius: 10, flexShrink: 0,
                    background: tipoColor + '18', border: `1px solid ${tipoColor}33`,
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                  }}>
                    <MapPin size={16} color={tipoColor} />
                  </div>
                  <div>
                    <p style={{ fontSize: 10, fontWeight: 700, color: 'var(--color-text-muted)', textTransform: 'uppercase', letterSpacing: '0.1em', fontFamily: 'var(--font-body)', marginBottom: 3 }}>Indirizzo</p>
                    <p style={{ fontSize: 13.5, color: 'var(--color-text)', fontFamily: 'var(--font-body)', lineHeight: 1.5 }}>{locale.indirizzo}</p>
                  </div>
                </div>

                <div style={{ height: 1, background: 'rgba(212,168,67,0.1)' }} />

                {/* Telefono */}
                {locale.telefono && (
                  <>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                      <div style={{
                        width: 40, height: 40, borderRadius: 10, flexShrink: 0,
                        background: tipoColor + '18', border: `1px solid ${tipoColor}33`,
                        display: 'flex', alignItems: 'center', justifyContent: 'center',
                      }}>
                        <Phone size={16} color={tipoColor} />
                      </div>
                      <div>
                        <p style={{ fontSize: 10, fontWeight: 700, color: 'var(--color-text-muted)', textTransform: 'uppercase', letterSpacing: '0.1em', fontFamily: 'var(--font-body)', marginBottom: 3 }}>Telefono</p>
                        <a href={`tel:${locale.telefono.replace(/\s/g, '')}`} style={{ fontSize: 14, color: tipoColor, fontWeight: 700, fontFamily: 'var(--font-body)', textDecoration: 'none' }}>
                          {locale.telefono}
                        </a>
                      </div>
                    </div>
                    <div style={{ height: 1, background: 'rgba(212,168,67,0.1)' }} />
                  </>
                )}

                {/* Sito web */}
                {locale.sitoWeb && (
                  <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                    <div style={{
                      width: 40, height: 40, borderRadius: 10, flexShrink: 0,
                      background: tipoColor + '18', border: `1px solid ${tipoColor}33`,
                      display: 'flex', alignItems: 'center', justifyContent: 'center',
                    }}>
                      <Globe size={16} color={tipoColor} />
                    </div>
                    <div>
                      <p style={{ fontSize: 10, fontWeight: 700, color: 'var(--color-text-muted)', textTransform: 'uppercase', letterSpacing: '0.1em', fontFamily: 'var(--font-body)', marginBottom: 3 }}>Sito web</p>
                      <a href={locale.sitoWeb} target="_blank" rel="noopener noreferrer" style={{ fontSize: 13, color: tipoColor, fontWeight: 700, fontFamily: 'var(--font-body)', textDecoration: 'none' }}>
                        Visita il sito →
                      </a>
                    </div>
                  </div>
                )}
              </div>
            </div>

            {/* Chiama pulsante */}
            {locale.telefono && (
              <a href={`tel:${locale.telefono.replace(/\s/g, '')}`} style={{
                display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8,
                background: tipoColor,
                color: '#fff', fontSize: 14, fontWeight: 800,
                fontFamily: 'var(--font-body)',
                padding: '14px 24px', borderRadius: 12, textDecoration: 'none',
                boxShadow: `0 6px 20px ${tipoColor}44`,
                transition: 'all 0.2s',
              }}>
                <Phone size={15} />
                Chiama ora
              </a>
            )}

            {/* Mappa placeholder */}
            <div style={{
              background: '#fff', borderRadius: 20,
              overflow: 'hidden', height: 180,
              boxShadow: '0 2px 20px rgba(14,14,24,0.06)',
              border: '1px solid rgba(212,168,67,0.08)',
              position: 'relative', display: 'flex', alignItems: 'center', justifyContent: 'center',
              flexDirection: 'column', gap: 8,
              background: `linear-gradient(135deg, ${tipoColor}10, ${tipoColor}05)`,
            }}>
              <div style={{ fontSize: 32 }}>📍</div>
              <p style={{ fontSize: 12, color: 'var(--color-text-muted)', fontFamily: 'var(--font-body)', textAlign: 'center', padding: '0 16px' }}>
                {locale.indirizzo}
              </p>
              <a
                href={`https://www.google.com/maps/search/${encodeURIComponent(locale.nome + ' ' + locale.indirizzo)}`}
                target="_blank" rel="noopener noreferrer"
                style={{
                  fontSize: 11, color: tipoColor, fontWeight: 700,
                  fontFamily: 'var(--font-body)', textDecoration: 'none',
                  border: `1px solid ${tipoColor}44`, borderRadius: 6, padding: '5px 12px',
                }}
              >
                Apri su Google Maps →
              </a>
            </div>
          </div>
        </div>

        {/* ── Suggeriti ── */}
        {suggeriti.length > 0 && (
          <div style={{ marginTop: 56 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 8 }}>
              <div style={{ width: 24, height: 2, background: 'var(--color-accent)', borderRadius: 2 }} />
              <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.18em', color: 'var(--color-accent)', textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>Scopri altri</span>
            </div>
            <h3 style={{ fontFamily: 'var(--font-display)', fontSize: '1.6rem', fontWeight: 600, color: 'var(--color-primary)', letterSpacing: '-0.02em', marginBottom: 28 }}>
              Potrebbero interessarti
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 20 }} className="suggeriti-grid">
              {suggeriti.map(l => {
                const tc = tipoColors[l.tipo] || '#D4A843';
                return (
                  <Link key={l.id} to={`/locali/${l.slug}`} style={{
                    background: '#fff', borderRadius: 16, overflow: 'hidden', textDecoration: 'none',
                    boxShadow: '0 2px 16px rgba(14,14,24,0.07)',
                    border: '1px solid rgba(212,168,67,0.06)',
                    transition: 'all 0.3s ease',
                  }}
                    onMouseEnter={e => {
                      (e.currentTarget as HTMLElement).style.transform = 'translateY(-4px)';
                      (e.currentTarget as HTMLElement).style.boxShadow = '0 12px 32px rgba(14,14,24,0.12)';
                    }}
                    onMouseLeave={e => {
                      (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                      (e.currentTarget as HTMLElement).style.boxShadow = '0 2px 16px rgba(14,14,24,0.07)';
                    }}
                  >
                    <div style={{ height: 140, overflow: 'hidden', position: 'relative' }}>
                      <img src={l.immagine} alt={l.nome} style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.4s ease' }} />
                      <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: tc }} />
                    </div>
                    <div style={{ padding: '14px 16px 18px' }}>
                      <span style={{ display: 'inline-block', background: tc + '18', border: `1px solid ${tc}33`, color: tc, fontSize: 9, fontWeight: 800, letterSpacing: '0.1em', textTransform: 'uppercase', padding: '3px 10px', borderRadius: 50, fontFamily: 'var(--font-body)', marginBottom: 8 }}>
                        {l.tipo}
                      </span>
                      <h4 style={{ fontFamily: 'var(--font-display)', fontSize: 15, fontWeight: 600, color: 'var(--color-primary)', lineHeight: 1.3 }}>
                        {l.nome}
                      </h4>
                      <p style={{ color: 'var(--color-text-muted)', fontSize: 11, marginTop: 6, fontFamily: 'var(--font-body)', display: 'flex', alignItems: 'center', gap: 4 }}>
                        <MapPin size={10} /> {l.indirizzo.split('—')[0].trim()}
                      </p>
                    </div>
                  </Link>
                );
              })}
            </div>
          </div>
        )}
      </div>

      <style>{`
        @media (max-width: 900px) { .locale-layout { grid-template-columns: 1fr !important; } }
        @media (max-width: 640px) { .suggeriti-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
