import { useState } from 'react';
import { Link } from 'react-router-dom';
import { MapPin, Clock, Phone, Star } from 'lucide-react';
import { locali, tipiLocali } from '../data/mockData';

const tipoColors: Record<string, string> = {
  Ristorante: '#EA580C', Bar: '#E8A838', Alloggio: '#0284C7',
  Negozio: '#16A34A', Artigianato: '#7C3AED',
};

export default function Locali() {
  const [tipoAttivo, setTipoAttivo] = useState('tutti');
  const filtered = tipoAttivo === 'tutti' ? locali : locali.filter(l => l.tipoSlug === tipoAttivo);

  return (
    <main style={{ minHeight: '100vh', background: 'var(--color-surface)', paddingTop: 72 }}>
      {/* Header */}
      <div style={{ background: 'var(--color-primary)', padding: '48px 24px 52px' }}>
        <div style={{ maxWidth: 1280, margin: '0 auto' }}>
          <span style={{ display: 'block', color: 'var(--color-accent)', fontSize: 11, fontWeight: 700, letterSpacing: '0.18em', textTransform: 'uppercase', marginBottom: 8 }}>
            Scopri Apricena
          </span>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(2rem, 5vw, 3rem)', fontWeight: 900, color: '#fff', marginBottom: 10 }}>
            Locali e Attività
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.55)', fontSize: 16 }}>Ristoranti, bar, alloggi e attività di Apricena</p>
        </div>
      </div>

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '40px 24px 80px' }}>
        {/* Filters */}
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', marginBottom: 36 }}>
          <button
            onClick={() => setTipoAttivo('tutti')}
            style={{
              padding: '8px 20px', borderRadius: 50, fontSize: 13, fontWeight: 600, cursor: 'pointer',
              border: '2px solid',
              borderColor: tipoAttivo === 'tutti' ? 'var(--color-primary)' : '#ddd',
              background: tipoAttivo === 'tutti' ? 'var(--color-primary)' : '#fff',
              color: tipoAttivo === 'tutti' ? '#fff' : 'var(--color-text-muted)',
              transition: 'all 0.2s',
            }}
          >Tutti</button>
          {tipiLocali.map(tipo => (
            <button
              key={tipo.slug}
              onClick={() => setTipoAttivo(tipo.slug)}
              style={{
                padding: '8px 20px', borderRadius: 50, fontSize: 13, fontWeight: 600, cursor: 'pointer',
                border: '2px solid',
                borderColor: tipoAttivo === tipo.slug ? (tipoColors[tipo.nome] || '#E8A838') : '#ddd',
                background: tipoAttivo === tipo.slug ? (tipoColors[tipo.nome] || '#E8A838') : '#fff',
                color: tipoAttivo === tipo.slug ? '#fff' : 'var(--color-text-muted)',
                transition: 'all 0.2s',
              }}
            >{tipo.nome}</button>
          ))}
        </div>

        {/* Grid */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 22 }} className="locali-page-grid">
          {filtered.map(locale => {
            const color = tipoColors[locale.tipo] || '#E8A838';
            return (
              <Link
                key={locale.id}
                to={`/locali/${locale.slug}`}
                style={{
                  background: '#fff', borderRadius: 18, overflow: 'hidden',
                  textDecoration: 'none', display: 'flex', flexDirection: 'column',
                  boxShadow: '0 2px 16px rgba(26,26,46,0.07)', transition: 'all 0.3s ease',
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
                  <img src={locale.immagine} alt={locale.nome} style={{ width: '100%', height: '100%', objectFit: 'cover', transition: 'transform 0.5s' }} />
                  <div style={{ position: 'absolute', inset: 0, background: 'linear-gradient(to top, rgba(0,0,0,0.45) 0%, transparent 60%)' }} />
                  <span style={{
                    position: 'absolute', top: 12, left: 12,
                    background: color, color: '#fff', fontSize: 10, fontWeight: 700,
                    letterSpacing: '0.1em', textTransform: 'uppercase', padding: '4px 12px', borderRadius: 50,
                  }}>{locale.tipo}</span>
                  {locale.inEvidenza && (
                    <span style={{
                      position: 'absolute', top: 12, right: 12,
                      background: 'var(--color-accent)', color: 'var(--color-primary)',
                      fontSize: 10, fontWeight: 700, padding: '4px 10px', borderRadius: 50,
                      display: 'flex', alignItems: 'center', gap: 4,
                    }}>
                      <Star size={9} fill="currentColor" /> In evidenza
                    </span>
                  )}
                </div>
                <div style={{ padding: '18px 20px 22px', flex: 1, display: 'flex', flexDirection: 'column' }}>
                  <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 700, color: 'var(--color-primary)', marginBottom: 6 }}>
                    {locale.nome}
                  </h3>
                  <p style={{ color: 'var(--color-text-muted)', fontSize: 13, lineHeight: 1.6, flex: 1, marginBottom: 14 }} className="line-clamp-2">
                    {locale.descrizione}
                  </p>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: 5, paddingTop: 12, borderTop: '1px solid #f0ece4' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'var(--color-text-muted)', fontSize: 12 }}>
                      <MapPin size={12} color={color} /> {locale.indirizzo}
                    </div>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'var(--color-text-muted)', fontSize: 12 }}>
                      <Clock size={12} color={color} /> {locale.orari}
                    </div>
                    {locale.telefono && (
                      <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'var(--color-text-muted)', fontSize: 12 }}>
                        <Phone size={12} color={color} /> {locale.telefono}
                      </div>
                    )}
                  </div>
                </div>
              </Link>
            );
          })}
        </div>
      </div>

      <style>{`
        @media (max-width: 900px) { .locali-page-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 580px) { .locali-page-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
