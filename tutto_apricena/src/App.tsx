import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { HelmetProvider } from 'react-helmet-async';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import Home from './pages/Home';
import Notizie from './pages/Notizie';
import SingleNotizia from './pages/SingleNotizia';
import Eventi from './pages/Eventi';
import SingleEvento from './pages/SingleEvento';
import Farmacie from './pages/Farmacie';
import Servizi from './pages/Servizi';
import Locali from './pages/Locali';
import SingleLocale from './pages/SingleLocale';
import Sponsor from './pages/Sponsor';
import Contatti from './pages/Contatti';
import ChiSiamo from './pages/ChiSiamo';
import Privacy from './pages/Privacy';
import Cookie from './pages/Cookie';
import NotFound from './pages/NotFound';
import ScrollToTop from './components/ScrollToTop';

export default function App() {
  return (
    <HelmetProvider>
      <BrowserRouter>
        <ScrollToTop />
        <Navbar />
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/notizie" element={<Notizie />} />
          <Route path="/notizie/:slug" element={<SingleNotizia />} />
          <Route path="/eventi" element={<Eventi />} />
          <Route path="/eventi/:slug" element={<SingleEvento />} />
          <Route path="/farmacie" element={<Farmacie />} />
          <Route path="/servizi" element={<Servizi />} />
          <Route path="/locali" element={<Locali />} />
          <Route path="/locali/:slug" element={<SingleLocale />} />
          <Route path="/sponsor" element={<Sponsor />} />
          <Route path="/contatti" element={<Contatti />} />
          <Route path="/chi-siamo" element={<ChiSiamo />} />
          <Route path="/privacy" element={<Privacy />} />
          <Route path="/cookie" element={<Cookie />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
        <Footer />
      </BrowserRouter>
    </HelmetProvider>
  );
}
