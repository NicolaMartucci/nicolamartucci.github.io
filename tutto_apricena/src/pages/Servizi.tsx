import { useState } from 'react';
import { Phone, Globe, Mail, Clock, MapPin, Building, Shield, Heart, Train, BookOpen, Mail as MailIcon } from 'lucide-react';
import { servizi, categorieServizi } from '../data/mockData';

const iconMap: Record<string, React.ReactNode> = {
  building: <Building size={22} />,
  shield: <Shield size={22} />,
  heart: <Heart size={22} />,
  train: <Train size={22} />,
  book: <BookOpen size={22} />,
  mail: <MailIcon size={22} />,
};

export default function Servizi() {
  const [catAttiva, setCatAttiva] = useState('tutti');
  const filtered = catAttiva === 'tutti' ? servizi : servizi.filter(s => s.categoriaSlug === catAttiva);

  return (
    <main style={{ minHeight: '100vh', background: 'var(--color-surface)', paddingTop: 72 }}>
      {/* Header */}
      <div style={{ background: 'var(--color-primary)', padding: '48px 24px 52px' }}>
        <div style={{ maxWidth: 1280, margin: '0 auto' }}>
          <span style={{ display: 'block', color: 'var(--color-accent)', fontSize: 11, fontWeight: 700, letterSpacing: '0.18em', textTransform: 'uppercase', marginBottom: 8 }}>
            TuttoApricena
          </span>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(2rem, 5vw, 3rem)', fontWeight: 900, color: '#fff', marginBottom: 10 }}>
            Servizi Utili
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.55)', fontSize: 16 }}>Tutto quello che serve sapere su Apricena</p>
        </div>
      </div>

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '40px 24px 80px' }}>
        {/* Filters */}
        <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap', marginBottom: 36 }}>
          <button
            onClick={() => setCatAttiva('tutti')}
            style={{
              padding: '8px 20px', borderRadius: 50, fontSize: 13, fontWeight: 600, cursor: 'pointer',
              border: '2px solid',
              borderColor: catAttiva === 'tutti' ? 'var(--color-primary)' : '#ddd',
              background: catAttiva === 'tutti' ? 'var(--color-primary)' : '#fff',
              color: catAttiva === 'tutti' ? '#fff' : 'var(--color-text-muted)',
              transition: 'all 0.2s',
            }}
          >Tutti</button>
          {categorieServizi.map(cat => (
            <button
              key={cat.slug}
              onClick={() => setCatAttiva(cat.slug)}
              style={{
                padding: '8px 20px', borderRadius: 50, fontSize: 13, fontWeight: 600, cursor: 'pointer',
                border: '2px solid',
                borderColor: catAttiva === cat.slug ? 'var(--color-accent)' : '#ddd',
                background: catAttiva === cat.slug ? 'var(--color-accent)' : '#fff',
                color: catAttiva === cat.slug ? 'var(--color-primary)' : 'var(--color-text-muted)',
                transition: 'all 0.2s',
              }}
            >{cat.nome}</button>
          ))}
        </div>

        {/* Grid */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: 18 }} className="servizi-page-grid">
          {filtered.map(servizio => (
            <div
              key={servizio.id}
              style={{
                background: '#fff', borderRadius: 18, padding: '24px', boxShadow: '0 2px 14px rgba(26,26,46,0.07)',
                border: '1px solid #f0ece4', transition: 'all 0.25s',
              }}
              onMouseEnter={e => {
                (e.currentTarget as HTMLElement).style.boxShadow = '0 10px 28px rgba(26,26,46,0.12)';
                (e.currentTarget as HTMLElement).style.transform = 'translateY(-2px)';
              }}
              onMouseLeave={e => {
                (e.currentTarget as HTMLElement).style.boxShadow = '0 2px 14px rgba(26,26,46,0.07)';
                (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
              }}
            >
              <div style={{ display: 'flex', alignItems: 'flex-start', gap: 16 }}>
                <div style={{
                  width: 50, height: 50, borderRadius: 13, background: 'rgba(232,168,56,0.1)',
                  display: 'flex', alignItems: 'center', justifyContent: 'center',
                  color: 'var(--color-accent)', flexShrink: 0,
                }}>
                  {iconMap[servizio.icona] || <Building size={22} />}
                </div>
                <div style={{ flex: 1, minWidth: 0 }}>
                  <span style={{ display: 'block', color: 'var(--color-accent)', fontSize: 10, fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.12em', marginBottom: 3 }}>
                    {servizio.categoria}
                  </span>
                  <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 800, color: 'var(--color-primary)', marginBottom: 6 }}>
                    {servizio.nome}
                  </h3>
                  <p style={{ color: 'var(--color-text-muted)', fontSize: 14, lineHeight: 1.6, marginBottom: 16 }}>
                    {servizio.descrizione}
                  </p>

                  <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
                    {servizio.indirizzo && (
                      <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8, color: 'var(--color-text-muted)', fontSize: 13 }}>
                        <MapPin size={13} color="var(--color-accent)" style={{ flexShrink: 0, marginTop: 1 }} />
                        {servizio.indirizzo}
                      </div>
                    )}
                    {servizio.orari && (
                      <div style={{ display: 'flex', alignItems: 'center', gap: 8, color: 'var(--color-text-muted)', fontSize: 13 }}>
                        <Clock size={13} color="var(--color-accent)" />
                        {servizio.orari}
                      </div>
                    )}
                    <div style={{ display: 'flex', flexWrap: 'wrap', gap: 10, marginTop: 6 }}>
                      {servizio.telefono && (
                        <a href={`tel:${servizio.telefono}`} style={{
                          display: 'inline-flex', alignItems: 'center', gap: 5,
                          background: 'rgba(232,168,56,0.1)', color: 'var(--color-primary)',
                          fontSize: 13, fontWeight: 600, padding: '5px 12px', borderRadius: 50, textDecoration: 'none',
                        }}>
                          <Phone size={12} /> {servizio.telefono}
                        </a>
                      )}
                      {servizio.email && (
                        <a href={`mailto:${servizio.email}`} style={{
                          display: 'inline-flex', alignItems: 'center', gap: 5,
                          background: 'rgba(232,168,56,0.1)', color: 'var(--color-primary)',
                          fontSize: 13, fontWeight: 600, padding: '5px 12px', borderRadius: 50, textDecoration: 'none',
                        }}>
                          <Mail size={12} /> Email
                        </a>
                      )}
                      {servizio.sitoWeb && (
                        <a href={servizio.sitoWeb} target="_blank" rel="noopener noreferrer" style={{
                          display: 'inline-flex', alignItems: 'center', gap: 5,
                          background: 'var(--color-primary)', color: '#fff',
                          fontSize: 13, fontWeight: 600, padding: '5px 12px', borderRadius: 50, textDecoration: 'none',
                        }}>
                          <Globe size={12} /> Sito web
                        </a>
                      )}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) { .servizi-page-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </main>
  );
}
