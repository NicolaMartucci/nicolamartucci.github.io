import { Link } from 'react-router-dom';
import { Home, ArrowLeft } from 'lucide-react';

export default function NotFound() {
  return (
    <main style={{
      paddingTop: 72,
      minHeight: '100vh',
      background: 'var(--color-surface)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      padding: '80px 24px',
    }}>
      <div style={{ textAlign: 'center', maxWidth: 480 }}>
        <div style={{
          fontFamily: 'var(--font-display)',
          fontSize: 'clamp(5rem, 15vw, 10rem)',
          fontWeight: 900,
          color: 'var(--color-primary)',
          lineHeight: 1,
          marginBottom: 16,
          opacity: 0.08,
          userSelect: 'none',
        }}>
          404
        </div>
        <div style={{ marginTop: -60 }}>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: '2rem', fontWeight: 900, color: 'var(--color-primary)', marginBottom: 14 }}>
            Pagina non trovata
          </h1>
          <p style={{ color: 'var(--color-text-muted)', fontSize: 16, lineHeight: 1.7, marginBottom: 36 }}>
            La pagina che stai cercando non esiste o è stata spostata.
            Torna alla homepage per trovare quello che cerchi.
          </p>
          <div style={{ display: 'flex', gap: 14, justifyContent: 'center', flexWrap: 'wrap' }}>
            <Link to="/" style={{
              display: 'inline-flex', alignItems: 'center', gap: 8,
              background: 'var(--color-accent)', color: 'var(--color-primary)',
              fontWeight: 700, fontSize: 14, padding: '12px 28px', borderRadius: 50, textDecoration: 'none',
            }}>
              <Home size={15} /> Homepage
            </Link>
            <button
              onClick={() => window.history.back()}
              style={{
                display: 'inline-flex', alignItems: 'center', gap: 8,
                border: '2px solid var(--color-primary)', color: 'var(--color-primary)',
                fontWeight: 600, fontSize: 14, padding: '11px 26px', borderRadius: 50,
                background: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)',
              }}
            >
              <ArrowLeft size={15} /> Torna indietro
            </button>
          </div>
        </div>
      </div>
    </main>
  );
}
