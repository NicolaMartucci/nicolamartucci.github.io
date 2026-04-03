import { useParams, Link } from 'react-router-dom';
import { Calendar, ArrowLeft, ExternalLink, Tag, ArrowRight } from 'lucide-react';
import { notizie, categorieNotizie } from '../data/mockData';

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('it-IT', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

export default function SingleNotizia() {
  const { slug } = useParams();
  const notizia = notizie.find(n => n.slug === slug);

  if (!notizia) {
    return (
      <main style={{ paddingTop: 72, minHeight: '80vh', display: 'flex', alignItems: 'center', justifyContent: 'center', flexDirection: 'column', gap: 16, background: 'var(--color-surface)' }}>
        <div style={{ fontSize: 48 }}>📰</div>
        <h1 style={{ fontFamily: 'var(--font-display)', fontSize: '2rem', color: 'var(--color-primary)' }}>Notizia non trovata</h1>
        <Link to="/notizie" style={{ color: 'var(--color-accent)', textDecoration: 'none', fontWeight: 600, fontFamily: 'var(--font-body)' }}>← Torna alle notizie</Link>
      </main>
    );
  }

  const catColor = categorieNotizie.find(c => c.slug === notizia.categoriaSlug)?.colore || '#D4A843';
  const correlate = notizie.filter(n => n.id !== notizia.id).slice(0, 3);

  return (
    <main style={{ paddingTop: 72, background: 'var(--color-surface)', minHeight: '100vh' }}>

      {/* ── Hero immagine ── */}
      <div style={{ position: 'relative', height: 'clamp(320px, 50vh, 520px)', overflow: 'hidden' }}>
        <img
          src={notizia.immagine}
          alt={notizia.titolo}
          style={{ width: '100%', height: '100%', objectFit: 'cover' }}
        />
        <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(14,14,24,0.92) 0%, rgba(14,14,24,0.5) 55%, rgba(14,14,24,0.15) 100%)' }} />
        {/* Gold top bar */}
        <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: `linear-gradient(to right, ${catColor}, ${catColor}aa)` }} />

        {/* Back button */}
        <Link to="/notizie" style={{
          position: 'absolute', top: 24, left: 24,
          display: 'inline-flex', alignItems: 'center', gap: 7,
          background: 'rgba(14,14,24,0.7)', backdropFilter: 'blur(12px)',
          color: '#fff', fontSize: 12, fontWeight: 700,
          fontFamily: 'var(--font-body)',
          padding: '8px 18px', borderRadius: 8, textDecoration: 'none',
          border: '1px solid rgba(255,255,255,0.12)',
          transition: 'all 0.2s',
        }}>
          <ArrowLeft size={13} /> Notizie
        </Link>

        {/* Title area */}
        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, padding: '32px 24px', maxWidth: 900, margin: '0 auto' }}>
          <span style={{
            display: 'inline-block', background: catColor,
            color: '#fff', fontSize: 10, fontWeight: 800,
            letterSpacing: '0.14em', textTransform: 'uppercase',
            padding: '5px 14px', borderRadius: 50,
            fontFamily: 'var(--font-body)', marginBottom: 14,
          }}>
            {notizia.categoria}
          </span>
          <h1 style={{
            fontFamily: 'var(--font-display)', color: '#fff',
            fontSize: 'clamp(1.7rem, 4vw, 2.8rem)',
            fontWeight: 600, lineHeight: 1.15,
            letterSpacing: '-0.02em',
          }}>
            {notizia.titolo}
          </h1>
        </div>
      </div>

      {/* ── Articolo ── */}
      <div style={{ maxWidth: 820, margin: '0 auto', padding: '48px 24px 80px' }}>

        {/* Meta */}
        <div style={{
          display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 16,
          marginBottom: 36, paddingBottom: 28,
          borderBottom: '1px solid rgba(212,168,67,0.15)',
        }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 6, color: 'var(--color-text-muted)', fontSize: 13, fontFamily: 'var(--font-body)' }}>
            <Calendar size={13} color="var(--color-accent)" />
            {formatDate(notizia.data)}
          </div>
          {notizia.fonteUrl && (
            <a href={notizia.fonteUrl} target="_blank" rel="noopener noreferrer" style={{
              display: 'inline-flex', alignItems: 'center', gap: 5,
              color: 'var(--color-accent-dark)', fontSize: 13,
              fontWeight: 700, textDecoration: 'none',
              fontFamily: 'var(--font-body)',
              border: '1px solid rgba(212,168,67,0.25)',
              borderRadius: 8, padding: '4px 12px',
              background: 'rgba(212,168,67,0.06)',
            }}>
              <ExternalLink size={11} /> Fonte: {notizia.fonte}
            </a>
          )}
        </div>

        {/* Abstract / occhiello */}
        <p style={{
          fontSize: 19, lineHeight: 1.8, color: 'var(--color-text)',
          marginBottom: 32, fontWeight: 400,
          fontFamily: 'var(--font-display)',
          fontStyle: 'italic',
          borderLeft: `3px solid ${catColor}`,
          paddingLeft: 22,
        }}>
          {notizia.abstract}
        </p>

        {/* Body */}
        <div style={{ fontSize: 16, lineHeight: 1.9, color: 'var(--color-text)', fontFamily: 'var(--font-body)' }}>
          {notizia.testo.split('\n\n').map((para, i) => (
            <p key={i} style={{ marginBottom: '1.5em' }}>{para}</p>
          ))}
        </div>

        {/* Tags */}
        {notizia.tag && notizia.tag.length > 0 && (
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 8, marginTop: 36, paddingTop: 28, borderTop: '1px solid rgba(212,168,67,0.12)' }}>
            <span style={{ fontSize: 11, color: 'var(--color-text-muted)', fontFamily: 'var(--font-body)', fontWeight: 600, alignSelf: 'center', marginRight: 4 }}>
              <Tag size={11} style={{ verticalAlign: 'middle', marginRight: 4 }} />Tag:
            </span>
            {notizia.tag.map(t => (
              <span key={t} className="tag-pill">{t}</span>
            ))}
          </div>
        )}

        {/* Source box */}
        <div style={{
          marginTop: 36, padding: '20px 24px',
          background: '#fff', borderRadius: 16,
          display: 'flex', alignItems: 'center', gap: 16,
          border: '1px solid rgba(212,168,67,0.15)',
          boxShadow: '0 2px 16px rgba(14,14,24,0.05)',
        }}>
          <div style={{
            width: 44, height: 44, borderRadius: 10,
            background: 'rgba(212,168,67,0.1)',
            display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0,
          }}>
            <ExternalLink size={18} color="var(--color-accent)" />
          </div>
          <div>
            <span style={{ display: 'block', fontSize: 10, fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.12em', color: 'var(--color-text-muted)', fontFamily: 'var(--font-body)', marginBottom: 3 }}>
              Fonte originale
            </span>
            <a href={notizia.fonteUrl} target="_blank" rel="noopener noreferrer" style={{
              color: 'var(--color-primary)', fontWeight: 700, fontSize: 14,
              textDecoration: 'none', fontFamily: 'var(--font-body)',
            }}>
              {notizia.fonte} <ArrowRight size={13} style={{ verticalAlign: 'middle' }} />
            </a>
          </div>
        </div>
      </div>

      {/* ── Altre notizie ── */}
      {correlate.length > 0 && (
        <div style={{ background: 'var(--color-primary)', padding: '60px 24px' }}>
          <div style={{ maxWidth: 1280, margin: '0 auto' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 8 }}>
              <div style={{ width: 24, height: 2, background: 'var(--color-accent)', borderRadius: 2 }} />
              <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.18em', color: 'var(--color-accent)', textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>Continua a leggere</span>
            </div>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.8rem', fontWeight: 600, color: '#fff', letterSpacing: '-0.02em', marginBottom: 32 }}>
              Altre notizie
            </h2>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 20 }} className="correlate-grid">
              {correlate.map(n => {
                const cc = categorieNotizie.find(c => c.slug === n.categoriaSlug)?.colore || '#D4A843';
                return (
                  <Link key={n.id} to={`/notizie/${n.slug}`} style={{
                    background: 'rgba(255,255,255,0.05)', borderRadius: 16, overflow: 'hidden',
                    textDecoration: 'none', border: '1px solid rgba(255,255,255,0.08)',
                    transition: 'all 0.3s ease', display: 'flex', flexDirection: 'column',
                  }}
                    onMouseEnter={e => {
                      (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,0.1)';
                      (e.currentTarget as HTMLElement).style.transform = 'translateY(-4px)';
                    }}
                    onMouseLeave={e => {
                      (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,0.05)';
                      (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                    }}
                  >
                    <div style={{ height: 160, overflow: 'hidden', position: 'relative' }}>
                      <img src={n.immagine} alt={n.titolo} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                      <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: cc }} />
                    </div>
                    <div style={{ padding: '16px 18px' }}>
                      <span style={{
                        display: 'inline-block', background: cc + '22', border: `1px solid ${cc}44`,
                        color: cc, fontSize: 9, fontWeight: 800, letterSpacing: '0.12em', textTransform: 'uppercase',
                        padding: '3px 10px', borderRadius: 50, fontFamily: 'var(--font-body)', marginBottom: 8,
                      }}>{n.categoria}</span>
                      <h4 style={{ fontFamily: 'var(--font-display)', fontSize: 15, fontWeight: 600, color: '#fff', lineHeight: 1.3 }} className="line-clamp-2">
                        {n.titolo}
                      </h4>
                    </div>
                  </Link>
                );
              })}
            </div>
          </div>
        </div>
      )}

      <style>{`
        @media (max-width: 768px) { .correlate-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
