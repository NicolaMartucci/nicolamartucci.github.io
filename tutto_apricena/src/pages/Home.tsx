import HeroSection from '../components/home/HeroSection';
import NewsSection from '../components/home/NewsSection';
import EventiSection from '../components/home/EventiSection';
import FarmacieWidget from '../components/home/FarmacieWidget';
import LocaliSection from '../components/home/LocaliSection';
import ServiziWidget from '../components/home/ServiziWidget';
import SponsorSection from '../components/home/SponsorSection';
import CittaSection from '../components/home/CittaSection';

export default function Home() {
  return (
    <main>
      <HeroSection />
      <FarmacieWidget />
      <NewsSection />
      <CittaSection />
      <EventiSection />
      <LocaliSection />
      <ServiziWidget />
      <SponsorSection />
    </main>
  );
}
