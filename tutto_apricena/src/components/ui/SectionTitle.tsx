

interface SectionTitleProps {
  eyebrow?: string;
  title: string;
  subtitle?: string;
  align?: 'left' | 'center';
  light?: boolean;
}

export default function SectionTitle({ eyebrow, title, subtitle, align = 'left', light = false }: SectionTitleProps) {
  return (
    <div className={`mb-8 ${align === 'center' ? 'text-center' : ''}`}>
      {eyebrow && (
        <span className={`text-sm font-semibold tracking-widest uppercase ${light ? 'text-[#E8A838]' : 'text-[#E8A838]'}`}>
          {eyebrow}
        </span>
      )}
      <h2 className={`mt-1 text-3xl md:text-4xl font-bold leading-tight ${light ? 'text-white' : 'text-[#1A1A2E]'}`}
        style={{ fontFamily: "'Playfair Display', serif" }}>
        {title}
      </h2>
      {subtitle && (
        <p className={`mt-3 text-base md:text-lg ${light ? 'text-white/70' : 'text-[#6B7280]'}`}>
          {subtitle}
        </p>
      )}
    </div>
  );
}
