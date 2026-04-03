import { ExternalLink, Star, Award, Shield } from 'lucide-react';
import { sponsor } from '../data/mockData';
import { Link } from 'react-router-dom';

const levelConfig: Record<string, { label: string; color: string; icon: React.ReactNode; description: string }> = {
  Gold: { label: 'Gold', color: '#E8A838', icon: <Star size={18} fill="currentColor" />, description: 'Massima visibilità su tutto il portale, banner in homepage, menzione in tutte le sezioni.' },
  Silver: { label: 'Silver', color: '#6B7280', icon: <Award size={18} />, description: 'Ottima visibilità, logo nella sezione sponsor, menzione nelle newsletter future.' },
  Bronze: { label: 'Bronze', color: '#92400E', icon: <Shield size={18} />, description: 'Presenza nella pagina sponsor, link al sito web, supporto alla comunità locale.' },
};

export default function Sponsor() {
  const goldSponsors = sponsor.filter(s => s.livello === 'Gold' && s.attivo);
  const silverSponsors = sponsor.filter(s => s.livello === 'Silver' && s.attivo);
  const bronzeSponsors = sponsor.filter(s => s.livello === 'Bronze' && s.attivo);

  return (
    <main style={{ minHeight: '100vh', background: 'var(--color-surface)', paddingTop: 72 }}>
      {/* Header */}
      <div style={{ background: 'var(--color-primary)', padding: '48px 24px 52px' }}>
        <div style={{ maxWidth: 1280, margin: '0 auto' }}>
          <span style={{ display: 'block', color: 'var(--color-accent)', fontSize: 11, fontWeight: 700, letterSpacing: '0.18em', textTransform: 'uppercase', marginBottom: 8 }}>Sostieni TuttoApricena</span>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(2rem, 5vw, 3rem)', fontWeight: 900, color: '#fff', marginBottom: 10 }}>
            I nostri Sponsor
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.55)', fontSize: 16 }}>Le aziende che rendono possibile TuttoApricena</p>
        </div>
      </div>

      <div style={{ maxWidth: 1000, margin: '0 auto', padding: '48px 24px 80px' }}>
        {/* CTA diventare sponsor */}
        <div style={{
          background: 'var(--color-primary)', borderRadius: 22, padding: '40px 36px',
          textAlign: 'center', marginBottom: 52,
        }}>
          <div style={{ width: 64, height: 64, borderRadius: 18, background: 'rgba(232,168,56,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 18px', color: 'var(--color-accent)' }}>
            <Star size={28} fill="currentColor" />
          </div>
          <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(1.4rem, 3vw, 2rem)', fontWeight: 800, color: '#fff', marginBottom: 12 }}>
            Vuoi diventare sponsor?
          </h2>
          <p style={{ color: 'rgba(255,255,255,0.6)', fontSize: 15, lineHeight: 1.7, maxWidth: 500, margin: '0 auto 24px' }}>
            Supporta il portale informativo di Apricena e dai visibilità alla tua attività.
          </p>
          <Link to="/contatti" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            background: 'var(--color-accent)', color: 'var(--color-primary)',
            fontWeight: 700, fontSize: 14, padding: '12px 28px', borderRadius: 50, textDecoration: 'none',
          }}>
            Contattaci per i pacchetti
          </Link>
        </div>

        {/* Packages */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 16, marginBottom: 48 }} className="packages-grid">
          {Object.entries(levelConfig).map(([key, cfg]) => (
            <div key={key} style={{ background: '#fff', borderRadius: 18, padding: '26px 22px', boxShadow: '0 4px 20px rgba(26,26,46,0.08)', border: `2px solid ${cfg.color}20` }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 14 }}>
                <div style={{ width: 40, height: 40, borderRadius: 10, background: `${cfg.color}15`, display: 'flex', alignItems: 'center', justifyContent: 'center', color: cfg.color }}>
                  {cfg.icon}
                </div>
                <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 800, color: cfg.color }}>
                  {cfg.label}
                </h3>
              </div>
              <p style={{ color: 'var(--color-text-muted)', fontSize: 13, lineHeight: 1.7 }}>{cfg.description}</p>
            </div>
          ))}
        </div>

        {/* Sponsor lists */}
        {goldSponsors.length > 0 && (
          <div style={{ marginBottom: 36 }}>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-primary)', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 10 }}>
              <Star size={20} color="#E8A838" fill="#E8A838" /> Sponsor Gold
            </h2>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
              {goldSponsors.map(s => (
                <div key={s.id} style={{
                  background: '#fff', borderRadius: 18, padding: '24px 28px',
                  display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 20,
                  boxShadow: '0 4px 20px rgba(232,168,56,0.1)', border: '2px solid rgba(232,168,56,0.2)',
                }}>
                  <div style={{ width: 56, height: 56, borderRadius: 14, background: 'rgba(232,168,56,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'var(--color-accent)', fontWeight: 900, fontSize: 22, flexShrink: 0 }}>
                    {s.nome.charAt(0)}
                  </div>
                  <div style={{ flex: 1, minWidth: 180 }}>
                    <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 18, fontWeight: 700, color: 'var(--color-primary)', marginBottom: 4 }}>{s.nome}</h3>
                    <p style={{ color: 'var(--color-text-muted)', fontSize: 14 }}>{s.descrizione}</p>
                  </div>
                  {s.sitoWeb && (
                    <a href={s.sitoWeb} target="_blank" rel="noopener noreferrer" style={{ display: 'inline-flex', alignItems: 'center', gap: 6, color: 'var(--color-accent)', fontWeight: 600, fontSize: 13, textDecoration: 'none' }}>
                      <ExternalLink size={13} /> Visita il sito
                    </a>
                  )}
                </div>
              ))}
            </div>
          </div>
        )}

        {silverSponsors.length > 0 && (
          <div style={{ marginBottom: 36 }}>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-primary)', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 10 }}>
              <Award size={20} color="#6B7280" /> Sponsor Silver
            </h2>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: 14 }} className="silver-grid">
              {silverSponsors.map(s => (
                <div key={s.id} style={{ background: '#fff', borderRadius: 16, padding: '20px 22px', boxShadow: '0 2px 12px rgba(26,26,46,0.07)', display: 'flex', gap: 14, alignItems: 'center' }}>
                  <div style={{ width: 46, height: 46, borderRadius: 12, background: '#F3F4F6', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#6B7280', fontWeight: 900, fontSize: 18, flexShrink: 0 }}>
                    {s.nome.charAt(0)}
                  </div>
                  <div>
                    <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 16, fontWeight: 700, color: 'var(--color-primary)', marginBottom: 3 }}>{s.nome}</h3>
                    <p style={{ color: 'var(--color-text-muted)', fontSize: 12, lineHeight: 1.5 }} className="line-clamp-2">{s.descrizione}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {bronzeSponsors.length > 0 && (
          <div>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-primary)', marginBottom: 16, display: 'flex', alignItems: 'center', gap: 10 }}>
              <Shield size={20} color="#92400E" /> Sponsor Bronze
            </h2>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 12 }} className="bronze-grid">
              {bronzeSponsors.map(s => (
                <div key={s.id} style={{ background: '#fff', borderRadius: 14, padding: '16px 18px', boxShadow: '0 2px 10px rgba(26,26,46,0.06)', textAlign: 'center' }}>
                  <div style={{ width: 40, height: 40, borderRadius: 10, background: 'rgba(146,64,14,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#92400E', fontWeight: 900, fontSize: 16, margin: '0 auto 10px' }}>
                    {s.nome.charAt(0)}
                  </div>
                  <h4 style={{ fontSize: 14, fontWeight: 700, color: 'var(--color-primary)' }}>{s.nome}</h4>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      <style>{`
        @media (max-width: 720px) {
          .packages-grid { grid-template-columns: 1fr !important; }
          .silver-grid { grid-template-columns: 1fr !important; }
          .bronze-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
      `}</style>
    </main>
  );
}
