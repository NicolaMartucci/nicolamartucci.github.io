import { Link } from 'react-router-dom';
import { Phone, Clock, ArrowRight, Moon, ExternalLink } from 'lucide-react';

// Le 4 farmacie reali di Apricena
// Fonte: farmaciediturno.org/comune.asp?cod=71004
const farmacieReali = [
  {
    id: 1,
    nome: "Farmacia Florio",
    indirizzo: "Corso Garibaldi, 94/96",
    telefono: "0882 641322",
    orario: "08:00-13:00 / 16:30-20:30",
  },
  {
    id: 2,
    nome: "Farmacia dell'Incoronata",
    indirizzo: "Via Roma, 6",
    telefono: "0882 641126",
    orario: "08:00-13:00 / 16:30-20:30",
  },
  {
    id: 3,
    nome: "Farmacia della Luna",
    indirizzo: "Viale Aldo Moro, 134/C/8",
    telefono: "0882 643574",
    orario: "08:30-13:00 / 16:30-20:30",
  },
  {
    id: 4,
    nome: "Farmacia Matarese",
    indirizzo: "Viale Papa Giovanni XXIII, 33/A",
    telefono: "0882 641134",
    orario: "08:00-13:00 / 16:30-20:30",
    notturno: true,
  },
];

// Rotazione turno: ogni farmacia fa turno a rotazione
function getFarmaciaOggi() {
  const start = new Date('2025-06-11');
  const oggi = new Date();
  const diffDays = Math.floor((oggi.getTime() - start.getTime()) / (1000 * 60 * 60 * 24));
  const idx = ((diffDays % 4) + 4) % 4;
  return farmacieReali[idx];
}

export default function FarmacieWidget() {
  const oggi = getFarmaciaOggi();

  return (
    <section style={{ background: 'var(--color-primary)', padding: '0' }}>
      {/* Top border gold */}
      <div style={{ height: 1, background: 'linear-gradient(to right, transparent, rgba(212,168,67,0.3), transparent)' }} />

      <div style={{ maxWidth: 1280, margin: '0 auto', padding: '20px 24px' }}>
        <div style={{
          display: 'grid',
          gridTemplateColumns: 'auto 1fr auto',
          alignItems: 'center',
          gap: 24,
        }} className="farmacia-widget-grid">

          {/* Left: label */}
          <div style={{ display: 'flex', alignItems: 'center', gap: 14, flexShrink: 0 }}>
            <div style={{
              width: 44, height: 44, borderRadius: 10,
              background: 'linear-gradient(135deg, #16A34A, #22C55E)',
              display: 'flex', alignItems: 'center', justifyContent: 'center',
              boxShadow: '0 4px 16px rgba(22,163,74,0.35)',
              flexShrink: 0,
              fontSize: 20, fontWeight: 700, color: '#fff',
            }}>
              ✚
            </div>
            <div>
              <div style={{
                fontSize: 9, fontWeight: 700, color: '#16A34A',
                letterSpacing: '0.15em', textTransform: 'uppercase',
                fontFamily: 'var(--font-body)', marginBottom: 2,
              }}>
                Farmacia di turno oggi
              </div>
              <div style={{
                fontSize: 15, fontWeight: 700, color: '#fff',
                fontFamily: 'var(--font-body)',
              }}>
                {oggi.nome}
              </div>
            </div>
          </div>

          {/* Center: info */}
          <div style={{
            display: 'flex', alignItems: 'center', gap: 20,
            flexWrap: 'wrap',
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'rgba(255,255,255,0.5)', fontSize: 12, fontFamily: 'var(--font-body)' }}>
              <span style={{ color: 'rgba(255,255,255,0.3)', fontSize: 14 }}>📍</span>
              {oggi.indirizzo} — Apricena (FG)
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'rgba(255,255,255,0.5)', fontSize: 12, fontFamily: 'var(--font-body)' }}>
              <Phone size={11} color="rgba(255,255,255,0.4)" />
              {oggi.telefono}
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 7, color: 'rgba(255,255,255,0.5)', fontSize: 12, fontFamily: 'var(--font-body)' }}>
              <Clock size={11} color="rgba(255,255,255,0.4)" />
              {oggi.orario}
            </div>
            {oggi.notturno && (
              <div style={{
                display: 'flex', alignItems: 'center', gap: 5,
                background: 'rgba(22,163,74,0.12)',
                border: '1px solid rgba(22,163,74,0.25)',
                borderRadius: 50, padding: '3px 10px',
              }}>
                <Moon size={10} color="#16A34A" />
                <span style={{ color: '#16A34A', fontSize: 10, fontWeight: 700, fontFamily: 'var(--font-body)', letterSpacing: '0.08em' }}>Servizio notturno</span>
              </div>
            )}
          </div>

          {/* Right: links */}
          <div style={{ display: 'flex', alignItems: 'center', gap: 10, flexShrink: 0 }}>
            <a
              href="https://www.farmaciediturno.org/comune.asp?cod=71004"
              target="_blank"
              rel="noopener noreferrer"
              style={{
                display: 'inline-flex', alignItems: 'center', gap: 6,
                color: 'rgba(255,255,255,0.4)', fontSize: 11,
                fontFamily: 'var(--font-body)', fontWeight: 600,
                textDecoration: 'none', transition: 'color 0.2s',
                border: '1px solid rgba(255,255,255,0.1)',
                borderRadius: 8, padding: '7px 12px',
              }}
              onMouseEnter={e => (e.currentTarget as HTMLElement).style.color = 'var(--color-accent)'}
              onMouseLeave={e => (e.currentTarget as HTMLElement).style.color = 'rgba(255,255,255,0.4)'}
            >
              <ExternalLink size={11} />
              Turni in tempo reale
            </a>
            <Link
              to="/farmacie"
              style={{
                display: 'inline-flex', alignItems: 'center', gap: 6,
                background: 'rgba(22,163,74,0.15)',
                border: '1px solid rgba(22,163,74,0.25)',
                color: '#4ade80', fontSize: 11,
                fontFamily: 'var(--font-body)', fontWeight: 700,
                textDecoration: 'none', transition: 'all 0.2s',
                borderRadius: 8, padding: '7px 14px',
              }}
              onMouseEnter={e => {
                (e.currentTarget as HTMLElement).style.background = 'rgba(22,163,74,0.25)';
              }}
              onMouseLeave={e => {
                (e.currentTarget as HTMLElement).style.background = 'rgba(22,163,74,0.15)';
              }}
            >
              Tutte le farmacie <ArrowRight size={11} />
            </Link>
          </div>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .farmacia-widget-grid { grid-template-columns: 1fr !important; gap: 12px !important; }
        }
      `}</style>
    </section>
  );
}
