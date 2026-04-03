import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, ChevronDown, MapPin } from 'lucide-react';

const slides = [
  {
    bg: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1920&q=90',
    label: 'La Città della Pietra',
    sublabel: 'Apricena — Porta del Gargano',
    tag: 'Cave di Pietra',
  },
  {
    bg: 'https://images.unsplash.com/photo-1534430480872-3498386e7856?w=1920&q=90',
    label: 'Tradizione e Devozione',
    sublabel: 'Madonna SS. Incoronata',
    tag: 'Cultura & Fede',
  },
  {
    bg: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1920&q=90',
    label: 'Sapori Autentici',
    sublabel: 'Cucina del Tavoliere e del Gargano',
    tag: 'Gastronomia',
  },
];

export default function HeroSection() {
  const [current, setCurrent] = useState(0);
  const [loaded, setLoaded] = useState(false);
  const [fading, setFading] = useState(false);

  useEffect(() => {
    const t = setTimeout(() => setLoaded(true), 100);
    return () => clearTimeout(t);
  }, []);

  useEffect(() => {
    const interval = setInterval(() => {
      setFading(true);
      setTimeout(() => {
        setCurrent((c) => (c + 1) % slides.length);
        setFading(false);
      }, 800);
    }, 7000);
    return () => clearInterval(interval);
  }, []);

  return (
    <section style={{
      position: 'relative',
      height: '100vh',
      minHeight: 640,
      maxHeight: 1000,
      display: 'flex',
      alignItems: 'center',
      overflow: 'hidden',
    }}>

      {/* Background images */}
      {slides.map((slide, i) => (
        <div
          key={i}
          style={{
            position: 'absolute',
            inset: 0,
            opacity: current === i && !fading ? 1 : 0,
            transition: 'opacity 1.2s cubic-bezier(0.4,0,0.2,1)',
          }}
        >
          <img
            src={slide.bg}
            alt={slide.label}
            style={{
              width: '100%',
              height: '100%',
              objectFit: 'cover',
              transform: current === i ? 'scale(1.08)' : 'scale(1)',
              transition: 'transform 9s ease-out',
            }}
            loading={i === 0 ? 'eager' : 'lazy'}
          />
        </div>
      ))}

      {/* Rich gradient overlays */}
      <div style={{
        position: 'absolute', inset: 0,
        background: 'linear-gradient(105deg, rgba(14,14,24,0.96) 0%, rgba(14,14,24,0.72) 50%, rgba(14,14,24,0.25) 100%)',
      }} />
      <div style={{
        position: 'absolute', inset: 0,
        background: 'linear-gradient(to top, rgba(14,14,24,0.9) 0%, transparent 45%)',
      }} />
      {/* Gold warm tint */}
      <div style={{
        position: 'absolute', inset: 0,
        background: 'radial-gradient(ellipse at 30% 60%, rgba(212,168,67,0.08) 0%, transparent 60%)',
      }} />

      {/* Vertical accent line */}
      <div style={{
        position: 'absolute', left: 0, top: 0, bottom: 0, width: 3,
        background: 'linear-gradient(to bottom, transparent 10%, var(--color-accent) 40%, var(--color-accent) 60%, transparent 90%)',
        opacity: loaded ? 0.8 : 0,
        transition: 'opacity 2s ease',
      }} />

      {/* Decorative geometric: rotated square */}
      <div style={{
        position: 'absolute', top: '15%', right: '8%',
        width: 180, height: 180,
        border: '1px solid rgba(212,168,67,0.12)',
        transform: 'rotate(45deg)',
        opacity: loaded ? 1 : 0,
        transition: 'opacity 2s ease 0.5s',
        borderRadius: 4,
      }} />
      <div style={{
        position: 'absolute', top: 'calc(15% + 20px)', right: 'calc(8% + 20px)',
        width: 140, height: 140,
        border: '1px solid rgba(212,168,67,0.07)',
        transform: 'rotate(45deg)',
        opacity: loaded ? 1 : 0,
        transition: 'opacity 2s ease 0.7s',
        borderRadius: 4,
      }} />

      {/* Dot grid */}
      <div style={{
        position: 'absolute', bottom: '20%', right: '5%',
        display: 'grid', gridTemplateColumns: 'repeat(6, 1fr)', gap: 12,
        opacity: loaded ? 0.2 : 0,
        transition: 'opacity 2.5s ease 1s',
      }}>
        {Array.from({ length: 24 }).map((_, i) => (
          <div key={i} style={{
            width: 3, height: 3, borderRadius: '50%',
            background: 'var(--color-accent)',
          }} />
        ))}
      </div>

      {/* Content */}
      <div style={{
        position: 'relative', zIndex: 10,
        maxWidth: 1280, margin: '0 auto',
        padding: '0 32px', paddingTop: 80, width: '100%',
      }}>
        <div style={{
          maxWidth: 700,
          opacity: loaded ? 1 : 0,
          transform: loaded ? 'translateY(0)' : 'translateY(50px)',
          transition: 'opacity 1s ease, transform 1s ease',
        }}>

          {/* Location pill */}
          <div style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            background: 'rgba(212,168,67,0.12)',
            border: '1px solid rgba(212,168,67,0.3)',
            borderRadius: 50, padding: '6px 16px',
            marginBottom: 28,
            backdropFilter: 'blur(8px)',
          }}>
            <MapPin size={11} color="var(--color-accent)" />
            <span style={{
              color: 'var(--color-accent)', fontSize: 10,
              fontWeight: 700, letterSpacing: '0.18em', textTransform: 'uppercase',
              fontFamily: 'var(--font-body)',
            }}>
              Apricena · Foggia · Puglia · Italia
            </span>
          </div>

          {/* Tag from slide */}
          <div style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            marginBottom: 16, marginLeft: 8,
            opacity: 0.7,
          }}>
            <span style={{
              color: 'rgba(255,255,255,0.5)', fontSize: 10,
              fontWeight: 600, letterSpacing: '0.15em', textTransform: 'uppercase',
              fontFamily: 'var(--font-body)',
            }}>
              {slides[current].tag}
            </span>
          </div>

          {/* Headline */}
          <h1 style={{
            fontFamily: 'var(--font-display)',
            fontSize: 'clamp(3rem, 7.5vw, 6.5rem)',
            fontWeight: 300,
            color: '#fff',
            lineHeight: 1.0,
            marginBottom: 12,
            letterSpacing: '-0.01em',
          }}>
            {slides[current].label}
          </h1>
          <h2 style={{
            fontFamily: 'var(--font-display)',
            fontSize: 'clamp(1.2rem, 2.5vw, 2rem)',
            fontWeight: 400,
            fontStyle: 'italic',
            color: 'var(--color-accent)',
            marginBottom: 28,
            letterSpacing: '0.02em',
            transition: 'opacity 0.5s ease',
          }}>
            {slides[current].sublabel}
          </h2>

          {/* Separator */}
          <div style={{
            display: 'flex', alignItems: 'center', gap: 16, marginBottom: 28,
            opacity: loaded ? 1 : 0, transition: 'opacity 1.5s ease 0.3s',
          }}>
            <div style={{ height: 1, width: 48, background: 'var(--color-accent)' }} />
            <span style={{
              color: 'rgba(255,255,255,0.4)', fontSize: 11,
              fontFamily: 'var(--font-body)', letterSpacing: '0.12em', textTransform: 'uppercase',
            }}>
              Il tuo portale di riferimento
            </span>
          </div>

          {/* Subtitle */}
          <p style={{
            color: 'rgba(255,255,255,0.65)', fontSize: 17,
            lineHeight: 1.75, marginBottom: 44,
            maxWidth: 500, fontFamily: 'var(--font-body)',
          }}>
            Notizie, eventi, servizi e tutto quello che devi sapere sulla città della pietra,
            porta del Parco Nazionale del Gargano.
          </p>

          {/* CTA */}
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 16, marginBottom: 56 }}>
            <Link to="/notizie" className="btn-primary">
              Ultime notizie <ArrowRight size={16} />
            </Link>
            <Link to="/eventi" className="btn-outline">
              Scopri gli eventi
            </Link>
          </div>

          {/* Stats */}
          <div style={{
            display: 'flex', flexWrap: 'wrap', gap: 36,
            paddingTop: 28, borderTop: '1px solid rgba(255,255,255,0.08)',
          }}>
            {[
              { num: '12.500', label: 'Abitanti' },
              { num: '2°', label: 'Polo marmifero d\'Italia' },
              { num: '1764', label: 'Anno del Santuario' },
            ].map((stat) => (
              <div key={stat.label}>
                <span style={{
                  display: 'block',
                  fontFamily: 'var(--font-display)',
                  fontSize: 30, fontWeight: 700,
                  color: 'var(--color-accent)',
                  letterSpacing: '-0.02em',
                }}>
                  {stat.num}
                </span>
                <span style={{
                  display: 'block',
                  color: 'rgba(255,255,255,0.4)',
                  fontFamily: 'var(--font-body)',
                  fontSize: 11, marginTop: 2,
                  letterSpacing: '0.05em',
                }}>
                  {stat.label}
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Slide indicators */}
      <div style={{
        position: 'absolute', bottom: 88, left: 32,
        display: 'flex', gap: 8, zIndex: 10,
        opacity: loaded ? 1 : 0, transition: 'opacity 2s ease 1s',
      }}>
        {slides.map((_, i) => (
          <button
            key={i}
            onClick={() => { setFading(true); setTimeout(() => { setCurrent(i); setFading(false); }, 400); }}
            style={{
              width: current === i ? 32 : 8,
              height: 4,
              borderRadius: 2,
              background: current === i ? 'var(--color-accent)' : 'rgba(255,255,255,0.3)',
              border: 'none', cursor: 'pointer',
              transition: 'all 0.4s ease', padding: 0,
            }}
            aria-label={`Slide ${i + 1}`}
          />
        ))}
      </div>

      {/* Slide counter */}
      <div style={{
        position: 'absolute', bottom: 88, right: 32,
        zIndex: 10, opacity: loaded ? 0.5 : 0,
        transition: 'opacity 2s ease 1s',
      }}>
        <span style={{
          fontFamily: 'var(--font-display)',
          fontSize: 28, fontWeight: 700, color: 'var(--color-accent)',
          lineHeight: 1,
        }}>
          {String(current + 1).padStart(2, '0')}
        </span>
        <span style={{ color: 'rgba(255,255,255,0.3)', fontSize: 14, margin: '0 6px' }}>/</span>
        <span style={{ color: 'rgba(255,255,255,0.3)', fontSize: 14 }}>
          {String(slides.length).padStart(2, '0')}
        </span>
      </div>

      {/* Scroll down */}
      <div style={{
        position: 'absolute', bottom: 28, left: '50%',
        transform: 'translateX(-50%)',
        zIndex: 10, display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 6,
        opacity: loaded ? 0.5 : 0, transition: 'opacity 2s ease 1.5s',
      }}>
        <span style={{
          color: 'rgba(255,255,255,0.5)', fontSize: 9,
          letterSpacing: '0.2em', textTransform: 'uppercase',
          fontFamily: 'var(--font-body)',
        }}>Scopri</span>
        <ChevronDown size={16} color="rgba(255,255,255,0.5)"
          style={{ animation: 'pulse-dot 2s ease infinite' }} />
      </div>
    </section>
  );
}
