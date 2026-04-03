import { Link } from 'react-router-dom';
import { Calendar, ArrowRight, ArrowUpRight } from 'lucide-react';
import { notizie, categorieNotizie } from '../../data/mockData';

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('it-IT', { day: 'numeric', month: 'long', year: 'numeric' });
}

function getCategoryColor(slug: string) {
  return categorieNotizie.find(c => c.slug === slug)?.colore || '#D4A843';
}

function CategoryBadge({ categoria, categoriaSlug }: { categoria: string; categoriaSlug: string }) {
  const color = getCategoryColor(categoriaSlug);
  return (
    <span style={{
      display: 'inline-block',
      background: color + '22',
      color: color,
      fontSize: 10,
      fontWeight: 700,
      letterSpacing: '0.12em',
      textTransform: 'uppercase',
      padding: '4px 12px',
      borderRadius: 50,
      border: `1px solid ${color}44`,
      fontFamily: 'var(--font-body)',
    }}>
      {categoria}
    </span>
  );
}

export default function NewsSection() {
  const featured = notizie.find(n => n.inEvidenza) || notizie[0];
  const secondary = notizie.filter(n => n.id !== featured.id).slice(0, 3);

  return (
    <section style={{ padding: '100px 0', background: 'var(--color-surface)' }}>
      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px' }}>

        {/* Header */}
        <div style={{
          display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between',
          marginBottom: 48, gap: 16, flexWrap: 'wrap',
        }}>
          <div>
            <span className="section-eyebrow">Aggiornamenti</span>
            <h2 className="section-title">Ultime Notizie</h2>
          </div>
          <Link to="/notizie" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            color: 'var(--color-text-muted)', fontSize: 13,
            fontWeight: 600, fontFamily: 'var(--font-body)',
            textDecoration: 'none', transition: 'color 0.2s',
            borderBottom: '1px solid var(--color-stone)',
            paddingBottom: 2,
          }}
            onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
            onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-text-muted)'}
          >
            Tutte le notizie <ArrowRight size={14} />
          </Link>
        </div>

        {/* Grid */}
        <div style={{ display: 'grid', gridTemplateColumns: 'minmax(0,1.8fr) minmax(0,1fr)', gap: 24 }} className="news-grid">

          {/* Featured article */}
          <Link
            to={`/notizie/${featured.slug}`}
            style={{
              position: 'relative', borderRadius: 20,
              overflow: 'hidden', minHeight: 480,
              display: 'flex', flexDirection: 'column',
              justifyContent: 'flex-end', textDecoration: 'none',
              boxShadow: '0 8px 32px rgba(14,14,24,0.12)',
              cursor: 'pointer',
            }}
            className="card-hover"
          >
            <img
              src={featured.immagine}
              alt={featured.titolo}
              style={{
                position: 'absolute', inset: 0,
                width: '100%', height: '100%', objectFit: 'cover',
                transition: 'transform 0.7s ease',
              }}
            />
            {/* Gradient */}
            <div style={{
              position: 'absolute', inset: 0,
              background: 'linear-gradient(to top, rgba(14,14,24,0.95) 0%, rgba(14,14,24,0.5) 55%, rgba(14,14,24,0.1) 100%)',
            }} />
            {/* Gold accent bar top */}
            <div style={{
              position: 'absolute', top: 0, left: 0, right: 0, height: 3,
              background: 'linear-gradient(to right, var(--color-accent), var(--color-accent-light))',
            }} />
            <div style={{ position: 'relative', zIndex: 2, padding: 32 }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 14 }}>
                <CategoryBadge categoria={featured.categoria} categoriaSlug={featured.categoriaSlug} />
                <span style={{ color: 'rgba(255,255,255,0.3)', fontSize: 11 }}>·</span>
                <div style={{ display: 'flex', alignItems: 'center', gap: 5, color: 'rgba(255,255,255,0.4)', fontSize: 11, fontFamily: 'var(--font-body)' }}>
                  <Calendar size={10} />
                  {formatDate(featured.data)}
                </div>
              </div>
              <h3 style={{
                fontFamily: 'var(--font-display)',
                fontSize: 'clamp(1.4rem, 2.5vw, 2rem)',
                fontWeight: 600, color: '#fff',
                marginBottom: 12, lineHeight: 1.2,
                letterSpacing: '-0.01em',
              }}>
                {featured.titolo}
              </h3>
              <p style={{
                color: 'rgba(255,255,255,0.65)', fontSize: 14,
                lineHeight: 1.7, marginBottom: 20,
                fontFamily: 'var(--font-body)',
              }} className="line-clamp-2">
                {featured.abstract}
              </p>
              <div style={{
                display: 'inline-flex', alignItems: 'center', gap: 8,
                color: 'var(--color-accent)', fontSize: 12,
                fontWeight: 700, fontFamily: 'var(--font-body)',
                letterSpacing: '0.05em',
              }}>
                Leggi l'articolo <ArrowUpRight size={14} />
              </div>
            </div>
          </Link>

          {/* Secondary articles */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            {secondary.map((notizia) => (
              <Link
                key={notizia.id}
                to={`/notizie/${notizia.slug}`}
                style={{
                  display: 'flex', gap: 16,
                  background: '#fff',
                  borderRadius: 16, padding: 16,
                  textDecoration: 'none',
                  boxShadow: '0 2px 16px rgba(14,14,24,0.06)',
                  transition: 'all 0.3s ease',
                  flex: 1, alignItems: 'flex-start',
                  border: '1px solid rgba(212,168,67,0.06)',
                }}
                onMouseEnter={e => {
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 8px 32px rgba(14,14,24,0.12)';
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(-3px)';
                  (e.currentTarget as HTMLElement).style.borderColor = 'rgba(212,168,67,0.2)';
                }}
                onMouseLeave={e => {
                  (e.currentTarget as HTMLElement).style.boxShadow = '0 2px 16px rgba(14,14,24,0.06)';
                  (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                  (e.currentTarget as HTMLElement).style.borderColor = 'rgba(212,168,67,0.06)';
                }}
              >
                <div style={{
                  width: 80, height: 80, flexShrink: 0,
                  borderRadius: 12, overflow: 'hidden',
                  position: 'relative',
                }}>
                  <img
                    src={notizia.immagine}
                    alt={notizia.titolo}
                    style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                  />
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', justifyContent: 'space-between', minWidth: 0, flex: 1 }}>
                  <div>
                    <CategoryBadge categoria={notizia.categoria} categoriaSlug={notizia.categoriaSlug} />
                    <h4 style={{
                      fontFamily: 'var(--font-display)',
                      fontSize: 15, fontWeight: 600,
                      color: 'var(--color-primary)', marginTop: 7, lineHeight: 1.3,
                      letterSpacing: '-0.01em',
                    }} className="line-clamp-2">
                      {notizia.titolo}
                    </h4>
                  </div>
                  <div style={{
                    display: 'flex', alignItems: 'center', gap: 5,
                    color: 'var(--color-text-muted)', fontSize: 11, marginTop: 8,
                    fontFamily: 'var(--font-body)',
                  }}>
                    <Calendar size={10} />
                    {formatDate(notizia.data)}
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>

        {/* Mobile CTA */}
        <div style={{ marginTop: 28, textAlign: 'center' }}>
          <Link to="/notizie" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            background: 'var(--color-primary)', color: '#fff',
            fontFamily: 'var(--font-body)',
            fontSize: 13, fontWeight: 600, padding: '12px 28px', borderRadius: 50,
            textDecoration: 'none',
          }}>
            Tutte le notizie <ArrowRight size={14} />
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 767px) {
          .news-grid { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </section>
  );
}
