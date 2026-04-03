import { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X } from 'lucide-react';

const navLinks = [
  { label: 'Notizie', href: '/notizie' },
  { label: 'Eventi', href: '/eventi' },
  { label: 'Farmacie', href: '/farmacie' },
  { label: 'Servizi', href: '/servizi' },
  { label: 'Locali', href: '/locali' },
  { label: 'Sponsor', href: '/sponsor' },
];

export default function Navbar() {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const location = useLocation();
  const isHome = location.pathname === '/';

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 60);
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => { setOpen(false); }, [location]);
  useEffect(() => {
    document.body.style.overflow = open ? 'hidden' : '';
    return () => { document.body.style.overflow = ''; };
  }, [open]);

  const isTransparent = isHome && !scrolled && !open;

  return (
    <header style={{
      position: 'fixed', top: 0, left: 0, right: 0,
      zIndex: 1000,
      transition: 'background 0.4s ease, box-shadow 0.4s ease, border-bottom 0.4s ease',
      background: isTransparent ? 'transparent' : 'rgba(14,14,24,0.96)',
      backdropFilter: isTransparent ? 'none' : 'blur(20px)',
      borderBottom: isTransparent ? 'none' : '1px solid rgba(212,168,67,0.1)',
      boxShadow: isTransparent ? 'none' : '0 4px 40px rgba(0,0,0,0.3)',
    }}>
      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px' }}>
        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', height: 72 }}>

          {/* Logo */}
          <Link to="/" style={{ display: 'flex', alignItems: 'center', gap: 12, textDecoration: 'none' }}>
            <div style={{
              width: 38, height: 38,
              background: 'linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-light) 100%)',
              borderRadius: 10,
              display: 'flex', alignItems: 'center', justifyContent: 'center',
              boxShadow: '0 4px 16px rgba(212,168,67,0.4)',
              flexShrink: 0,
            }}>
              <span style={{ color: 'var(--color-primary)', fontFamily: 'var(--font-display)', fontWeight: 700, fontSize: 15, letterSpacing: '-0.05em' }}>A</span>
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', lineHeight: 1 }}>
              <span style={{
                fontFamily: 'var(--font-display)',
                fontSize: 20, fontWeight: 600,
                color: '#fff', letterSpacing: '-0.03em',
              }}>
                Tutto<span style={{ color: 'var(--color-accent)' }}>Apricena</span>
              </span>
              <span style={{
                fontFamily: 'var(--font-body)',
                fontSize: 9, fontWeight: 500,
                color: 'rgba(255,255,255,0.35)',
                letterSpacing: '0.15em', textTransform: 'uppercase',
                marginTop: 1,
              }}>
                Portale Informativo
              </span>
            </div>
          </Link>

          {/* Desktop Nav */}
          <nav style={{ display: 'flex', alignItems: 'center', gap: 2 }} className="desktop-nav">
            {navLinks.map((link) => {
              const active = location.pathname.startsWith(link.href);
              return (
                <Link
                  key={link.href}
                  to={link.href}
                  style={{
                    padding: '8px 16px',
                    borderRadius: 8,
                    fontSize: 13,
                    fontWeight: 600,
                    fontFamily: 'var(--font-body)',
                    letterSpacing: '0.02em',
                    color: active ? 'var(--color-accent)' : 'rgba(255,255,255,0.7)',
                    background: active ? 'rgba(212,168,67,0.1)' : 'transparent',
                    textDecoration: 'none',
                    transition: 'all 0.2s ease',
                    borderBottom: active ? '2px solid var(--color-accent)' : '2px solid transparent',
                  }}
                  onMouseEnter={e => {
                    if (!active) {
                      (e.target as HTMLElement).style.color = '#fff';
                      (e.target as HTMLElement).style.background = 'rgba(255,255,255,0.06)';
                    }
                  }}
                  onMouseLeave={e => {
                    if (!active) {
                      (e.target as HTMLElement).style.color = 'rgba(255,255,255,0.7)';
                      (e.target as HTMLElement).style.background = 'transparent';
                    }
                  }}
                >
                  {link.label}
                </Link>
              );
            })}
          </nav>

          {/* Desktop actions */}
          <div style={{ display: 'flex', alignItems: 'center', gap: 8 }} className="desktop-nav">
            <Link to="/chi-siamo" style={{
              fontSize: 12, fontWeight: 600,
              fontFamily: 'var(--font-body)',
              color: 'rgba(255,255,255,0.5)',
              textDecoration: 'none', padding: '6px 12px', borderRadius: 8,
              transition: 'color 0.2s',
            }}>
              Chi siamo
            </Link>
            <Link to="/contatti" style={{
              fontSize: 12, fontWeight: 700,
              fontFamily: 'var(--font-body)',
              background: 'linear-gradient(135deg, var(--color-accent), var(--color-accent-light))',
              color: 'var(--color-primary)',
              textDecoration: 'none', padding: '9px 22px', borderRadius: 8,
              transition: 'all 0.25s ease',
              boxShadow: '0 4px 14px rgba(212,168,67,0.3)',
            }}>
              Contatti
            </Link>
          </div>

          {/* Mobile hamburger */}
          <button
            onClick={() => setOpen(!open)}
            aria-label={open ? 'Chiudi menu' : 'Apri menu'}
            className="mobile-menu-btn"
            style={{
              background: 'none', border: 'none', color: '#fff',
              cursor: 'pointer', padding: 8, borderRadius: 8, display: 'none',
            }}
          >
            {open ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      <div style={{
        overflow: 'hidden',
        maxHeight: open ? 600 : 0,
        transition: 'max-height 0.35s ease',
        background: 'rgba(14,14,24,0.99)',
        borderTop: open ? '1px solid rgba(212,168,67,0.1)' : 'none',
      }}>
        <div style={{ padding: '16px 20px 28px' }}>
          <nav style={{ display: 'flex', flexDirection: 'column', gap: 4, marginBottom: 16 }}>
            {navLinks.map((link) => {
              const active = location.pathname.startsWith(link.href);
              return (
                <Link key={link.href} to={link.href} style={{
                  padding: '13px 16px', borderRadius: 10, fontSize: 16, fontWeight: 600,
                  fontFamily: 'var(--font-body)',
                  color: active ? 'var(--color-accent)' : 'rgba(255,255,255,0.82)',
                  background: active ? 'rgba(212,168,67,0.08)' : 'transparent',
                  textDecoration: 'none', transition: 'all 0.2s',
                }}>
                  {link.label}
                </Link>
              );
            })}
            <Link to="/chi-siamo" style={{
              padding: '13px 16px', borderRadius: 10, fontSize: 16,
              fontWeight: 600, fontFamily: 'var(--font-body)',
              color: 'rgba(255,255,255,0.5)', textDecoration: 'none',
            }}>
              Chi siamo
            </Link>
          </nav>
          <Link to="/contatti" style={{
            display: 'flex', alignItems: 'center', justifyContent: 'center',
            background: 'linear-gradient(135deg, var(--color-accent), var(--color-accent-light))',
            color: 'var(--color-primary)', fontWeight: 700,
            fontFamily: 'var(--font-body)',
            fontSize: 15, padding: '14px 24px', borderRadius: 10,
            textDecoration: 'none',
          }}>
            Contattaci
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 1023px) {
          .desktop-nav { display: none !important; }
          .mobile-menu-btn { display: flex !important; align-items: center; justify-content: center; }
        }
      `}</style>
    </header>
  );
}
