import { useState } from 'react';
import { Link } from 'react-router-dom';
import { Calendar, Search, ArrowRight } from 'lucide-react';
import { notizie, categorieNotizie } from '../data/mockData';

function formatDate(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('it-IT', { day: 'numeric', month: 'long', year: 'numeric' });
}

export default function Notizie() {
  const [catFilter, setCatFilter] = useState('tutte');
  const [search, setSearch] = useState('');

  const filtered = notizie.filter(n => {
    const matchCat = catFilter === 'tutte' || n.categoriaSlug === catFilter;
    const matchSearch = n.titolo.toLowerCase().includes(search.toLowerCase()) || n.abstract.toLowerCase().includes(search.toLowerCase());
    return matchCat && matchSearch;
  });

  const featured = filtered.find(n => n.inEvidenza) || filtered[0];
  const rest = filtered.filter(n => n.id !== featured?.id);

  return (
    <main style={{ paddingTop: 72, minHeight: '100vh', background: 'var(--color-surface)' }}>

      {/* ── Hero Header ── */}
      <div style={{
        background: 'var(--color-primary)',
        padding: '60px 24px 52px',
        position: 'relative', overflow: 'hidden',
      }}>
        <div style={{
          position: 'absolute', top: 0, right: 0, bottom: 0, width: '40%',
          background: 'radial-gradient(ellipse at 80% 30%, rgba(212,168,67,0.07) 0%, transparent 60%)',
          pointerEvents: 'none',
        }} />
        <div style={{ maxWidth: 1280, margin: '0 auto', position: 'relative' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 10 }}>
            <div style={{ width: 24, height: 2, background: 'var(--color-accent)', borderRadius: 2 }} />
            <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.18em', color: 'var(--color-accent)', textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>
              Aggiornamenti
            </span>
          </div>
          <h1 style={{
            fontFamily: 'var(--font-display)',
            fontSize: 'clamp(2.2rem, 5vw, 3.5rem)',
            fontWeight: 600, color: '#fff',
            letterSpacing: '-0.02em', marginBottom: 12,
          }}>
            Notizie da Apricena
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.5)', fontSize: 15, fontFamily: 'var(--font-body)', maxWidth: 520, lineHeight: 1.7 }}>
            Tutte le ultime notizie dal comune di Apricena e dal territorio garganico
          </p>
        </div>
      </div>

      {/* ── Filtri ── */}
      <div style={{ background: '#fff', borderBottom: '1px solid rgba(212,168,67,0.1)', position: 'sticky', top: 72, zIndex: 100 }}>
        <div style={{ maxWidth: 1280, margin: '0 auto', padding: '14px 24px', display: 'flex', flexWrap: 'wrap', gap: 12, alignItems: 'center' }}>
          {/* Search */}
          <div style={{ position: 'relative', flex: '1 1 240px', minWidth: 200, maxWidth: 320 }}>
            <Search size={14} style={{ position: 'absolute', left: 13, top: '50%', transform: 'translateY(-50%)', color: 'var(--color-text-muted)' }} />
            <input
              type="text"
              placeholder="Cerca notizie…"
              value={search}
              onChange={e => setSearch(e.target.value)}
              style={{
                width: '100%', paddingLeft: 36, paddingRight: 14,
                paddingTop: 9, paddingBottom: 9,
                borderRadius: 8, border: '1px solid rgba(212,168,67,0.2)',
                background: 'var(--color-surface)', fontSize: 13,
                outline: 'none', fontFamily: 'var(--font-body)',
                color: 'var(--color-text)',
              }}
            />
          </div>
          {/* Category pills */}
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6 }}>
            {[{ nome: 'Tutte', slug: 'tutte', colore: 'var(--color-primary)' }, ...categorieNotizie].map(cat => (
              <button
                key={cat.slug}
                onClick={() => setCatFilter(cat.slug)}
                style={{
                  padding: '6px 16px', borderRadius: 50, fontSize: 12, fontWeight: 700,
                  cursor: 'pointer', border: 'none', fontFamily: 'var(--font-body)',
                  letterSpacing: '0.03em',
                  background: catFilter === cat.slug ? (cat as any).colore || 'var(--color-primary)' : 'var(--color-surface)',
                  color: catFilter === cat.slug ? '#fff' : 'var(--color-text-muted)',
                  transition: 'all 0.2s',
                }}
              >
                {cat.nome}
              </button>
            ))}
          </div>
        </div>
      </div>

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '48px 24px 80px' }}>
        {filtered.length === 0 ? (
          <div style={{ textAlign: 'center', padding: '80px 24px', color: 'var(--color-text-muted)' }}>
            <div style={{ fontSize: 48, marginBottom: 16 }}>🔍</div>
            <p style={{ fontSize: 18, fontFamily: 'var(--font-display)' }}>Nessuna notizia trovata.</p>
            <button onClick={() => { setCatFilter('tutte'); setSearch(''); }} style={{ marginTop: 16, color: 'var(--color-accent)', fontWeight: 700, background: 'none', border: 'none', cursor: 'pointer', fontSize: 14, fontFamily: 'var(--font-body)' }}>
              Azzera filtri
            </button>
          </div>
        ) : (
          <>
            {/* ── Featured grande ── */}
            {featured && (
              <div style={{ marginBottom: 56 }}>
                <Link
                  to={`/notizie/${featured.slug}`}
                  style={{
                    display: 'grid', gridTemplateColumns: '1.3fr 1fr',
                    borderRadius: 24, overflow: 'hidden', textDecoration: 'none',
                    boxShadow: '0 8px 40px rgba(14,14,24,0.12)',
                    minHeight: 400, background: '#fff',
                    transition: 'all 0.35s ease',
                  }}
                  className="featured-grid"
                  onMouseEnter={e => {
                    (e.currentTarget as HTMLElement).style.transform = 'translateY(-4px)';
                    (e.currentTarget as HTMLElement).style.boxShadow = '0 20px 60px rgba(14,14,24,0.18)';
                  }}
                  onMouseLeave={e => {
                    (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                    (e.currentTarget as HTMLElement).style.boxShadow = '0 8px 40px rgba(14,14,24,0.12)';
                  }}
                >
                  {/* Image */}
                  <div style={{ position: 'relative', overflow: 'hidden' }}>
                    <img
                      src={featured.immagine}
                      alt={featured.titolo}
                      style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.6s ease' }}
                    />
                    <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(135deg, rgba(14,14,24,0.3) 0%, transparent 60%)' }} />
                    <div style={{ position: 'absolute', top: 20, left: 20 }}>
                      <span style={{
                        background: categorieNotizie.find(c => c.slug === featured.categoriaSlug)?.colore,
                        color: '#fff', fontSize: 10, fontWeight: 700, letterSpacing: '0.12em',
                        textTransform: 'uppercase', padding: '5px 14px', borderRadius: 50,
                        fontFamily: 'var(--font-body)',
                      }}>
                        {featured.categoria}
                      </span>
                    </div>
                    <div style={{
                      position: 'absolute', top: 20, right: 20,
                      background: 'rgba(212,168,67,0.9)', borderRadius: 8,
                      padding: '4px 10px',
                    }}>
                      <span style={{ color: 'var(--color-primary)', fontSize: 9, fontWeight: 800, fontFamily: 'var(--font-body)', letterSpacing: '0.1em', textTransform: 'uppercase' }}>
                        In evidenza
                      </span>
                    </div>
                  </div>
                  {/* Content */}
                  <div style={{ padding: '40px 36px', display: 'flex', flexDirection: 'column', justifyContent: 'center' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 6, color: 'var(--color-text-muted)', fontSize: 12, fontFamily: 'var(--font-body)', marginBottom: 18 }}>
                      <Calendar size={11} />
                      {formatDate(featured.data)}
                      <span style={{ margin: '0 4px', opacity: 0.4 }}>·</span>
                      {featured.fonte}
                    </div>
                    <h2 style={{
                      fontFamily: 'var(--font-display)',
                      fontSize: 'clamp(1.4rem, 2.5vw, 2rem)',
                      fontWeight: 600, color: 'var(--color-primary)',
                      lineHeight: 1.2, letterSpacing: '-0.02em', marginBottom: 16,
                    }}>
                      {featured.titolo}
                    </h2>
                    <p style={{ color: 'var(--color-text-muted)', fontSize: 14.5, lineHeight: 1.75, fontFamily: 'var(--font-body)', marginBottom: 28 }} className="line-clamp-3">
                      {featured.abstract}
                    </p>
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 6, marginBottom: 28 }}>
                      {featured.tag.slice(0, 3).map(t => (
                        <span key={t} className="tag-pill">{t}</span>
                      ))}
                    </div>
                    <div style={{ display: 'inline-flex', alignItems: 'center', gap: 8, color: 'var(--color-accent-dark)', fontWeight: 700, fontSize: 13, fontFamily: 'var(--font-body)' }}>
                      Leggi l'articolo <ArrowRight size={14} />
                    </div>
                  </div>
                </Link>
              </div>
            )}

            {/* ── Griglia articoli ── */}
            {rest.length > 0 && (
              <>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 28 }}>
                  <div style={{ width: 24, height: 2, background: 'var(--color-accent)', borderRadius: 2 }} />
                  <span style={{ fontSize: 10, fontWeight: 700, letterSpacing: '0.18em', color: 'var(--color-accent)', textTransform: 'uppercase', fontFamily: 'var(--font-body)' }}>
                    Altre notizie
                  </span>
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 24 }} className="notizie-grid">
                  {rest.map(notizia => {
                    const catColor = categorieNotizie.find(c => c.slug === notizia.categoriaSlug)?.colore || '#D4A843';
                    return (
                      <Link
                        key={notizia.id}
                        to={`/notizie/${notizia.slug}`}
                        style={{
                          background: '#fff', borderRadius: 20, overflow: 'hidden',
                          textDecoration: 'none', display: 'flex', flexDirection: 'column',
                          boxShadow: '0 2px 20px rgba(14,14,24,0.07)',
                          border: '1px solid rgba(212,168,67,0.06)',
                          transition: 'all 0.3s ease',
                        }}
                        onMouseEnter={e => {
                          (e.currentTarget as HTMLElement).style.transform = 'translateY(-5px)';
                          (e.currentTarget as HTMLElement).style.boxShadow = '0 16px 40px rgba(14,14,24,0.13)';
                          (e.currentTarget as HTMLElement).style.borderColor = 'rgba(212,168,67,0.2)';
                        }}
                        onMouseLeave={e => {
                          (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                          (e.currentTarget as HTMLElement).style.boxShadow = '0 2px 20px rgba(14,14,24,0.07)';
                          (e.currentTarget as HTMLElement).style.borderColor = 'rgba(212,168,67,0.06)';
                        }}
                      >
                        {/* Image */}
                        <div style={{ position: 'relative', height: 210, overflow: 'hidden' }}>
                          <img
                            src={notizia.immagine}
                            alt={notizia.titolo}
                            style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.5s ease' }}
                          />
                          <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(14,14,24,0.3) 0%, transparent 60%)' }} />
                          {/* Top color bar */}
                          <div style={{ position: 'absolute', top: 0, left: 0, right: 0, height: 3, background: catColor }} />
                          <span style={{
                            position: 'absolute', top: 16, left: 16,
                            background: catColor + 'dd',
                            color: '#fff', fontSize: 9, fontWeight: 800,
                            letterSpacing: '0.12em', textTransform: 'uppercase',
                            padding: '4px 12px', borderRadius: 50,
                            fontFamily: 'var(--font-body)',
                          }}>
                            {notizia.categoria}
                          </span>
                        </div>

                        {/* Content */}
                        <div style={{ padding: '20px 22px 22px', flex: 1, display: 'flex', flexDirection: 'column' }}>
                          <h3 style={{
                            fontFamily: 'var(--font-display)',
                            fontSize: 17, fontWeight: 600,
                            color: 'var(--color-primary)', lineHeight: 1.3,
                            marginBottom: 10, flex: 1,
                            letterSpacing: '-0.01em',
                          }} className="line-clamp-2">
                            {notizia.titolo}
                          </h3>
                          <p style={{
                            color: 'var(--color-text-muted)', fontSize: 13,
                            lineHeight: 1.65, marginBottom: 16,
                            fontFamily: 'var(--font-body)',
                          }} className="line-clamp-2">
                            {notizia.abstract}
                          </p>
                          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                            <div style={{ display: 'flex', alignItems: 'center', gap: 5, color: 'var(--color-text-muted)', fontSize: 11, fontFamily: 'var(--font-body)' }}>
                              <Calendar size={10} />
                              {formatDate(notizia.data)}
                            </div>
                            <span style={{ display: 'flex', alignItems: 'center', gap: 4, color: 'var(--color-accent-dark)', fontSize: 12, fontWeight: 700, fontFamily: 'var(--font-body)' }}>
                              Leggi <ArrowRight size={12} />
                            </span>
                          </div>
                        </div>
                      </Link>
                    );
                  })}
                </div>
              </>
            )}
          </>
        )}
      </div>

      <style>{`
        @media (max-width: 900px) { .notizie-grid { grid-template-columns: repeat(2, 1fr) !important; } .featured-grid { grid-template-columns: 1fr !important; } }
        @media (max-width: 580px) { .notizie-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
