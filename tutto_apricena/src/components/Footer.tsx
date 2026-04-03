import { Link } from 'react-router-dom';
import { Mail, MapPin, ExternalLink } from 'lucide-react';

export default function Footer() {
  return (
    <footer style={{ background: 'var(--color-primary)', color: '#fff', position: 'relative', overflow: 'hidden' }}>
      {/* Top gold border */}
      <div style={{
        height: 2,
        background: 'linear-gradient(to right, transparent, var(--color-accent), var(--color-accent-light), var(--color-accent), transparent)',
      }} />

      {/* Decorative background */}
      <div style={{
        position: 'absolute', top: 0, right: 0, bottom: 0,
        width: '40%',
        background: 'radial-gradient(ellipse at 80% 30%, rgba(212,168,67,0.04) 0%, transparent 60%)',
        pointerEvents: 'none',
      }} />

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '64px 24px 32px', position: 'relative' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '2.2fr 1fr 1fr', gap: 56, marginBottom: 48 }} className="footer-grid">

          {/* Brand */}
          <div>
            <Link to="/" style={{ display: 'inline-flex', alignItems: 'center', gap: 12, textDecoration: 'none', marginBottom: 20 }}>
              <div style={{
                width: 40, height: 40,
                background: 'linear-gradient(135deg, var(--color-accent), var(--color-accent-light))',
                borderRadius: 10,
                display: 'flex', alignItems: 'center', justifyContent: 'center',
              }}>
                <span style={{ color: 'var(--color-primary)', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 18 }}>A</span>
              </div>
              <div>
                <span style={{ fontFamily: 'var(--font-display)', fontSize: 22, fontWeight: 600, color: '#fff', letterSpacing: '-0.03em', display: 'block' }}>
                  Tutto<span style={{ color: 'var(--color-accent)' }}>Apricena</span>
                </span>
                <span style={{ fontFamily: 'var(--font-body)', fontSize: 9, color: 'rgba(255,255,255,0.3)', letterSpacing: '0.15em', textTransform: 'uppercase' }}>
                  Portale Informativo
                </span>
              </div>
            </Link>
            <p style={{ color: 'rgba(255,255,255,0.45)', fontSize: 13.5, lineHeight: 1.8, maxWidth: 360, fontFamily: 'var(--font-body)', marginBottom: 20 }}>
              Il portale informativo indipendente di Apricena — la città della pietra, porta del Gargano.
              Notizie, eventi, servizi e tutto quello che accade nella nostra comunità.
            </p>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8, color: 'rgba(255,255,255,0.35)', fontSize: 12, fontFamily: 'var(--font-body)', marginBottom: 20 }}>
              <MapPin size={12} color="var(--color-accent)" />
              Apricena (FG) · Puglia · Italia
            </div>
            <div style={{ display: 'flex', gap: 10 }}>
              {[
                { label: 'f', aria: 'Facebook' },
                { label: 'ig', aria: 'Instagram' },
              ].map((s) => (
                <a key={s.label} href="#" aria-label={s.aria} style={{
                  width: 36, height: 36, borderRadius: 8,
                  background: 'rgba(255,255,255,0.06)',
                  border: '1px solid rgba(255,255,255,0.08)',
                  display: 'flex', alignItems: 'center', justifyContent: 'center',
                  color: 'rgba(255,255,255,0.5)', fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 11,
                  textDecoration: 'none', transition: 'all 0.2s',
                }}
                  onMouseEnter={e => {
                    (e.currentTarget as HTMLElement).style.background = 'var(--color-accent)';
                    (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)';
                    (e.currentTarget as HTMLElement).style.borderColor = 'var(--color-accent)';
                  }}
                  onMouseLeave={e => {
                    (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,0.06)';
                    (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.5)';
                    (e.currentTarget as HTMLElement).style.borderColor = 'rgba(255,255,255,0.08)';
                  }}
                >
                  {s.label}
                </a>
              ))}
              <a href="mailto:info@tuttoapricena.it" aria-label="Email" style={{
                width: 36, height: 36, borderRadius: 8,
                background: 'rgba(255,255,255,0.06)',
                border: '1px solid rgba(255,255,255,0.08)',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                color: 'rgba(255,255,255,0.5)',
                transition: 'all 0.2s',
              }}
                onMouseEnter={e => {
                  (e.currentTarget as HTMLElement).style.background = 'var(--color-accent)';
                  (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)';
                  (e.currentTarget as HTMLElement).style.borderColor = 'var(--color-accent)';
                }}
                onMouseLeave={e => {
                  (e.currentTarget as HTMLElement).style.background = 'rgba(255,255,255,0.06)';
                  (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.5)';
                  (e.currentTarget as HTMLElement).style.borderColor = 'rgba(255,255,255,0.08)';
                }}
              >
                <Mail size={14} />
              </a>
            </div>
          </div>

          {/* Sezioni */}
          <div>
            <h4 style={{
              fontFamily: 'var(--font-body)',
              fontSize: 10, fontWeight: 700,
              textTransform: 'uppercase', letterSpacing: '0.18em',
              color: 'var(--color-accent)', marginBottom: 20,
            }}>
              Sezioni
            </h4>
            <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: 12 }}>
              {[
                { label: 'Notizie', href: '/notizie' },
                { label: 'Eventi', href: '/eventi' },
                { label: 'Farmacie di turno', href: '/farmacie' },
                { label: 'Servizi Utili', href: '/servizi' },
                { label: 'Locali e Attività', href: '/locali' },
                { label: 'I nostri Sponsor', href: '/sponsor' },
              ].map((l) => (
                <li key={l.href}>
                  <Link to={l.href} style={{
                    color: 'rgba(255,255,255,0.4)', fontSize: 13.5,
                    fontFamily: 'var(--font-body)',
                    textDecoration: 'none', transition: 'color 0.2s',
                    display: 'flex', alignItems: 'center', gap: 6,
                  }}
                    onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
                    onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.4)'}
                  >
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Info */}
          <div>
            <h4 style={{
              fontFamily: 'var(--font-body)',
              fontSize: 10, fontWeight: 700,
              textTransform: 'uppercase', letterSpacing: '0.18em',
              color: 'var(--color-accent)', marginBottom: 20,
            }}>
              Info
            </h4>
            <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: 12, marginBottom: 28 }}>
              {[
                { label: 'Chi Siamo', href: '/chi-siamo' },
                { label: 'Contatti', href: '/contatti' },
                { label: 'Privacy Policy', href: '/privacy' },
                { label: 'Cookie Policy', href: '/cookie' },
              ].map((l) => (
                <li key={l.href}>
                  <Link to={l.href} style={{
                    color: 'rgba(255,255,255,0.4)', fontSize: 13.5,
                    fontFamily: 'var(--font-body)',
                    textDecoration: 'none', transition: 'color 0.2s',
                  }}
                    onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
                    onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.4)'}
                  >
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
            <a href="https://www.comune.apricena.fg.it" target="_blank" rel="noopener noreferrer" style={{
              display: 'inline-flex', alignItems: 'center', gap: 6,
              color: 'rgba(255,255,255,0.3)', fontSize: 11,
              fontFamily: 'var(--font-body)',
              textDecoration: 'none', transition: 'color 0.2s',
              border: '1px solid rgba(255,255,255,0.1)',
              borderRadius: 8, padding: '8px 14px',
            }}
              onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
              onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.3)'}
            >
              <ExternalLink size={11} /> Comune di Apricena
            </a>
          </div>
        </div>

        {/* Bottom bar */}
        <div style={{
          paddingTop: 24,
          borderTop: '1px solid rgba(255,255,255,0.07)',
          display: 'flex', flexWrap: 'wrap',
          alignItems: 'center', justifyContent: 'space-between', gap: 12,
        }}>
          <p style={{ color: 'rgba(255,255,255,0.2)', fontSize: 11.5, fontFamily: 'var(--font-body)' }}>
            © {new Date().getFullYear()} TuttoApricena — Portale informativo indipendente di Apricena (FG)
          </p>
          <p style={{ color: 'rgba(255,255,255,0.2)', fontSize: 11.5, fontFamily: 'var(--font-body)' }}>
            Le notizie provengono da fonti terze citate nei singoli articoli.
          </p>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .footer-grid { grid-template-columns: 1fr !important; gap: 32px !important; }
        }
      `}</style>
    </footer>
  );
}
