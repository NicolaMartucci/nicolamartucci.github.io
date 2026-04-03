import { Link } from 'react-router-dom';
import { MapPin, Clock, ArrowRight } from 'lucide-react';
import { locali } from '../../data/mockData';

const tipoColors: Record<string, string> = {
  Ristorante: '#EA580C',
  Bar: '#E8A838',
  Alloggio: '#0284C7',
  Negozio: '#16A34A',
  Artigianato: '#7C3AED',
};

export default function LocaliSection() {
  const inEvidenza = locali.filter(l => l.inEvidenza).slice(0, 3);

  return (
    <section style={{ padding: '80px 0', background: 'var(--color-surface)' }}>
      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px' }}>

        {/* Header */}
        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 40, gap: 16, flexWrap: 'wrap' }}>
          <div>
            <span className="section-eyebrow">Scopri</span>
            <h2 className="section-title">Locali e Attività</h2>
            <p style={{ color: 'var(--color-text-muted)', marginTop: 6, fontSize: 15 }}>
              Ristoranti, bar, alloggi e attività commerciali di Apricena
            </p>
          </div>
          <Link to="/locali" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            color: 'var(--color-primary)', fontSize: 14, fontWeight: 600, textDecoration: 'none',
          }}
            onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
            onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)'}
          >
            Vedi tutti <ArrowRight size={16} />
          </Link>
        </div>

        {/* Cards */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 22 }} className="locali-grid">
          {inEvidenza.map((locale) => {
            const color = tipoColors[locale.tipo] || '#E8A838';
            return (
              <Link
                key={locale.id}
                to={`/locali/${locale.slug}`}
                style={{
                  background: '#fff',
                  borderRadius: 18,
                  overflow: 'hidden',
                  textDecoration: 'none',
                  boxShadow: '0 2px 16px rgba(26,26,46,0.07)',
                  transition: 'all 0.3s ease',
                  display: 'block',
                }}
                onMouseEnter={e => {
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(-5px)';
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 18px 36px rgba(26,26,46,0.13)';
                }}
                onMouseLeave={e => {
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 2px 16px rgba(26,26,46,0.07)';
                }}
              >
                <div style={{ position: 'relative', height: 210, overflow: 'hidden' }}>
                  <img
                    src={locale.immagine}
                    alt={locale.nome}
                    style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.5s ease' }}
                  />
                  <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(0,0,0,0.45) 0%, transparent 60%)' }} />
                  <span style={{
                    position: 'absolute', top: 12, left: 12,
                    background: color, color: '#fff',
                    fontSize: 10, fontWeight: 700, letterSpacing: '0.1em', textTransform: 'uppercase',
                    padding: '4px 12px', borderRadius: 50,
                  }}>
                    {locale.tipo}
                  </span>
                </div>
                <div style={{ padding: '18px 20px 20px' }}>
                  <h3 style={{
                    fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 700,
                    color: 'var(--color-primary)', marginBottom: 6, lineHeight: 1.2,
                  }}>
                    {locale.nome}
                  </h3>
                  <p style={{ color: 'var(--color-text-muted)', fontSize: 13, lineHeight: 1.55, marginBottom: 14 }} className="line-clamp-2">
                    {locale.descrizione}
                  </p>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: 5 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'var(--color-text-muted)', fontSize: 12 }}>
                      <MapPin size={12} color="var(--color-accent)" />
                      {locale.indirizzo}
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'var(--color-text-muted)', fontSize: 12 }}>
                      <Clock size={12} color="var(--color-accent)" />
                      {locale.orari}
                    </div>
                  </div>
                </div>
              </Link>
            );
          })}
        </div>

        <div style={{ marginTop: 28, textAlign: 'center' }}>
          <Link to="/locali" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            background: 'var(--color-primary)', color: '#fff',
            fontSize: 14, fontWeight: 600, padding: '12px 28px', borderRadius: 50,
            textDecoration: 'none',
          }}>
            Tutti i locali <ArrowRight size={15} />
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 900px) { .locali-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 580px) { .locali-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </section>
  );
}
