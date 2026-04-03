import React from 'react';

interface BadgeProps {
  children: React.ReactNode;
  color?: string;
  variant?: 'solid' | 'outline';
  size?: 'sm' | 'md' | 'lg';
}

const categoryColors: Record<string, string> = {
  cronaca: '#DC2626',
  cultura: '#7C3AED',
  sport: '#16A34A',
  economia: '#0284C7',
  societa: '#EA580C',
  turismo: '#E8A838',
  religioso: '#7C3AED',
  gastronomia: '#EA580C',
  musica: '#0284C7',
  gold: '#E8A838',
  silver: '#6B7280',
  bronze: '#92400E',
};

export default function Badge({ children, color, variant = 'solid', size = 'sm' }: BadgeProps) {
  const key = typeof children === 'string' ? children.toLowerCase() : '';
  const bg = color || categoryColors[key] || '#E8A838';

  const padding =
    size === 'lg' ? '6px 14px' :
    size === 'md' ? '4px 12px' :
    '3px 10px';

  const fontSize =
    size === 'lg' ? '13px' :
    size === 'md' ? '12px' :
    '11px';

  if (variant === 'outline') {
    return (
      <span style={{
        display: 'inline-block',
        borderRadius: '999px',
        fontFamily: 'Inter, sans-serif',
        fontWeight: 600,
        letterSpacing: '0.05em',
        textTransform: 'uppercase',
        padding,
        fontSize,
        color: bg,
        border: `2px solid ${bg}`,
        whiteSpace: 'nowrap',
        lineHeight: 1.4,
      }}>
        {children}
      </span>
    );
  }

  return (
    <span style={{
      display: 'inline-block',
      borderRadius: '999px',
      fontFamily: 'Inter, sans-serif',
      fontWeight: 600,
      letterSpacing: '0.05em',
      textTransform: 'uppercase',
      padding,
      fontSize,
      backgroundColor: bg,
      color: '#ffffff',
      whiteSpace: 'nowrap',
      lineHeight: 1.4,
    }}>
      {children}
    </span>
  );
}
