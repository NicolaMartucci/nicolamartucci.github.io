import { Link } from 'react-router-dom';
import { Calendar, MapPin, Clock, ArrowRight } from 'lucide-react';
import { eventi } from '../../data/mockData';

function formatDateShort(dateStr: string) {
  const d = new Date(dateStr);
  return {
    day: d.toLocaleDateString('it-IT', { day: '2-digit' }),
    month: d.toLocaleDateString('it-IT', { month: 'short' }).toUpperCase(),
  };
}

const categoryColors: Record<string, string> = {
  Religioso: '#7C3AED',
  Sport: '#16A34A',
  Gastronomia: '#EA580C',
  Cultura: '#0284C7',
  Musica: '#D4A843',
  Turismo: '#0891B2',
};

export default function EventiSection() {
  const prossimi = eventi.slice(0, 4);

  return (
    <section style={{
      padding: '100px 0',
      background: 'var(--color-primary)',
      position: 'relative',
      overflow: 'hidden',
    }}>
      {/* Background decoration */}
      <div style={{
        position: 'absolute', top: '-20%', right: '-10%',
        width: 600, height: 600,
        borderRadius: '50%',
        background: 'radial-gradient(circle, rgba(212,168,67,0.06) 0%, transparent 70%)',
        pointerEvents: 'none',
      }} />
      <div style={{
        position: 'absolute', bottom: '-10%', left: '-5%',
        width: 400, height: 400,
        borderRadius: '50%',
        background: 'radial-gradient(circle, rgba(212,168,67,0.04) 0%, transparent 70%)',
        pointerEvents: 'none',
      }} />

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px', position: 'relative', zIndex: 1 }}>

        {/* Header */}
        <div style={{
          display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between',
          marginBottom: 52, gap: 16, flexWrap: 'wrap',
        }}>
          <div>
            <span style={{
              display: 'inline-flex', alignItems: 'center', gap: 8,
              fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700,
              letterSpacing: '0.18em', textTransform: 'uppercase',
              color: 'var(--color-accent)', marginBottom: 10,
            }}>
              <span style={{ display: 'block', width: 24, height: 2, background: 'var(--color-accent)', borderRadius: 2 }} />
              In programma
            </span>
            <h2 style={{
              fontFamily: 'var(--font-display)',
              fontSize: 'clamp(1.8rem, 3.5vw, 2.8rem)',
              fontWeight: 600, color: '#fff',
              lineHeight: 1.1, letterSpacing: '-0.02em',
            }}>
              Prossimi Eventi
            </h2>
          </div>
          <Link to="/eventi" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            color: 'rgba(255,255,255,0.5)', fontSize: 13,
            fontWeight: 600, fontFamily: 'var(--font-body)',
            textDecoration: 'none', transition: 'color 0.2s',
            borderBottom: '1px solid rgba(255,255,255,0.15)',
            paddingBottom: 2,
          }}
            onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
            onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.5)'}
          >
            Tutti gli eventi <ArrowRight size={14} />
          </Link>
        </div>

        {/* Cards grid */}
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(4, 1fr)',
          gap: 20,
        }} className="eventi-grid">
          {prossimi.map((evento) => {
            const startDate = formatDateShort(evento.dataInizio);
            const isMultiDay = evento.dataInizio !== evento.dataFine;
            const endDate = isMultiDay ? formatDateShort(evento.dataFine) : null;
            const catColor = categoryColors[evento.categoria] || '#D4A843';

            return (
              <Link
                key={evento.id}
                to={`/eventi/${evento.slug}`}
                style={{
                  display: 'flex', flexDirection: 'column',
                  borderRadius: 16, overflow: 'hidden',
                  textDecoration: 'none',
                  background: 'rgba(255,255,255,0.04)',
                  border: '1px solid rgba(255,255,255,0.08)',
                  transition: 'all 0.35s ease',
                  cursor: 'pointer',
                }}
                onMouseEnter={e => {
                  (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,0.08)';
                  (e.currentTarget as HTMLElement).style.borderColor = 'rgba(212,168,67,0.3)';
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(-6px)';
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 20px 48px rgba(0,0,0,0.3)';
                }}
                onMouseLeave={e => {
                  (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,0.04)';
                  (e.currentTarget as HTMLElement).style.borderColor = 'rgba(255,255,255,0.08)';
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                  (e.currentTarget as HTMLElement).style.boxShadow = 'none';
                }}
              >
                {/* Image */}
                <div style={{ position: 'relative', height: 180, overflow: 'hidden' }}>
                  <img
                    src={evento.immagine}
                    alt={evento.titolo}
                    style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                  />
                  <div style={{
                    position: 'absolute', inset: 0,
                    background: 'linear-gradient(to top, rgba(14,14,24,0.7) 0%, transparent 60%)',
                  }} />
                  {/* Date badge */}
                  <div style={{
                    position: 'absolute', top: 14, left: 14,
                    background: 'rgba(14,14,24,0.85)',
                    backdropFilter: 'blur(8px)',
                    borderRadius: 10, padding: '8px 12px',
                    textAlign: 'center',
                    border: '1px solid rgba(212,168,67,0.2)',
                  }}>
                    <div style={{
                      fontFamily: 'var(--font-display)',
                      fontSize: 22, fontWeight: 700,
                      color: 'var(--color-accent)', lineHeight: 1,
                    }}>
                      {startDate.day}
                    </div>
                    <div style={{
                      fontFamily: 'var(--font-body)',
                      fontSize: 9, fontWeight: 700,
                      color: 'rgba(255,255,255,0.5)',
                      letterSpacing: '0.1em',
                    }}>
                      {startDate.month}
                    </div>
                    {endDate && (
                      <div style={{
                        fontFamily: 'var(--font-body)',
                        fontSize: 8, color: 'rgba(255,255,255,0.3)',
                        marginTop: 2,
                      }}>
                        → {endDate.day} {endDate.month}
                      </div>
                    )}
                  </div>
                  {/* Category badge */}
                  <div style={{
                    position: 'absolute', top: 14, right: 14,
                    background: catColor + '22',
                    border: `1px solid ${catColor}55`,
                    borderRadius: 50, padding: '3px 10px',
                  }}>
                    <span style={{
                      color: catColor, fontSize: 9, fontWeight: 700,
                      letterSpacing: '0.1em', textTransform: 'uppercase',
                      fontFamily: 'var(--font-body)',
                    }}>
                      {evento.categoria}
                    </span>
                  </div>
                </div>

                {/* Content */}
                <div style={{ padding: '18px 20px 20px', flex: 1, display: 'flex', flexDirection: 'column', gap: 10 }}>
                  <h3 style={{
                    fontFamily: 'var(--font-display)',
                    fontSize: 17, fontWeight: 600,
                    color: '#fff', lineHeight: 1.3,
                    letterSpacing: '-0.01em',
                  }} className="line-clamp-2">
                    {evento.titolo}
                  </h3>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: 6, marginTop: 'auto' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8, color: 'rgba(255,255,255,0.4)', fontSize: 11, fontFamily: 'var(--font-body)' }}>
                      <Clock size={11} color="var(--color-accent)" />
                      {evento.orario}
                    </div>
                    <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8, color: 'rgba(255,255,255,0.4)', fontSize: 11, fontFamily: 'var(--font-body)' }}>
                      <MapPin size={11} color="var(--color-accent)" style={{ flexShrink: 0, marginTop: 1 }} />
                      <span className="line-clamp-2">{evento.luogo}</span>
                    </div>
                  </div>
                </div>
              </Link>
            );
          })}
        </div>

        {/* Bottom CTA */}
        <div style={{ textAlign: 'center', marginTop: 40 }}>
          <Link to="/eventi" style={{
            display: 'inline-flex', alignItems: 'center', gap: 10,
            background: 'transparent',
            border: '1.5px solid rgba(212,168,67,0.35)',
            color: 'var(--color-accent)',
            fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700,
            padding: '13px 32px', borderRadius: 50,
            textDecoration: 'none', transition: 'all 0.3s ease',
          }}
            onMouseEnter={e => {
              (e.currentTarget as HTMLElement).style.background = 'rgba(212,168,67,0.08)';
              (e.currentTarget as HTMLElement).style.borderColor = 'var(--color-accent)';
            }}
            onMouseLeave={e => {
              (e.currentTarget as HTMLElement).style.background = 'transparent';
              (e.currentTarget as HTMLElement).style.borderColor = 'rgba(212,168,67,0.35)';
            }}
          >
            Calendario completo degli eventi <ArrowRight size={15} />
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 1023px) { .eventi-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 640px) { .eventi-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </section>
  );
}
