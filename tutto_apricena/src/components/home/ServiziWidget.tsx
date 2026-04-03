import { Link } from 'react-router-dom';
import { Building, Shield, Heart, Train, BookOpen, Mail, ArrowRight, Phone } from 'lucide-react';
import { servizi } from '../../data/mockData';

const iconMap: Record<string, React.ReactNode> = {
  building: <Building size={22} />,
  shield: <Shield size={22} />,
  heart: <Heart size={22} />,
  train: <Train size={22} />,
  book: <BookOpen size={22} />,
  mail: <Mail size={22} />,
  phone: <Phone size={22} />,
};

export default function ServiziWidget() {
  const topServizi = servizi.slice(0, 6);

  return (
    <section style={{ padding: '80px 0', background: '#fff' }}>
      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '0 24px' }}>

        {/* Header */}
        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 40, gap: 16, flexWrap: 'wrap' }}>
          <div>
            <span className="section-eyebrow">Informazioni utili</span>
            <h2 className="section-title">Servizi Utili</h2>
            <p style={{ color: 'var(--color-text-muted)', marginTop: 6, fontSize: 15 }}>
              Tutto quello di cui hai bisogno, a portata di click
            </p>
          </div>
          <Link to="/servizi" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            color: 'var(--color-primary)', fontSize: 14, fontWeight: 600, textDecoration: 'none',
          }}
            onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
            onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)'}
          >
            Tutti i servizi <ArrowRight size={16} />
          </Link>
        </div>

        {/* Grid */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 14 }} className="servizi-grid">
          {topServizi.map((servizio) => (
            <Link
              key={servizio.id}
              to="/servizi"
              style={{
                display: 'flex',
                alignItems: 'flex-start',
                gap: 16,
                padding: '18px 20px',
                borderRadius: 16,
                background: 'var(--color-surface)',
                textDecoration: 'none',
                transition: 'all 0.25s ease',
                border: '1px solid transparent',
              }}
              onMouseEnter={e => {
                (e.currentTarget as HTMLElement).style.background = 'var(--color-primary)';
                (e.currentTarget as HTMLElement).style.borderColor = 'transparent';
                (e.currentTarget as HTMLElement).style.transform = 'translateY(-2px)';
                (e.currentTarget as HTMLElement).style.boxShadow = '0 10px 28px rgba(26,26,46,0.15)';
              }}
              onMouseLeave={e => {
                (e.currentTarget as HTMLElement).style.background = 'var(--color-surface)';
                (e.currentTarget as HTMLElement).style.borderColor = 'transparent';
                (e.currentTarget as HTMLElement).style.transform = 'translateY(0)';
                (e.currentTarget as HTMLElement).style.boxShadow = 'none';
              }}
            >
              <div style={{
                width: 46, height: 46,
                borderRadius: 12,
                background: 'rgba(232,168,56,0.12)',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                color: 'var(--color-accent)',
                flexShrink: 0,
                transition: 'background 0.25s',
              }}>
                {iconMap[servizio.icona] || <Building size={22} />}
              </div>
              <div style={{ minWidth: 0 }}>
                <span style={{ display: 'block', fontSize: 10, fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.12em', color: 'var(--color-text-muted)', marginBottom: 3 }}>
                  {servizio.categoria}
                </span>
                <h4 style={{ fontSize: 14, fontWeight: 700, color: 'var(--color-primary)', marginBottom: 2 }}>
                  {servizio.nome}
                </h4>
                <p style={{ fontSize: 12, color: 'var(--color-text-muted)' }} className="line-clamp-1">
                  {servizio.orari}
                </p>
              </div>
            </Link>
          ))}
        </div>

        <div style={{ marginTop: 28, textAlign: 'center' }}>
          <Link to="/servizi" style={{
            display: 'inline-flex', alignItems: 'center', gap: 8,
            border: '2px solid var(--color-primary)', color: 'var(--color-primary)',
            fontSize: 14, fontWeight: 600, padding: '12px 28px', borderRadius: 50,
            textDecoration: 'none', transition: 'all 0.2s',
          }}
            onMouseEnter={e => {
              (e.currentTarget as HTMLElement).style.background = 'var(--color-primary)';
              (e.currentTarget as HTMLElement).style.color = '#fff';
            }}
            onMouseLeave={e => {
              (e.currentTarget as HTMLElement).style.background = 'transparent';
              (e.currentTarget as HTMLElement).style.color = 'var(--color-primary)';
            }}
          >
            Vedi tutti i servizi <ArrowRight size={15} />
          </Link>
        </div>
      </div>

      <style>{`
        @media (max-width: 900px) { .servizi-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 580px) { .servizi-grid { grid-template-columns: 1fr !important; } }
      `}</style>
    </section>
  );
}
