import { useParams, Link } from 'react-router-dom';
import { Calendar, MapPin, Clock, ArrowLeft, ArrowRight } from 'lucide-react';
import { eventi } from '../data/mockData';

const categoryColors: Record<string, string> = {
  Religioso: '#7C3AED',
  Sport: '#16A34A',
  Gastronomia: '#EA580C',
  Cultura: '#0284C7',
  Musica: '#D4A843',
  Turismo: '#0891B2',
};

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('it-IT', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}
function formatDateShort(dateStr: string) {
  return {
    day: new Date(dateStr).getDate(),
    month: new Date(dateStr).toLocaleDateString('it-IT', { month: 'long' }),
    year: new Date(dateStr).getFullYear(),
  };
}

export default function SingleEvento() {
  const { slug } = useParams();
  const evento = eventi.find(e => e.slug === slug);
  const altriEventi = eventi.filter(e => e.slug !== slug).slice(0, 3);

  if (!evento) {
    return (
      <main style={{ paddingTop: 72, minHeight: '80vh', display: 'flex', alignItems: 'center', justifyContent: 'center', flexDirection: 'column', gap: 16, background: 'var(--color-surface)' }}>
        <div style={{ fontSize: 48 }}>📅</div>
        <h1 style={{ fontFamily: 'var(--font-display)', fontSize: '2rem', color: 'var(--color-primary)' }}>Evento non trovato</h1>
        <Link to="/eventi" style={{ color: 'var(--color-accent)', textDecoration: 'none', fontWeight: 600, fontFamily: 'var(--font-body)' }}>← Torna agli eventi</Link>
      </main>
    );
  }

  const catColor = categoryColors[evento.categoria] || '#D4A843';
  const isMultiDay = evento.dataInizio !== evento.dataFine;
  const startDate = formatDateShort(evento.dataInizio);
  const endDate = isMultiDay ? formatDateShort(evento.dataFine) : null;

  return (
    <main style={{ paddingTop: 72, background: 'var(--color-surface)', minHeight: '100vh' }}>

      {/* ── Hero ── */}
      <div style={{ position: 'relative', height: 'clamp(320px, 52vh, 520px)', overflow: 'hidden' }}>
        <img src={evento.immagine} alt={evento.titolo} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
        <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(14,14,24,0.94) 0%, rgba(14,14,24,0.5) 55%, rgba(14,14,24,0.1) 100%)' }} />
        <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: catColor }} />

        <Link to="/eventi" style={{
          position: 'absolute', top: 24, left: 24,
          display: 'inline-flex', alignItems: 'center', gap: 7,
          background: 'rgba(14,14,24,0.7)', backdropFilter: 'blur(12px)',
          color: '#fff', fontSize: 12, fontWeight: 700, fontFamily: 'var(--font-body)',
          padding: '8px 18px', borderRadius: 8, textDecoration: 'none',
          border: '1px solid rgba(255,255,255,0.12)',
        }}>
          <ArrowLeft size={13} /> Eventi
        </Link>

        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, padding: '32px 24px', maxWidth: 900, margin: '0 auto' }}>
          <span style={{
            display: 'inline-block', background: catColor, color: '#fff',
            fontSize: 10, fontWeight: 800, letterSpacing: '0.14em', textTransform: 'uppercase',
            padding: '5px 14px', borderRadius: 50, fontFamily: 'var(--font-body)', marginBottom: 14,
          }}>
            {evento.categoria}
          </span>
          <h1 style={{
            fontFamily: 'var(--font-display)', color: '#fff',
            fontSize: 'clamp(1.7rem, 4vw, 2.8rem)',
            fontWeight: 600, lineHeight: 1.15, letterSpacing: '-0.02em',
          }}>
            {evento.titolo}
          </h1>
        </div>
      </div>

      {/* ── Contenuto ── */}
      <div style={{ maxWidth: 960, margin: '0 auto', padding: '48px 24px 80px' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 300px', gap: 32 }} className="evento-layout">

          {/* Sinistra */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
            <div style={{
              background: '#fff', borderRadius: 20, padding: '32px 36px',
              boxShadow: '0 2px 20px rgba(14,14,24,0.06)',
              border: '1px solid rgba(212,168,67,0.08)',
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 18 }}>
                <div style={{ width: 20, height: 2, background: catColor, borderRadius: 2 }} />
                <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.15em', color: catColor, textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>Descrizione</span>
              </div>
              <p style={{ fontSize: 16, lineHeight: 1.85, color: 'var(--color-text)', fontFamily: 'var(--font-body)' }}>
                {evento.descrizione}
              </p>
            </div>

            {evento.linkEsterno && (
              <a href={evento.linkEsterno} target="_blank" rel="noopener noreferrer" style={{
                display: 'inline-flex', alignItems: 'center', gap: 8,
                background: catColor, color: '#fff', fontSize: 13, fontWeight: 800,
                fontFamily: 'var(--font-body)', padding: '14px 24px', borderRadius: 12,
                textDecoration: 'none', width: 'fit-content',
              }}>
                Maggiori informazioni <ArrowRight size={14} />
              </a>
            )}
          </div>

          {/* Destra — card info */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>

            {/* Date box */}
            <div style={{
              background: 'var(--color-primary)', borderRadius: 20, padding: '24px',
              border: '1px solid rgba(212,168,67,0.15)',
              boxShadow: '0 4px 20px rgba(14,14,24,0.15)',
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 16 }}>
                <div style={{ width: 20, height: 2, background: catColor, borderRadius: 2 }} />
                <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.15em', color: catColor, textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>Date</span>
              </div>

              <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
                <div style={{
                  textAlign: 'center',
                  background: 'rgba(255,255,255,0.06)',
                  border: `1px solid ${catColor}33`,
                  borderRadius: 12, padding: '12px 16px', minWidth: 72,
                }}>
                  <div style={{ fontFamily: 'var(--font-display)', fontSize: 36, fontWeight: 700, color: catColor, lineHeight: 1 }}>
                    {startDate.day}
                  </div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: 'rgba(255,255,255,0.5)', marginTop: 4, letterSpacing: '0.05em' }}>
                    {startDate.month}
                  </div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, color: 'rgba(255,255,255,0.3)' }}>
                    {startDate.year}
                  </div>
                </div>

                {endDate && (
                  <>
                    <div style={{ color: 'rgba(255,255,255,0.3)', fontSize: 18, fontWeight: 300 }}>→</div>
                    <div style={{
                      textAlign: 'center',
                      background: 'rgba(255,255,255,0.06)',
                      border: `1px solid ${catColor}33`,
                      borderRadius: 12, padding: '12px 16px', minWidth: 72,
                    }}>
                      <div style={{ fontFamily: 'var(--font-display)', fontSize: 36, fontWeight: 700, color: catColor, lineHeight: 1 }}>
                        {endDate.day}
                      </div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: 'rgba(255,255,255,0.5)', marginTop: 4 }}>
                        {endDate.month}
                      </div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, color: 'rgba(255,255,255,0.3)' }}>
                        {endDate.year}
                      </div>
                    </div>
                  </>
                )}
              </div>
            </div>

            {/* Dettagli */}
            <div style={{
              background: '#fff', borderRadius: 20, padding: '20px 22px',
              boxShadow: '0 2px 16px rgba(14,14,24,0.06)',
              border: '1px solid rgba(212,168,67,0.08)',
              display: 'flex', flexDirection: 'column', gap: 14,
            }}>
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                <div style={{ width: 36, height: 36, borderRadius: 8, background: catColor + '18', border: `1px solid ${catColor}33`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <Clock size={15} color={catColor} />
                </div>
                <div>
                  <p style={{ fontSize: 10, fontWeight: 700, color: 'var(--color-text-muted)', textTransform: 'uppercase', letterSpacing: '0.1em', fontFamily: 'var(--font-body)', marginBottom: 2 }}>Orario</p>
                  <p style={{ fontSize: 14, color: 'var(--color-text)', fontFamily: 'var(--font-body)', fontWeight: 600 }}>{evento.orario}</p>
                </div>
              </div>
              <div style={{ height: 1, background: 'rgba(212,168,67,0.1)' }} />
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: 12 }}>
                <div style={{ width: 36, height: 36, borderRadius: 8, background: catColor + '18', border: `1px solid ${catColor}33`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <MapPin size={15} color={catColor} />
                </div>
                <div>
                  <p style={{ fontSize: 10, fontWeight: 700, color: 'var(--color-text-muted)', textTransform: 'uppercase', letterSpacing: '0.1em', fontFamily: 'var(--font-body)', marginBottom: 2 }}>Luogo</p>
                  <p style={{ fontSize: 13.5, color: 'var(--color-text)', fontFamily: 'var(--font-body)', lineHeight: 1.5 }}>{evento.luogo}</p>
                </div>
              </div>
            </div>

            {/* Aggiungi al calendario */}
            <a
              href={`https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(evento.titolo)}&dates=${evento.dataInizio.replace(/-/g, '')}/${evento.dataFine.replace(/-/g, '')}&details=${encodeURIComponent(evento.descrizione)}&location=${encodeURIComponent(evento.luogo)}`}
              target="_blank" rel="noopener noreferrer"
              style={{
                display: 'flex', alignItems: 'center', justifyContent: 'center', gap: 8,
                border: `1.5px solid ${catColor}44`, borderRadius: 12,
                color: catColor, fontSize: 12, fontWeight: 700, fontFamily: 'var(--font-body)',
                padding: '12px 20px', textDecoration: 'none', transition: 'all 0.2s',
                background: catColor + '08',
              }}
            >
              <Calendar size={14} />
              Aggiungi a Google Calendar
            </a>
          </div>
        </div>

        {/* ── Altri eventi ── */}
        {altriEventi.length > 0 && (
          <div style={{ marginTop: 56 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 8 }}>
              <div style={{ width: 24, height: 2, background: 'var(--color-accent)', borderRadius: 2 }} />
              <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.18em', color: 'var(--color-accent)', textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>In calendario</span>
            </div>
            <h3 style={{ fontFamily: 'var(--font-display)', fontSize: '1.6rem', fontWeight: 600, color: 'var(--color-primary)', letterSpacing: '-0.02em', marginBottom: 28 }}>
              Altri eventi
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 20 }} className="altri-eventi-grid">
              {altriEventi.map(e => {
                const cc = categoryColors[e.categoria] || '#D4A843';
                const sd = formatDateShort(e.dataInizio);
                return (
                  <Link key={e.id} to={`/eventi/${e.slug}`} style={{
                    background: '#fff', borderRadius: 16, overflow: 'hidden',
                    textDecoration: 'none', display: 'flex', flexDirection: 'column',
                    boxShadow: '0 2px 16px rgba(14,14,24,0.07)',
                    border: '1px solid rgba(212,168,67,0.06)',
                    transition: 'all 0.3s ease',
                  }}
                    onMouseEnter={e2 => {
                      (e2.currentTarget as HTMLElement).style.transform = 'translateY(-4px)';
                      (e2.currentTarget as HTMLElement).style.boxShadow = '0 12px 32px rgba(14,14,24,0.12)';
                    }}
                    onMouseLeave={e2 => {
                      (e2.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                      (e2.currentTarget as HTMLElement).style.boxShadow = '0 2px 16px rgba(14,14,24,0.07)';
                    }}
                  >
                    <div style={{ height: 140, overflow: 'hidden', position: 'relative' }}>
                      <img src={e.immagine} alt={e.titolo} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                      <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: cc }} />
                      <div style={{
                        position: 'absolute', top: 12, left: 12,
                        background: 'rgba(14,14,24,0.85)', borderRadius: 8,
                        padding: '6px 10px', textAlign: 'center',
                        border: `1px solid ${cc}44`,
                      }}>
                        <div style={{ fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 700, color: cc, lineHeight: 1 }}>{sd.day}</div>
                        <div style={{ fontFamily: 'var(--font-body)', fontSize: 8, color: 'rgba(255,255,255,0.5)', letterSpacing: '0.08em' }}>{sd.month.slice(0,3).toUpperCase()}</div>
                      </div>
                    </div>
                    <div style={{ padding: '14px 16px 18px' }}>
                      <span style={{ display: 'inline-block', background: cc + '18', border: `1px solid ${cc}33`, color: cc, fontSize: 9, fontWeight: 800, letterSpacing: '0.1em', textTransform: 'uppercase', padding: '3px 10px', borderRadius: 50, fontFamily: 'var(--font-body)', marginBottom: 8 }}>
                        {e.categoria}
                      </span>
                      <h4 style={{ fontFamily: 'var(--font-display)', fontSize: 14, fontWeight: 600, color: 'var(--color-primary)', lineHeight: 1.3 }} className="line-clamp-2">
                        {e.titolo}
                      </h4>
                    </div>
                  </Link>
                );
              })}
            </div>
          </div>
        )}
      </div>

      <style>{`
        @media (max-width: 768px) { .evento-layout { grid-template-columns: 1fr !important; } .altri-eventi-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
