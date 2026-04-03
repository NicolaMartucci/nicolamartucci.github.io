import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';

const highlights = [
  {
    number: '2°',
    label: 'Polo marmifero d\'Italia',
    desc: 'Dopo Carrara, le cave di Apricena sono il secondo bacino estrattivo nazionale',
  },
  {
    number: '12.500',
    label: 'Abitanti',
    desc: 'Cittadina al confine tra il Tavoliere delle Puglie e il Parco del Gargano',
  },
  {
    number: '1764',
    label: 'Santuario Incoronata',
    desc: 'Il Santuario mariano diocesano custodisce la statua miracolosa della Madonna',
  },
  {
    number: '1627',
    label: 'Anno del terremoto',
    desc: 'Il terremoto della Capitanata rasò al suolo la città, che rinacque più bella',
  },
];

export default function CittaSection() {
  return (
    <section style={{
      padding: '100px 0',
      background: 'var(--color-surface)',
      position: 'relative',
      overflow: 'hidden',
    }}>
      {/* Background subtle texture */}
      <div style={{
        position: 'absolute', inset: 0,
        backgroundImage: `
          radial-gradient(circle at 10% 50%, rgba(212,168,67,0.04) 0%, transparent 50%),
          radial-gradient(circle at 90% 20%, rgba(200,184,154,0.05) 0%, transparent 50%)
        `,
        pointerEvents: 'none',
      }} />

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px', position: 'relative' }}>
        <div style={{
          display: 'grid',
          gridTemplateColumns: '1fr 1fr',
          gap: 80, alignItems: 'center',
        }} className="citta-grid">

          {/* Left: image collage */}
          <div style={{ position: 'relative' }}>
            <div style={{
              borderRadius: 20, overflow: 'hidden',
              height: 480, position: 'relative',
              boxShadow: '0 24px 80px rgba(14,14,24,0.15)',
            }}>
              <img
                src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900&q=85"
                alt="Cave di pietra di Apricena"
                style={{ width: '100%', height: '100%', objectFit: 'cover' }}
              />
              <div style={{
                position: 'absolute', inset: 0,
                background: 'linear-gradient(135deg, rgba(14,14,24,0.3) 0%, transparent 60%)',
              }} />
            </div>
            {/* Floating card */}
            <div style={{
              position: 'absolute', bottom: -24, right: -24,
              background: 'var(--color-primary)',
              borderRadius: 16, padding: '20px 24px',
              boxShadow: '0 16px 48px rgba(14,14,24,0.25)',
              border: '1px solid rgba(212,168,67,0.15)',
              width: 200,
            }}>
              <div style={{
                fontFamily: 'var(--font-display)',
                fontSize: 40, fontWeight: 700,
                color: 'var(--color-accent)', lineHeight: 1,
                marginBottom: 6,
              }}>
                20%
              </div>
              <div style={{
                fontFamily: 'var(--font-body)',
                fontSize: 11, color: 'rgba(255,255,255,0.5)',
                lineHeight: 1.5, letterSpacing: '0.03em',
              }}>
                della produzione lapidea nazionale viene da Apricena
              </div>
            </div>
            {/* Decorative square outline */}
            <div style={{
              position: 'absolute', top: -16, left: -16,
              width: 80, height: 80,
              border: '2px solid rgba(212,168,67,0.2)',
              borderRadius: 8,
            }} />
          </div>

          {/* Right: content */}
          <div>
            <span className="section-eyebrow" style={{ marginBottom: 12 }}>Conoscere Apricena</span>
            <h2 style={{
              fontFamily: 'var(--font-display)',
              fontSize: 'clamp(2rem, 4vw, 3.2rem)',
              fontWeight: 600,
              color: 'var(--color-primary)',
              lineHeight: 1.1,
              letterSpacing: '-0.02em',
              marginBottom: 20,
            }}>
              La Città della Pietra,<br />
              <em style={{ color: 'var(--color-accent)', fontStyle: 'italic' }}>Porta del Gargano</em>
            </h2>
            <p style={{
              fontFamily: 'var(--font-body)',
              color: 'var(--color-text-muted)', fontSize: 15.5,
              lineHeight: 1.8, marginBottom: 16,
            }}>
              Apricena sorge al confine tra il Tavoliere delle Puglie e il Parco Nazionale del Gargano,
              nella provincia di Foggia. È universalmente nota per la sua pietra calcarea pregiata,
              estratta da cave che rappresentano il secondo polo marmifero d'Italia dopo Carrara.
            </p>
            <p style={{
              fontFamily: 'var(--font-body)',
              color: 'var(--color-text-muted)', fontSize: 15.5,
              lineHeight: 1.8, marginBottom: 36,
            }}>
              La Pietra di Apricena decora la Reggia di Caserta, la Basilica di San Pio a San Giovanni
              Rotondo e monumenti in tutto il mondo. Ma Apricena è anche storia, fede, tradizione:
              il Palazzo Baronale secentesco, la Torre dell'Orologio e il Santuario della Madonna
              Incoronata (1764) sono i simboli di una città che seppe rinascere dal grande terremoto del 1627.
            </p>

            {/* Stats grid */}
            <div style={{
              display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16,
              marginBottom: 36,
            }}>
              {highlights.map((h) => (
                <div key={h.label} style={{
                  background: '#fff', borderRadius: 12, padding: '16px 18px',
                  border: '1px solid rgba(212,168,67,0.1)',
                  boxShadow: '0 2px 12px rgba(14,14,24,0.05)',
                }}>
                  <div style={{
                    fontFamily: 'var(--font-display)',
                    fontSize: 24, fontWeight: 700,
                    color: 'var(--color-accent)', marginBottom: 4,
                    letterSpacing: '-0.02em',
                  }}>
                    {h.number}
                  </div>
                  <div style={{
                    fontFamily: 'var(--font-body)',
                    fontSize: 12, fontWeight: 700,
                    color: 'var(--color-primary)', marginBottom: 4,
                    letterSpacing: '0.02em',
                  }}>
                    {h.label}
                  </div>
                  <div style={{
                    fontFamily: 'var(--font-body)',
                    fontSize: 11, color: 'var(--color-text-muted)',
                    lineHeight: 1.5,
                  }}>
                    {h.desc}
                  </div>
                </div>
              ))}
            </div>

            <Link to="/chi-siamo" style={{
              display: 'inline-flex', alignItems: 'center', gap: 10,
              background: 'var(--color-primary)',
              color: '#fff',
              fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700,
              padding: '14px 28px', borderRadius: 50,
              textDecoration: 'none', transition: 'all 0.3s ease',
            }}
              onMouseEnter={e => {
                (e.currentTarget as HTMLElement).style.background = 'var(--color-accent)';
                (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)';
              }}
              onMouseLeave={e => {
                (e.currentTarget as HTMLElement).style.background = 'var(--color-primary)';
                (e.currentTarget as HTMLElement).style.color = '#fff';
              }}
            >
              Scopri la storia di Apricena <ArrowRight size={15} />
            </Link>
          </div>
        </div>
      </div>

      <style>{`
        @media (max-width: 767px) {
          .citta-grid { grid-template-columns: 1fr !important; gap: 48px !important; }
        }
      `}</style>
    </section>
  );
}
