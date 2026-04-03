import { useState } from 'react';
import { Mail, MapPin, Send, CheckCircle } from 'lucide-react';

export default function Contatti() {
  const [sent, setSent] = useState(false);
  const [form, setForm] = useState({ nome: '', email: '', oggetto: '', messaggio: '' });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSent(true);
  };

  return (
    <main style={{ paddingTop: 72, minHeight: '100vh', background: 'var(--color-surface)' }}>
      {/* Header */}
      <div style={{ background: 'var(--color-primary)', padding: '48px 24px 52px' }}>
        <div style={{ maxWidth: 1280, margin: '0 auto' }}>
          <span style={{ display: 'block', color: 'var(--color-accent)', fontSize: 11, fontWeight: 700, letterSpacing: '0.18em', textTransform: 'uppercase', marginBottom: 8 }}>
            Contatti
          </span>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(2rem, 5vw, 3rem)', fontWeight: 900, color: '#fff', marginBottom: 10 }}>
            Scrivici
          </h1>
          <p style={{ color: 'rgba(255,255,255,0.55)', fontSize: 16 }}>
            Hai una segnalazione, una notizia o vuoi collaborare? Contattaci.
          </p>
        </div>
      </div>

      <div style={{ maxWidth: 1000, margin: '0 auto', padding: '56px 24px 80px' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 2fr', gap: 48 }} className="contatti-grid">

          {/* Info sidebar */}
          <div>
            <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-primary)', marginBottom: 20 }}>
              Informazioni
            </h2>

            <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
              <div style={{ display: 'flex', gap: 14, alignItems: 'flex-start' }}>
                <div style={{ width: 44, height: 44, borderRadius: 12, background: 'rgba(232,168,56,0.12)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <Mail size={20} color="var(--color-accent)" />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: 11, fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.1em', color: 'var(--color-text-muted)', marginBottom: 3 }}>Email</span>
                  <a href="mailto:info@tuttoapricena.it" style={{ color: 'var(--color-primary)', fontWeight: 600, fontSize: 14, textDecoration: 'none' }}>
                    info@tuttoapricena.it
                  </a>
                </div>
              </div>

              <div style={{ display: 'flex', gap: 14, alignItems: 'flex-start' }}>
                <div style={{ width: 44, height: 44, borderRadius: 12, background: 'rgba(232,168,56,0.12)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <MapPin size={20} color="var(--color-accent)" />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: 11, fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.1em', color: 'var(--color-text-muted)', marginBottom: 3 }}>Dove siamo</span>
                  <span style={{ color: 'var(--color-primary)', fontWeight: 600, fontSize: 14 }}>
                    Apricena (FG), Puglia
                  </span>
                </div>
              </div>
            </div>

            <div style={{ marginTop: 32, padding: '20px', background: '#fff', borderRadius: 16, boxShadow: '0 2px 12px rgba(26,26,46,0.07)' }}>
              <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 16, fontWeight: 700, color: 'var(--color-primary)', marginBottom: 10 }}>
                Vuoi segnalare una notizia?
              </h3>
              <p style={{ fontSize: 13, color: 'var(--color-text-muted)', lineHeight: 1.6 }}>
                Se hai informazioni su eventi, notizie locali o vuoi proporre contenuti, scrivici. Ogni segnalazione viene valutata dalla redazione.
              </p>
            </div>
          </div>

          {/* Form */}
          <div>
            {sent ? (
              <div style={{ background: '#fff', borderRadius: 20, padding: '48px 36px', textAlign: 'center', boxShadow: '0 4px 24px rgba(26,26,46,0.08)' }}>
                <CheckCircle size={52} color="#16A34A" style={{ margin: '0 auto 20px' }} />
                <h2 style={{ fontFamily: 'var(--font-display)', fontSize: '1.6rem', fontWeight: 800, color: 'var(--color-primary)', marginBottom: 10 }}>
                  Messaggio inviato!
                </h2>
                <p style={{ color: 'var(--color-text-muted)', fontSize: 15 }}>
                  Grazie per averci scritto. Ti risponderemo al più presto.
                </p>
              </div>
            ) : (
              <form onSubmit={handleSubmit} style={{ background: '#fff', borderRadius: 20, padding: '36px', boxShadow: '0 4px 24px rgba(26,26,46,0.08)' }}>
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16, marginBottom: 16 }} className="form-row">
                  <div>
                    <label style={{ display: 'block', fontSize: 12, fontWeight: 600, color: 'var(--color-primary)', marginBottom: 6 }}>Nome *</label>
                    <input
                      type="text" required
                      value={form.nome}
                      onChange={e => setForm({ ...form, nome: e.target.value })}
                      placeholder="Il tuo nome"
                      style={{ width: '100%', padding: '11px 14px', borderRadius: 10, border: '1.5px solid #e0e0e0', fontSize: 14, outline: 'none', fontFamily: 'var(--font-body)', transition: 'border-color 0.2s' }}
                      onFocus={e => (e.target as HTMLInputElement).style.borderColor = 'var(--color-accent)'}
                      onBlur={e => (e.target as HTMLInputElement).style.borderColor = '#e0e0e0'}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: 12, fontWeight: 600, color: 'var(--color-primary)', marginBottom: 6 }}>Email *</label>
                    <input
                      type="email" required
                      value={form.email}
                      onChange={e => setForm({ ...form, email: e.target.value })}
                      placeholder="La tua email"
                      style={{ width: '100%', padding: '11px 14px', borderRadius: 10, border: '1.5px solid #e0e0e0', fontSize: 14, outline: 'none', fontFamily: 'var(--font-body)', transition: 'border-color 0.2s' }}
                      onFocus={e => (e.target as HTMLInputElement).style.borderColor = 'var(--color-accent)'}
                      onBlur={e => (e.target as HTMLInputElement).style.borderColor = '#e0e0e0'}
                    />
                  </div>
                </div>
                <div style={{ marginBottom: 16 }}>
                  <label style={{ display: 'block', fontSize: 12, fontWeight: 600, color: 'var(--color-primary)', marginBottom: 6 }}>Oggetto *</label>
                  <input
                    type="text" required
                    value={form.oggetto}
                    onChange={e => setForm({ ...form, oggetto: e.target.value })}
                    placeholder="Oggetto del messaggio"
                    style={{ width: '100%', padding: '11px 14px', borderRadius: 10, border: '1.5px solid #e0e0e0', fontSize: 14, outline: 'none', fontFamily: 'var(--font-body)', transition: 'border-color 0.2s' }}
                    onFocus={e => (e.target as HTMLInputElement).style.borderColor = 'var(--color-accent)'}
                    onBlur={e => (e.target as HTMLInputElement).style.borderColor = '#e0e0e0'}
                  />
                </div>
                <div style={{ marginBottom: 24 }}>
                  <label style={{ display: 'block', fontSize: 12, fontWeight: 600, color: 'var(--color-primary)', marginBottom: 6 }}>Messaggio *</label>
                  <textarea
                    required
                    rows={6}
                    value={form.messaggio}
                    onChange={e => setForm({ ...form, messaggio: e.target.value })}
                    placeholder="Scrivi il tuo messaggio..."
                    style={{ width: '100%', padding: '11px 14px', borderRadius: 10, border: '1.5px solid #e0e0e0', fontSize: 14, outline: 'none', fontFamily: 'var(--font-body)', resize: 'vertical', transition: 'border-color 0.2s' }}
                    onFocus={e => (e.target as HTMLTextAreaElement).style.borderColor = 'var(--color-accent)'}
                    onBlur={e => (e.target as HTMLTextAreaElement).style.borderColor = '#e0e0e0'}
                  />
                </div>
                <button type="submit" style={{
                  display: 'inline-flex', alignItems: 'center', gap: 8,
                  background: 'var(--color-accent)', color: 'var(--color-primary)',
                  fontSize: 15, fontWeight: 700, padding: '13px 32px', borderRadius: 50,
                  border: 'none', cursor: 'pointer', transition: 'all 0.2s',
                  fontFamily: 'var(--font-body)',
                }}
                  onMouseEnter={e => (e.currentTarget as HTMLElement).style.background = 'var(--color-primary)'}
                  onMouseLeave={e => (e.currentTarget as HTMLElement).style.background = 'var(--color-accent)'}
                >
                  <Send size={16} /> Invia messaggio
                </button>
              </form>
            )}
          </div>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .contatti-grid { grid-template-columns: 1fr !important; }
          .form-row { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </main>
  );
}
