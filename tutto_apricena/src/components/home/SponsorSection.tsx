import { Link } from 'react-router-dom';
import { ArrowRight, Star } from 'lucide-react';
import { sponsor } from '../../data/mockData';

const levelColors: Record<string, string> = {
  Gold: '#E8A838',
  Silver: '#9CA3AF',
  Bronze: '#92400E',
};

export default function SponsorSection() {
  const goldSponsors = sponsor.filter(s => s.livello === 'Gold' && s.attivo);
  const otherSponsors = sponsor.filter(s => s.livello !== 'Gold' && s.attivo);

  return (
    <section style={{ padding: '64px 0', background: 'var(--color-surface)', borderTop: '1px solid var(--color-surface-dark)' }}>
      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px' }}>

        {/* Header */}
        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 32, gap: 16, flexWrap: 'wrap' }}>
          <div>
            <span className="section-eyebrow">Con il supporto di</span>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.8rem', fontWeight: 800, color: 'var(--color-primary)' }}>
              I nostri Sponsor
            </h2>
          </div>
          <Link to="/sponsor" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            color: 'var(--color-primary)', fontSize: 14, fontWeight: 600, textDecoration: 'none',
          }}
            onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
            onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)'}
          >
            Diventa sponsor <ArrowRight size={16} />
          </Link>
        </div>

        {/* Gold */}
        {goldSponsors.map(s => (
          <div key={s.id} style={{
            background: '#fff',
            border: '2px solid rgba(232,168,56,0.25)',
            borderRadius: 18,
            padding: '24px 28px',
            display: 'flex',
            flexWrap: 'wrap',
            alignItems: 'center',
            gap: 20,
            marginBottom: 20,
            boxShadow: '0 4px 20px rgba(232,168,56,0.08)',
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
              <div style={{
                width: 58, height: 58, borderRadius: 14,
                background: 'rgba(232,168,56,0.1)',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                flexShrink: 0,
              }}>
                <Star size={26} color="var(--color-accent)" fill="var(--color-accent)" />
              </div>
              <div>
                <span style={{ display: 'block', fontSize: 10, fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.15em', color: 'var(--color-accent)', marginBottom: 3 }}>
                  Sponsor Gold
                </span>
                <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 700, color: 'var(--color-primary)' }}>
                  {s.nome}
                </h3>
              </div>
            </div>
            <p style={{ color: 'var(--color-text-muted)', fontSize: 14, flex: 1, minWidth: 200 }}>{s.descrizione}</p>
            {s.sitoWeb && (
              <a href={s.sitoWeb} target="_blank" rel="noopener noreferrer" style={{
                fontSize: 13, fontWeight: 600, color: 'var(--color-accent)', textDecoration: 'none', flexShrink: 0,
              }}>
                Visita il sito →
              </a>
            )}
          </div>
        ))}

        {/* Others */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: 14 }} className="sponsor-grid">
          {otherSponsors.map(s => (
            <div key={s.id} style={{
              background: '#fff',
              borderRadius: 14,
              padding: '18px 14px',
              display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center',
              boxShadow: '0 2px 10px rgba(26,26,46,0.06)',
              transition: 'all 0.25s',
            }}
              onMouseEnter={e => {
                (e.currentTarget as HTMLElement).style.transform = 'translateY(-3px)';
                (e.currentTarget as HTMLElement).style.boxShadow = '0 8px 24px rgba(26,26,46,0.12)';
              }}
              onMouseLeave={e => {
                (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                (e.currentTarget as HTMLElement).style.boxShadow = '0 2px 10px rgba(26,26,46,0.06)';
              }}
            >
              <div style={{
                width: 42, height: 42, borderRadius: '50%',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                marginBottom: 10, fontWeight: 900, fontSize: 16, color: '#fff',
                background: levelColors[s.livello] || '#6B7280',
              }}>
                {s.nome.charAt(0)}
              </div>
              <h4 style={{ fontSize: 13, fontWeight: 600, color: 'var(--color-primary)', lineHeight: 1.3 }}>{s.nome}</h4>
              <span style={{ fontSize: 11, color: 'var(--color-text-muted)', marginTop: 3 }}>{s.livello}</span>
            </div>
          ))}

          {/* CTA */}
          <Link to="/sponsor" style={{
            background: 'var(--color-primary)',
            borderRadius: 14,
            padding: '18px 14px',
            display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', textAlign: 'center',
            textDecoration: 'none',
            transition: 'background 0.25s',
          }}
            onMouseEnter={e => (e.currentTarget as HTMLElement).style.background = 'var(--color-accent)'}
            onMouseLeave={e => (e.currentTarget as HTMLElement).style.background = 'var(--color-primary)'}
          >
            <span style={{ fontSize: 28, color: '#fff', marginBottom: 6, lineHeight: 1 }}>+</span>
            <span style={{ fontSize: 12, fontWeight: 600, color: '#fff', lineHeight: 1.3 }}>Diventa sponsor</span>
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 900px) { .sponsor-grid { grid-template-columns: repeat(3, 1fr) !important; } }
        @media (max-width: 580px) { .sponsor-grid { grid-template-columns: repeat(2, 1fr) !important; } }
      `}</style>
    </section>
  );
}
