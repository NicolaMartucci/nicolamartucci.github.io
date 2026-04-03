import { useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { MapPin, Clock } from 'lucide-react';
import { eventi } from '../data/mockData';
import Badge from '../components/ui/Badge';

const categoryColors: Record<string, string> = {
  Religioso: '#7C3AED',
  Sport: '#16A34A',
  Gastronomia: '#EA580C',
  Cultura: '#0284C7',
  Musica: '#E8A838',
};

function formatDateLong(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('it-IT', {
    day: 'numeric', month: 'long', year: 'numeric',
  });
}

function getDayNum(dateStr: string) {
  return new Date(dateStr).getDate();
}

function getMonthShort(dateStr: string) {
  return new Date(dateStr).toLocaleDateString('it-IT', { month: 'short' }).toUpperCase();
}

/* ─── CALENDAR ─── */
function getMonthDays(year: number, month: number) {
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  return { firstDay: firstDay === 0 ? 6 : firstDay - 1, daysInMonth };
}

function CalendarView() {
  const today = new Date();
  const [viewDate, setViewDate] = useState({ year: today.getFullYear(), month: today.getMonth() });
  const { firstDay, daysInMonth } = getMonthDays(viewDate.year, viewDate.month);
  const monthName = new Date(viewDate.year, viewDate.month).toLocaleDateString('it-IT', {
    month: 'long', year: 'numeric',
  });

  function getEventsForDay(day: number) {
    const dateStr = `${viewDate.year}-${String(viewDate.month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return eventi.filter(e => e.dataInizio <= dateStr && e.dataFine >= dateStr);
  }

  function prevMonth() {
    setViewDate(v => v.month === 0 ? { year: v.year - 1, month: 11 } : { ...v, month: v.month - 1 });
  }
  function nextMonth() {
    setViewDate(v => v.month === 11 ? { year: v.year + 1, month: 0 } : { ...v, month: v.month + 1 });
  }

  const dayLabels = ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'];
  const totalCells = firstDay + daysInMonth;
  const rows = Math.ceil(totalCells / 7);

  return (
    <div style={{
      background: '#fff',
      borderRadius: 16,
      overflow: 'hidden',
      boxShadow: '0 2px 20px rgba(0,0,0,0.08)',
    }}>
      {/* Calendar header */}
      <div style={{
        background: '#1A1A2E',
        padding: '20px 24px',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
      }}>
        <button onClick={prevMonth} style={{
          background: 'rgba(255,255,255,0.1)',
          border: 'none',
          color: '#fff',
          width: 40, height: 40,
          borderRadius: 8,
          fontSize: 22,
          cursor: 'pointer',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
        }}>‹</button>
        <h3 style={{
          fontFamily: "'Playfair Display', serif",
          fontWeight: 700,
          color: '#fff',
          fontSize: 20,
          textTransform: 'capitalize',
          margin: 0,
        }}>{monthName}</h3>
        <button onClick={nextMonth} style={{
          background: 'rgba(255,255,255,0.1)',
          border: 'none',
          color: '#fff',
          width: 40, height: 40,
          borderRadius: 8,
          fontSize: 22,
          cursor: 'pointer',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
        }}>›</button>
      </div>

      {/* Day labels */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(7, 1fr)',
        background: '#F8F6F1',
        borderBottom: '1px solid #EEEAE3',
      }}>
        {dayLabels.map(d => (
          <div key={d} style={{
            textAlign: 'center',
            padding: '12px 4px',
            fontSize: 12,
            fontWeight: 700,
            color: '#6B7280',
            fontFamily: 'Inter, sans-serif',
            letterSpacing: '0.05em',
          }}>{d}</div>
        ))}
      </div>

      {/* Grid */}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(7, 1fr)',
      }}>
        {Array.from({ length: rows * 7 }).map((_, i) => {
          const day = i - firstDay + 1;
          const isValid = day >= 1 && day <= daysInMonth;
          const isToday = isValid && day === today.getDate() && viewDate.month === today.getMonth() && viewDate.year === today.getFullYear();
          const dayEvents = isValid ? getEventsForDay(day) : [];

          return (
            <div key={i} style={{
              borderRight: '1px solid #EEEAE3',
              borderBottom: '1px solid #EEEAE3',
              minHeight: 90,
              padding: '8px 6px',
              background: !isValid ? '#F8F6F1' : isToday ? '#FFF8EC' : '#fff',
            }}>
              {isValid && (
                <>
                  <span style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    width: 28, height: 28,
                    borderRadius: '50%',
                    fontSize: 13,
                    fontWeight: 700,
                    fontFamily: 'Inter, sans-serif',
                    background: isToday ? '#E8A838' : 'transparent',
                    color: isToday ? '#1A1A2E' : '#1A1A2E',
                    marginBottom: 4,
                  }}>{day}</span>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: 2 }}>
                    {dayEvents.slice(0, 2).map(e => (
                      <Link key={e.id} to={`/eventi/${e.slug}`} style={{
                        display: 'block',
                        fontSize: 10,
                        fontWeight: 600,
                        padding: '2px 6px',
                        borderRadius: 4,
                        backgroundColor: categoryColors[e.categoria] || '#E8A838',
                        color: '#fff',
                        textDecoration: 'none',
                        overflow: 'hidden',
                        textOverflow: 'ellipsis',
                        whiteSpace: 'nowrap',
                        fontFamily: 'Inter, sans-serif',
                      }} title={e.titolo}>{e.titolo}</Link>
                    ))}
                    {dayEvents.length > 2 && (
                      <span style={{ fontSize: 10, color: '#6B7280', fontFamily: 'Inter, sans-serif' }}>
                        +{dayEvents.length - 2} altri
                      </span>
                    )}
                  </div>
                </>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
}

/* ─── EVENTO CARD ─── */
function EventCard({ evento }: { evento: typeof eventi[0] }) {
  return (
    <Link to={`/eventi/${evento.slug}`} style={{ textDecoration: 'none' }}>
      <div style={{
        background: '#fff',
        borderRadius: 16,
        overflow: 'hidden',
        boxShadow: '0 2px 12px rgba(0,0,0,0.07)',
        display: 'flex',
        flexDirection: 'column',
        height: '100%',
        transition: 'transform 0.25s ease, box-shadow 0.25s ease',
      }}
        onMouseEnter={e => {
          (e.currentTarget as HTMLDivElement).style.transform = 'translateY(-4px)';
          (e.currentTarget as HTMLDivElement).style.boxShadow = '0 12px 32px rgba(0,0,0,0.14)';
        }}
        onMouseLeave={e => {
          (e.currentTarget as HTMLDivElement).style.transform = 'translateY(0)';
          (e.currentTarget as HTMLDivElement).style.boxShadow = '0 2px 12px rgba(0,0,0,0.07)';
        }}
      >
        {/* Image */}
        <div style={{ position: 'relative', height: 200, overflow: 'hidden', flexShrink: 0 }}>
          <img
            src={evento.immagine}
            alt={evento.titolo}
            style={{ width: '100%', height: '100%', objectFit: 'cover' }}
          />
          <div style={{
            position: 'absolute', inset: 0,
            background: 'linear-gradient(to top, rgba(0,0,0,0.45) 0%, transparent 60%)',
          }} />

          {/* Date badge top-left */}
          <div style={{
            position: 'absolute', top: 12, left: 12,
            background: '#fff',
            borderRadius: 12,
            padding: '8px 14px',
            textAlign: 'center',
            boxShadow: '0 2px 8px rgba(0,0,0,0.15)',
            minWidth: 52,
          }}>
            <div style={{
              fontFamily: 'Inter, sans-serif',
              fontWeight: 900,
              fontSize: 22,
              color: '#E8A838',
              lineHeight: 1,
            }}>{getDayNum(evento.dataInizio)}</div>
            <div style={{
              fontFamily: 'Inter, sans-serif',
              fontWeight: 700,
              fontSize: 11,
              color: '#1A1A2E',
              letterSpacing: '0.05em',
              marginTop: 2,
            }}>{getMonthShort(evento.dataInizio)}</div>
          </div>

          {/* Category badge top-right */}
          <div style={{ position: 'absolute', top: 12, right: 12 }}>
            <Badge color={categoryColors[evento.categoria]} size="md">{evento.categoria}</Badge>
          </div>
        </div>

        {/* Content */}
        <div style={{ padding: '20px', display: 'flex', flexDirection: 'column', flex: 1 }}>
          <h3 style={{
            fontFamily: "'Playfair Display', serif",
            fontWeight: 700,
            fontSize: 18,
            color: '#1A1A2E',
            lineHeight: 1.3,
            marginBottom: 10,
          }}>{evento.titolo}</h3>

          <p style={{
            fontFamily: 'Inter, sans-serif',
            fontSize: 14,
            color: '#6B7280',
            lineHeight: 1.6,
            flex: 1,
            marginBottom: 16,
            display: '-webkit-box',
            WebkitLineClamp: 2,
            WebkitBoxOrient: 'vertical',
            overflow: 'hidden',
          }}>{evento.descrizione}</p>

          {/* Meta */}
          <div style={{
            borderTop: '1px solid #F0EDE8',
            paddingTop: 14,
            display: 'flex',
            flexDirection: 'column',
            gap: 8,
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
              <div style={{
                width: 28, height: 28, borderRadius: 8,
                background: '#FFF8EC',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                flexShrink: 0,
              }}>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#E8A838" strokeWidth="2.5">
                  <rect x="3" y="4" width="18" height="18" rx="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" />
                </svg>
              </div>
              <span style={{ fontFamily: 'Inter, sans-serif', fontSize: 13, color: '#4B5563' }}>
                {formatDateLong(evento.dataInizio)}
                {evento.dataInizio !== evento.dataFine && ` → ${formatDateLong(evento.dataFine)}`}
              </span>
            </div>

            <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
              <div style={{
                width: 28, height: 28, borderRadius: 8,
                background: '#FFF8EC',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                flexShrink: 0,
              }}>
                <Clock size={13} color="#E8A838" />
              </div>
              <span style={{ fontFamily: 'Inter, sans-serif', fontSize: 13, color: '#4B5563' }}>
                {evento.orario}
              </span>
            </div>

            <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
              <div style={{
                width: 28, height: 28, borderRadius: 8,
                background: '#FFF8EC',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                flexShrink: 0,
              }}>
                <MapPin size={13} color="#E8A838" />
              </div>
              <span style={{ fontFamily: 'Inter, sans-serif', fontSize: 13, color: '#4B5563' }}>
                {evento.luogo}
              </span>
            </div>
          </div>
        </div>
      </div>
    </Link>
  );
}

/* ─── LISTA COMPATTA (sotto calendario) ─── */
function EventRow({ evento }: { evento: typeof eventi[0] }) {
  return (
    <Link to={`/eventi/${evento.slug}`} style={{ textDecoration: 'none' }}>
      <div style={{
        display: 'flex',
        alignItems: 'center',
        gap: 16,
        background: '#fff',
        borderRadius: 14,
        padding: '16px 20px',
        boxShadow: '0 1px 8px rgba(0,0,0,0.06)',
        transition: 'box-shadow 0.2s ease, transform 0.2s ease',
      }}
        onMouseEnter={e => {
          (e.currentTarget as HTMLDivElement).style.boxShadow = '0 6px 20px rgba(0,0,0,0.12)';
          (e.currentTarget as HTMLDivElement).style.transform = 'translateX(4px)';
        }}
        onMouseLeave={e => {
          (e.currentTarget as HTMLDivElement).style.boxShadow = '0 1px 8px rgba(0,0,0,0.06)';
          (e.currentTarget as HTMLDivElement).style.transform = 'translateX(0)';
        }}
      >
        {/* Date box */}
        <div style={{
          width: 54, height: 54,
          background: '#1A1A2E',
          borderRadius: 12,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          flexShrink: 0,
        }}>
          <span style={{
            fontFamily: 'Inter, sans-serif',
            fontWeight: 900,
            fontSize: 20,
            color: '#E8A838',
            lineHeight: 1,
          }}>{getDayNum(evento.dataInizio)}</span>
          <span style={{
            fontFamily: 'Inter, sans-serif',
            fontWeight: 700,
            fontSize: 10,
            color: '#fff',
            letterSpacing: '0.06em',
            marginTop: 2,
          }}>{getMonthShort(evento.dataInizio)}</span>
        </div>

        {/* Info */}
        <div style={{ flex: 1, minWidth: 0 }}>
          <h4 style={{
            fontFamily: "'Playfair Display', serif",
            fontWeight: 700,
            fontSize: 15,
            color: '#1A1A2E',
            margin: 0,
            overflow: 'hidden',
            textOverflow: 'ellipsis',
            whiteSpace: 'nowrap',
          }}>{evento.titolo}</h4>
          <p style={{
            fontFamily: 'Inter, sans-serif',
            fontSize: 12,
            color: '#6B7280',
            marginTop: 4,
            overflow: 'hidden',
            textOverflow: 'ellipsis',
            whiteSpace: 'nowrap',
          }}>{evento.luogo} · {evento.orario}</p>
        </div>

        {/* Badge */}
        <div style={{ flexShrink: 0 }}>
          <Badge color={categoryColors[evento.categoria]} size="sm">{evento.categoria}</Badge>
        </div>
      </div>
    </Link>
  );
}

/* ─── MAIN PAGE ─── */
export default function Eventi() {
  const [searchParams, setSearchParams] = useSearchParams();
  const view = searchParams.get('view') || 'list';

  return (
    <main style={{ minHeight: '100vh', background: '#F8F6F1' }}>

      {/* ── HERO HEADER ── */}
      <div style={{
        background: 'linear-gradient(135deg, #1A1A2E 0%, #2D2D4E 100%)',
        paddingTop: 120,
        paddingBottom: 60,
      }}>
        <div style={{ maxWidth: 1200, margin: '0 auto', padding: '0 24px' }}>
          <span style={{
            fontFamily: 'Inter, sans-serif',
            fontWeight: 700,
            fontSize: 12,
            letterSpacing: '0.15em',
            textTransform: 'uppercase',
            color: '#E8A838',
            display: 'block',
            marginBottom: 12,
          }}>TuttoApricena</span>

          <h1 style={{
            fontFamily: "'Playfair Display', serif",
            fontWeight: 800,
            fontSize: 'clamp(36px, 5vw, 56px)',
            color: '#fff',
            margin: 0,
            lineHeight: 1.1,
          }}>Eventi</h1>

          <p style={{
            fontFamily: 'Inter, sans-serif',
            fontSize: 18,
            color: 'rgba(255,255,255,0.55)',
            marginTop: 12,
          }}>Tutti gli eventi e le manifestazioni di Apricena</p>
        </div>
      </div>

      {/* ── CONTENT ── */}
      <div style={{ maxWidth: 1200, margin: '0 auto', padding: '48px 24px' }}>

        {/* ── VIEW TOGGLE TABS ── */}
        <div style={{
          display: 'inline-flex',
          background: '#fff',
          borderRadius: 14,
          padding: 6,
          boxShadow: '0 2px 12px rgba(0,0,0,0.08)',
          marginBottom: 40,
          gap: 4,
        }}>
          <button
            onClick={() => setSearchParams({})}
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 8,
              padding: '10px 24px',
              borderRadius: 10,
              border: 'none',
              fontFamily: 'Inter, sans-serif',
              fontWeight: 600,
              fontSize: 14,
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              background: view !== 'calendar' ? '#1A1A2E' : 'transparent',
              color: view !== 'calendar' ? '#fff' : '#6B7280',
            }}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke={view !== 'calendar' ? '#E8A838' : '#6B7280'} strokeWidth="2.5">
              <line x1="8" y1="6" x2="21" y2="6" /><line x1="8" y1="12" x2="21" y2="12" />
              <line x1="8" y1="18" x2="21" y2="18" /><line x1="3" y1="6" x2="3.01" y2="6" />
              <line x1="3" y1="12" x2="3.01" y2="12" /><line x1="3" y1="18" x2="3.01" y2="18" />
            </svg>
            Vista Lista
          </button>

          <button
            onClick={() => setSearchParams({ view: 'calendar' })}
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 8,
              padding: '10px 24px',
              borderRadius: 10,
              border: 'none',
              fontFamily: 'Inter, sans-serif',
              fontWeight: 600,
              fontSize: 14,
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              background: view === 'calendar' ? '#1A1A2E' : 'transparent',
              color: view === 'calendar' ? '#fff' : '#6B7280',
            }}
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke={view === 'calendar' ? '#E8A838' : '#6B7280'} strokeWidth="2.5">
              <rect x="3" y="4" width="18" height="18" rx="2" />
              <line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Vista Calendario
          </button>
        </div>

        {/* ── CALENDAR VIEW ── */}
        {view === 'calendar' ? (
          <div>
            <CalendarView />
            <div style={{ marginTop: 48 }}>
              <h3 style={{
                fontFamily: "'Playfair Display', serif",
                fontWeight: 700,
                fontSize: 24,
                color: '#1A1A2E',
                marginBottom: 20,
              }}>Tutti gli eventi in programma</h3>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                {eventi.map(e => <EventRow key={e.id} evento={e} />)}
              </div>
            </div>
          </div>

        ) : (
          /* ── LIST VIEW ── */
          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))',
            gap: 28,
          }}>
            {eventi.map(evento => (
              <EventCard key={evento.id} evento={evento} />
            ))}
          </div>
        )}
      </div>
    </main>
  );
}
