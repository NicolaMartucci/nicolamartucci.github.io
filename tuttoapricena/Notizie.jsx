import React, { useState, useEffect } from 'react';

/**
 * 📰 COMPONENTE NOTIZIE - PRONTO ALL'USO
 * 
 * Legge automaticamente i dati da window.TA_DATA
 * creato dallo script nel tag <script> di index.html
 * 
 * COME USARE:
 * 1. Copia questo file nel tuo progetto React (es: src/components/Notizie.jsx)
 * 2. Importa: import Notizie from './components/Notizie'
 * 3. Usa: <Notizie />
 * 
 * FUNZIONA CON:
 * - Vite
 * - Create React App
 * - Next.js
 * - Qualsiasi setup React
 */

function Notizie() {
  const [notizie, setNotizie] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    console.log('🔍 [Notizie] Componente montato, cerco dati...');

    const loadData = () => {
      console.log('🔄 [Notizie] Tentativo caricamento dati...');
      
      // Controlla se window.TA_DATA esiste
      if (!window.TA_DATA) {
        console.warn('⚠️ [Notizie] window.TA_DATA non trovato, riproverò tra 500ms...');
        return false;
      }

      console.log('✅ [Notizie] window.TA_DATA trovato:', window.TA_DATA);

      // Controlla se ci sono notizie
      if (!window.TA_DATA.notizie) {
        console.error('❌ [Notizie] window.TA_DATA.notizie non esiste!');
        setError('Struttura dati non valida');
        setLoading(false);
        return true;
      }

      const tutteLeNotizie = window.TA_DATA.notizie;
      console.log('📊 [Notizie] Totale notizie nel DB:', tutteLeNotizie.length);

      // Filtra solo quelle pubblicate
      const pubblicate = tutteLeNotizie.filter(n => {
        const isPubblicata = n.status === 'pubblicata';
        console.log(`  - "${n.title}" → status: "${n.status}" → ${isPubblicata ? '✅ PUBBLICATA' : '❌ NON PUBBLICATA'}`);
        return isPubblicata;
      });

      console.log('✅ [Notizie] Notizie pubblicate:', pubblicate.length);

      // Ordina per data (più recenti prima)
      const ordinate = pubblicate.sort((a, b) => {
        const dateA = new Date(a.date || '1970-01-01');
        const dateB = new Date(b.date || '1970-01-01');
        return dateB - dateA;
      });

      console.log('📋 [Notizie] Notizie ordinate:', ordinate.map(n => n.title));

      setNotizie(ordinate);
      setLoading(false);
      return true;
    };

    // Tentativo immediato
    const success = loadData();

    if (!success) {
      // Se fallisce, ascolta l'evento 'ta-ready'
      console.log('⏳ [Notizie] Attesa evento ta-ready...');
      
      const handleReady = () => {
        console.log('🎉 [Notizie] Evento ta-ready ricevuto!');
        loadData();
      };

      document.addEventListener('ta-ready', handleReady);

      // Retry ogni 500ms per max 10 secondi
      let attempts = 0;
      const maxAttempts = 20;
      const retryInterval = setInterval(() => {
        attempts++;
        console.log(`🔄 [Notizie] Retry ${attempts}/${maxAttempts}...`);
        
        if (loadData() || attempts >= maxAttempts) {
          clearInterval(retryInterval);
          if (attempts >= maxAttempts && !window.TA_DATA) {
            console.error('❌ [Notizie] Timeout: dati non caricati dopo 10 secondi');
            setError('Impossibile caricare le notizie. Controlla la console per dettagli.');
            setLoading(false);
          }
        }
      }, 500);

      return () => {
        document.removeEventListener('ta-ready', handleReady);
        clearInterval(retryInterval);
      };
    }
  }, []);

  // STATO: Caricamento
  if (loading) {
    return (
      <div style={styles.container}>
        <div style={styles.loading}>
          <div style={styles.spinner}></div>
          <p>Caricamento notizie...</p>
        </div>
      </div>
    );
  }

  // STATO: Errore
  if (error) {
    return (
      <div style={styles.container}>
        <div style={styles.error}>
          <h3>❌ Errore</h3>
          <p>{error}</p>
          <details style={{ marginTop: '12px', fontSize: '13px' }}>
            <summary>Dettagli tecnici</summary>
            <pre style={{ background: '#f5f5f5', padding: '12px', borderRadius: '6px', marginTop: '8px', overflow: 'auto' }}>
              {JSON.stringify({
                window_TA_DATA: window.TA_DATA ? 'Presente' : 'Assente',
                notizie: window.TA_DATA?.notizie?.length || 0,
                timestamp: new Date().toISOString()
              }, null, 2)}
            </pre>
          </details>
        </div>
      </div>
    );
  }

  // STATO: Nessuna notizia
  if (notizie.length === 0) {
    return (
      <div style={styles.container}>
        <div style={styles.empty}>
          <div style={styles.emptyIcon}>📰</div>
          <h3>Nessuna notizia pubblicata</h3>
          <p>Non ci sono notizie da mostrare al momento.</p>
          <details style={{ marginTop: '12px', fontSize: '13px', textAlign: 'left' }}>
            <summary>Debug info</summary>
            <pre style={{ background: '#f5f5f5', padding: '12px', borderRadius: '6px', marginTop: '8px', overflow: 'auto' }}>
              {JSON.stringify({
                totale_notizie_db: window.TA_DATA?.notizie?.length || 0,
                notizie_pubblicate: notizie.length,
                status_notizie: window.TA_DATA?.notizie?.map(n => ({ title: n.title, status: n.status })) || []
              }, null, 2)}
            </pre>
          </details>
        </div>
      </div>
    );
  }

  // STATO: Notizie presenti
  return (
    <div style={styles.container}>
      <div style={styles.header}>
        <h2>📰 Ultime Notizie</h2>
        <span style={styles.badge}>{notizie.length} {notizie.length === 1 ? 'notizia' : 'notizie'}</span>
      </div>

      <div style={styles.grid}>
        {notizie.map(notizia => (
          <article key={notizia.id} style={styles.card}>
            {notizia.img && (
              <img 
                src={notizia.img} 
                alt={notizia.title}
                style={styles.image}
                onError={(e) => e.target.style.display = 'none'}
              />
            )}
            
            <div style={styles.content}>
              <div style={styles.meta}>
                <span style={styles.category}>{notizia.cat || 'Generale'}</span>
                <span style={styles.date}>{formatDate(notizia.date)}</span>
              </div>

              <h3 style={styles.title}>{notizia.title}</h3>
              
              {notizia.summary && (
                <p style={styles.summary}>{notizia.summary}</p>
              )}

              {notizia.author && (
                <div style={styles.author}>
                  <span>✍️ {notizia.author}</span>
                </div>
              )}

              {notizia.tags && notizia.tags.length > 0 && (
                <div style={styles.tags}>
                  {notizia.tags.map((tag, i) => (
                    <span key={i} style={styles.tag}>#{tag}</span>
                  ))}
                </div>
              )}
            </div>
          </article>
        ))}
      </div>
    </div>
  );
}

// Helper: formatta data in italiano
function formatDate(dateString) {
  if (!dateString) return '';
  try {
    const date = new Date(dateString);
    return date.toLocaleDateString('it-IT', { 
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    });
  } catch {
    return dateString;
  }
}

// Stili inline (puoi spostarli in CSS esterno)
const styles = {
  container: {
    maxWidth: '1200px',
    margin: '0 auto',
    padding: '40px 20px'
  },
  header: {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    marginBottom: '32px'
  },
  badge: {
    background: '#d63031',
    color: 'white',
    padding: '4px 12px',
    borderRadius: '20px',
    fontSize: '14px',
    fontWeight: '600'
  },
  grid: {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))',
    gap: '24px'
  },
  card: {
    background: 'white',
    borderRadius: '12px',
    overflow: 'hidden',
    boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
    transition: 'transform 0.2s, box-shadow 0.2s',
    cursor: 'pointer'
  },
  image: {
    width: '100%',
    height: '200px',
    objectFit: 'cover'
  },
  content: {
    padding: '20px'
  },
  meta: {
    display: 'flex',
    alignItems: 'center',
    gap: '12px',
    marginBottom: '12px',
    fontSize: '13px'
  },
  category: {
    background: '#3498db',
    color: 'white',
    padding: '4px 10px',
    borderRadius: '6px',
    fontWeight: '600',
    textTransform: 'uppercase',
    fontSize: '11px',
    letterSpacing: '0.5px'
  },
  date: {
    color: '#666'
  },
  title: {
    fontSize: '20px',
    fontWeight: '700',
    marginBottom: '12px',
    lineHeight: '1.3',
    color: '#2c3e50'
  },
  summary: {
    fontSize: '14px',
    lineHeight: '1.6',
    color: '#555',
    marginBottom: '12px'
  },
  author: {
    fontSize: '13px',
    color: '#888',
    marginTop: '12px',
    paddingTop: '12px',
    borderTop: '1px solid #eee'
  },
  tags: {
    display: 'flex',
    flexWrap: 'wrap',
    gap: '6px',
    marginTop: '12px'
  },
  tag: {
    background: '#f0f0f0',
    color: '#666',
    padding: '4px 8px',
    borderRadius: '6px',
    fontSize: '12px'
  },
  loading: {
    textAlign: 'center',
    padding: '60px 20px'
  },
  spinner: {
    width: '40px',
    height: '40px',
    border: '4px solid #f3f3f3',
    borderTop: '4px solid #d63031',
    borderRadius: '50%',
    animation: 'spin 1s linear infinite',
    margin: '0 auto 16px'
  },
  error: {
    background: '#fee',
    border: '1px solid #fcc',
    borderRadius: '12px',
    padding: '24px',
    textAlign: 'center'
  },
  empty: {
    textAlign: 'center',
    padding: '60px 20px',
    color: '#999'
  },
  emptyIcon: {
    fontSize: '64px',
    marginBottom: '16px'
  }
};

// CSS per animazione spinner (aggiungi al CSS globale)
const spinnerKeyframes = `
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
`;

// Inietta keyframes
if (typeof document !== 'undefined') {
  const style = document.createElement('style');
  style.textContent = spinnerKeyframes;
  document.head.appendChild(style);
}

export default Notizie;
